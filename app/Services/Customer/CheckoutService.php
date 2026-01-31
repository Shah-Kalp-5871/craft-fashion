<?php

namespace App\Services\Customer;

use App\Helpers\CartHelper;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\StockHistory;
use App\Models\Payment;
use App\Models\PaymentAttempt;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutService
{
    protected CartHelper $cartHelper;
    protected $cart;
    protected $customer;

    public function __construct(CartHelper $cartHelper)
    {
        $this->cartHelper = $cartHelper;
        $this->cart = $cartHelper->getCart();
        $this->customer = Auth::guard('customer')->user();
    }

    /**
     * Main entry point for placing an order
     */
    public function placeOrder(array $checkoutData, array $paymentData = [])
    {
        return DB::transaction(function () use ($checkoutData, $paymentData) {
            Log::info('Starting order placement process', [
                'customer_id' => $this->customer?->id,
                'cart_items_count' => count($this->cart['items'] ?? [])
            ]);

            // Validate cart and stock
            $this->validateCart();

            // Create order
            $order = $this->createOrder($checkoutData);

            // Create order items
            $this->createOrderItems($order);

            // Update stock
            $this->updateStock($order);

            // Process payment
            $payment = $this->processPayment($order, $paymentData);

            // Clear cart
            $this->cartHelper->clearCart();

            Log::info('Order placed successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'grand_total' => $order->grand_total
            ]);

            return [
                'order' => $order,
                'payment' => $payment
            ];
        });
    }

    /**
     * Validate cart and stock availability
     */
    private function validateCart(): void
    {
        if (empty($this->cart['items'])) {
            Log::error('Cart validation failed: Cart is empty');
            throw new \Exception('Your cart is empty. Please add items before checkout.');
        }

        foreach ($this->cart['items'] as $item) {
            $variant = ProductVariant::with('product')
                ->where('sku', $item['sku'])
                ->first();

            if (!$variant) {
                Log::error('Variant not found during cart validation', [
                    'sku' => $item['sku'],
                    'cart_item' => $item
                ]);
                throw new \Exception("Product variant '{$item['product_name']}' is no longer available.");
            }

            if ($variant->stock_quantity < $item['quantity']) {
                Log::error('Insufficient stock during cart validation', [
                    'sku' => $item['sku'],
                    'requested_quantity' => $item['quantity'],
                    'available_stock' => $variant->stock_quantity
                ]);
                throw new \Exception("Insufficient stock for {$item['product_name']}. Available: {$variant->stock_quantity}, Requested: {$item['quantity']}");
            }

            // Check if product allows COD
            if (isset($item['cod_available']) && !$item['cod_available']) {
                Log::warning('COD not available for product', [
                    'product_name' => $item['product_name'],
                    'sku' => $item['sku']
                ]);
            }
        }

        Log::info('Cart validation passed', [
            'items_count' => count($this->cart['items'])
        ]);
    }

    /**
     * Create order record
     */
    private function createOrder(array $checkoutData): Order
    {
        try {
            $orderData = [
                'order_number' => $this->generateOrderNumber(),
                'customer_id' => $this->customer?->id,
                'subtotal' => $this->cart['subtotal'],
                'tax_total' => $this->cart['tax_total'],
                'shipping_total' => $checkoutData['shipping_cost'] ?? 0,
                'discount_total' => $this->cart['discount_total'] ?? 0,
                'grand_total' => $this->cart['subtotal'] + $this->cart['tax_total'] + ($checkoutData['shipping_cost'] ?? 0) - ($this->cart['discount_total'] ?? 0),
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_status' => 'pending',
                'shipping_address' => $this->prepareAddress($checkoutData, 'shipping'),
                'billing_address' => $this->prepareAddress($checkoutData, 'billing'),
                'customer_notes' => $checkoutData['notes'] ?? null,
                'shipping_method' => 'shiprocket',
                'payment_method' => $checkoutData['payment_method'] ?? 'cod',
            ];

            $order = Order::create($orderData);

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'grand_total' => $order->grand_total
            ]);

            return $order;

        } catch (\Exception $e) {
            Log::error('Failed to create order', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Failed to create order. Please try again.');
        }
    }

    /**
     * Create order items
     */
    private function createOrderItems(Order $order): void
    {
        try {
            foreach ($this->cart['items'] as $item) {
                $variant = ProductVariant::where('sku', $item['sku'])->first();

                if (!$variant) {
                    Log::error('Variant not found when creating order items', [
                        'sku' => $item['sku'],
                        'order_id' => $order->id
                    ]);
                    continue;
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $item['product_name'],
                    'sku' => $item['sku'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'compare_price' => $item['compare_price'] ?? $item['unit_price'],
                    'total' => $item['total'],
                    'discount_amount' => $item['discount_amount'] ?? 0,
                    'attributes' => $item['attributes'] ?? [],
                    'offer_id' => $item['offer_id'] ?? null,
                ]);
            }

            Log::info('Order items created', [
                'order_id' => $order->id,
                'items_count' => count($this->cart['items'])
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to create order items', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Failed to create order items. Please try again.');
        }
    }

    /**
     * Update stock quantities
     */
    private function updateStock(Order $order): void
    {
        try {
            foreach ($order->items as $item) {
                $variant = $item->variant;

                // Check stock again to prevent race conditions
                if ($variant->stock_quantity < $item->quantity) {
                    throw new \Exception("Insufficient stock for SKU {$item->sku}. Available: {$variant->stock_quantity}, Ordered: {$item->quantity}");
                }

                // Update stock
                $oldStock = $variant->stock_quantity;
                $variant->decrement('stock_quantity', $item->quantity);

                // Create stock history
                StockHistory::create([
                    'product_variant_id' => $variant->id,
                    'change_type' => 'order',
                    'quantity' => -$item->quantity,
                    'source_type' => 'order',
                    'source_id' => $order->id,
                    'reason' => 'Order placed',
                    'stock_before' => $oldStock,
                    'stock_after' => $oldStock - $item->quantity,
                    'reference' => $order->order_number,
                ]);

                Log::info('Stock updated for variant', [
                    'variant_id' => $variant->id,
                    'sku' => $variant->sku,
                    'quantity_reduced' => $item->quantity,
                    'new_stock' => $variant->stock_quantity
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to update stock', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Failed to update inventory. Please try again.');
        }
    }

    /**
     * Process payment
     */
    private function processPayment(Order $order, array $paymentData): ?Payment
    {
        try {
            $paymentMethod = $order->payment_method;

            if ($paymentMethod === 'cod') {
                // For COD, create pending payment
                $payment = Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => 'cod',
                    'amount' => $order->grand_total,
                    'currency' => 'INR',
                    'status' => 'pending',
                    'transaction_id' => 'COD_' . Str::random(16),
                    'payment_details' => ['method' => 'cash_on_delivery'],
                ]);

                $order->update(['payment_status' => 'pending']);

                Log::info('COD payment recorded', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id
                ]);

                return $payment;

            } elseif ($paymentMethod === 'online' && !empty($paymentData['razorpay_payment_id'])) {
                // For online payments, verify with Razorpay
                // This will be handled by the RazorpayService
                // Return null here as payment will be processed separately
                return null;
            }

            Log::warning('Unknown payment method', [
                'order_id' => $order->id,
                'payment_method' => $paymentMethod
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('Failed to process payment', [
                'order_id' => $order->id,
                'payment_method' => $order->payment_method,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Payment processing failed. Please try again.');
        }
    }

    /**
     * Prepare address data
     */
    private function prepareAddress(array $checkoutData, string $type): array
    {
        try {
            $prefix = $type === 'shipping' ? '' : 'billing_';
            $useShipping = $type === 'billing' && ($checkoutData['same_as_shipping'] ?? true);

            $address = [
                'name' => $checkoutData[$prefix . 'name'] ?? $checkoutData['name'] ?? $checkoutData['full_name'] ?? '',
                'email' => $checkoutData[$prefix . 'email'] ?? $checkoutData['email'] ?? '',
                'phone' => $checkoutData[$prefix . 'phone'] ?? $checkoutData['phone'] ?? '',
                'address' => $checkoutData[$prefix . 'address'] ?? $checkoutData['address'] ?? '',
                'address2' => $checkoutData[$prefix . 'address2'] ?? $checkoutData['address2'] ?? null,
                'city' => $checkoutData[$prefix . 'city'] ?? $checkoutData['city'] ?? '',
                'state' => $checkoutData[$prefix . 'state'] ?? $checkoutData['state'] ?? '',
                'country' => $checkoutData[$prefix . 'country'] ?? $checkoutData['country'] ?? 'India',
                'pincode' => $checkoutData[$prefix . 'pincode'] ?? $checkoutData['pincode'] ?? '',
            ];

            // Clean up empty values
            return array_filter($address, function ($value) {
                return $value !== null && $value !== '';
            });

        } catch (\Exception $e) {
            Log::error('Failed to prepare address', [
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Invalid address data provided.');
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(Str::random(6));
        return "ORD-{$timestamp}-{$random}";
    }

    /**
     * Check if COD is available for all products in cart
     */
    public function isCODAvailable(): bool
    {
        foreach ($this->cart['items'] as $item) {
            $variant = ProductVariant::where('sku', $item['sku'])->first();

            if ($variant && isset($variant->product->cod_available) && !$variant->product->cod_available) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get available payment methods based on cart items
     */
    public function getAvailablePaymentMethods(): array
    {
        $methods = [
            'online' => [
                'name' => 'Online Payment',
                'available' => !empty(config('services.razorpay.key_id')) && !empty(config('services.razorpay.key_secret'))
            ],
            'cod' => [
                'name' => 'Cash on Delivery',
                'available' => $this->isCODAvailable()
            ]
        ];

        return $methods;
    }
}
