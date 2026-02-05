<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CheckoutService;
use App\Services\Customer\RazorpayService;
use App\Services\Customer\ShiprocketService;
use App\Helpers\CartHelper;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        protected CheckoutService $checkoutService,
        protected CartHelper $cartHelper,
        protected RazorpayService $razorpayService,
        protected ShiprocketService $shiprocketService
    ) {
    }

    /* =====================================================
     | SHOW CHECKOUT
     ===================================================== */
    public function index()
    {
        $cart = $this->cartHelper->getCart();

        if (empty($cart['items'])) {
            return redirect()->route('customer.cart')
                ->with('error', 'Your cart is empty.');
        }

        return view('customer.checkout.index', [
            'cart' => $cart,
            'codAvailable' => $this->checkoutService->isCODAvailable(),
            'paymentMethods' => $this->checkoutService->getAvailablePaymentMethods(),
            'addresses' => Auth::guard('customer')->user()?->addresses ?? collect(),
        ]);
    }

    /* =====================================================
     | PROCESS CHECKOUT
     ===================================================== */
    public function processCheckout(Request $request)
    {
        $this->validateCheckout($request);

        if ($request->payment_method === 'cod') {
            return $this->processCOD($request);
        }

        return $this->processOnlinePayment($request);
    }

    /* =====================================================
     | COD FLOW
     ===================================================== */
    private function processCOD(Request $request)
    {
        $data = $request->all();
        $data['shipping_cost'] = $this->getShippingCost($request);

        $result = $this->checkoutService->placeOrder($data);

        if (!empty($result['order'])) {
            $this->shiprocketService->createOrder($result['order']);
        }

        return redirect()
            ->route('customer.checkout.confirmation', $result['order']->id)
            ->with('success', 'Order placed successfully!');
    }

    /* =====================================================
     | ONLINE PAYMENT INIT (NO DB ORDER)
     ===================================================== */
    private function processOnlinePayment(Request $request)
    {
        $cart = $this->cartHelper->getCart();

        $data = $request->all();
        $data['shipping_cost'] = $this->getShippingCost($request);

        session([
            'checkout_data' => $data
        ]);

        // Calculate correct total including shipping
        $shippingCost = $data['shipping_cost'];
        $grandTotal = $cart['subtotal'] + $cart['tax_total'] + $shippingCost - ($cart['discount_total'] ?? 0);

        $amountInPaise = (int) round($grandTotal * 100);

        $razorpayOrder = $this->razorpayService->createOrderByAmount($amountInPaise);

        if (!$razorpayOrder['success']) {
            return back()->with('error', $razorpayOrder['message']);
        }

        session([
            'razorpay_order_id' => $razorpayOrder['order_id']
        ]);

        return view('customer.checkout.payment', [
            'keyId' => $razorpayOrder['key_id'],
            'orderId' => $razorpayOrder['order_id'],
            'amount' => $grandTotal,
            'customer' => Auth::guard('customer')->user()
        ]);
    }

    /* =====================================================
     | RAZORPAY CALLBACK
     ===================================================== */
    public function paymentCallback(Request $request)
    {

        try {
            $request->validate([
                'razorpay_payment_id' => 'required',
                'razorpay_order_id' => 'required',
                'razorpay_signature' => 'required',
            ]);

            $checkoutData = session('checkout_data');

            if (!$checkoutData) {
                throw new \Exception('Checkout session expired');
            }

            $checkoutData['payment_method'] = 'online';


            // Create order AFTER payment success
            $result = $this->checkoutService->placeOrder(
                $checkoutData,
                $request->all()
            );

            $order = $result['order'];

            $paymentResult = $this->razorpayService->processPayment($order, $request->all());

            if (!$paymentResult['success']) {
                throw new \Exception($paymentResult['message'] ?? 'Payment processing failed');
            }

            $this->shiprocketService->createOrder($order);


            session()->forget([
                'checkout_data',
                'razorpay_order_id'
            ]);

            return redirect()
                ->route('customer.checkout.confirmation', $order->id)
                ->with('success', 'Payment successful!');

        } catch (\Exception $e) {

            Log::error('Payment failed', [
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->route('customer.checkout.payment.failed')
                ->with('error', $e->getMessage());
        }
    }

    /* =====================================================
     | CONFIRMATION
     ===================================================== */
    public function confirmation($orderId)
    {
        $order = Order::where('customer_id', Auth::guard('customer')->id())
            ->with(['items.variant.product', 'shipments'])
            ->findOrFail($orderId);

        return response()->view('customer.checkout.confirmation', compact('order'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /* =====================================================
     | SHIPPING CHECK (SHIPROCKET)
     ===================================================== */
    public function checkShipping(Request $request)
    {
        $request->validate(['pincode' => 'required']);

        $cart = $this->cartHelper->getCart();
        $weight = $this->calculateCartWeight($cart);

        $dimensions = $this->calculateCartDimensions($cart);

        $result = $this->shiprocketService->checkServiceability($request->pincode, $weight, $dimensions);
        
        // Extract city and state from Shiprocket response
        if ($result['success'] && isset($result['raw_data']['data']['available_courier_companies'][0])) {
            $courierData = $result['raw_data']['data']['available_courier_companies'][0];
            $result['city'] = $courierData['city'] ?? null;
            $result['state'] = $courierData['state'] ?? null;
        }
        
        return response()->json($result);
    }

    /* =====================================================
     | HELPERS
     ===================================================== */
    private function calculateCartDimensions($cart): array
    {
        $length = 10;
        $width = 10;
        $height = 10;
        
        // Simple bounding box logic: max dimensions
        // A more complex logic would be volume based or 3D packing, but max of each dim 
        // ensures the box is at least big enough for the largest item.
        // Then we can sum heights? Or just take max of all?
        // Let's take max of Length/Width and sum of Heights? 
        // Shiprocket expects a single box dimension.
        // Ideally we should sum volume and estimate box, but max(L), max(W), max(H) 
        // is safer default than 10x10x10 if we have large items.
        // Let's iterate.

        $maxLength = 10;
        $maxWidth = 10;
        $maxHeight = 10;

        foreach ($cart['items'] as $item) {
             $variant = ProductVariant::where('sku', $item['sku'])->first();
             // Fallback to product if variant dims are null (which shouldn't be due to default, but safety)
             // Prioritize variant > product > default 10
             
             $l = $variant->length ?? ($item->product->length ?? 10);
             $w = $variant->width ?? ($item->product->width ?? 10);
             $h = $variant->height ?? ($item->product->height ?? 10);
             
             if ($l > $maxLength) $maxLength = $l;
             if ($w > $maxWidth) $maxWidth = $w;
             // Height might be additive if stacked? 
             // For now let's just take max height too to keep shipping cost low/reasonable 
             // unless we want to sum heights. 
             // User requested "cheap price", so max dims is better than sum dims 
             // (which implies stacking everything vertically).
             if ($h > $maxHeight) $maxHeight = $h;
        }

        return [
            'length' => $maxLength,
            'width' => $maxWidth,
            'height' => $maxHeight
        ];
    }

    private function calculateCartWeight($cart): float
    {
        $weight = 0;

        foreach ($cart['items'] as $item) {
            $variant = ProductVariant::where('sku', $item['sku'])->first();
            $itemWeight = $variant->weight ?? ($item->product->weight ?? 0.1);
            $weight += $itemWeight * $item['quantity'];
        }

        return max($weight, 0.1);
    }

    private function validateCheckout(Request $request): void
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
            'payment_method' => ['required', Rule::in(['online', 'cod'])],
            'delivery' => ['required', Rule::in(['standard', 'express'])],
        ])->validate();
    }

    public function createRazorpayOrder(Request $request)
    {
        $this->validateCheckout($request);
        $cart = $this->cartHelper->getCart();

        if (empty($cart['items'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        // Store checkout data for callback
        $data = $request->all();
        $data['shipping_cost'] = $this->getShippingCost($request);
        session(['checkout_data' => $data]);

        // Calculate correct total including shipping
        $shippingCost = $data['shipping_cost'];
        $grandTotal = $cart['subtotal'] + $cart['tax_total'] + $shippingCost - ($cart['discount_total'] ?? 0);

        // Razorpay expects paise
        $amountInPaise = (int) round($grandTotal * 100);

        $razorpayOrder = $this->razorpayService
            ->createOrderByAmount($amountInPaise);

        return response()->json($razorpayOrder);
    }

    /* =====================================================
 | PAYMENT FAILED PAGE
 ===================================================== */
    public function paymentFailed()
    {
        return view('customer.checkout.payment_failed');
    }


    private function getShippingCost(Request $request): float
    {
        return $request->input('delivery') === 'express' ? 200.00 : 100.00;
    }
}
