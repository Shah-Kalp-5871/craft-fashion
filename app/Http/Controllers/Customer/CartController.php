<?php
// app/Http\Controllers\Customer\CartController.php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    protected $cartHelper;

    public function __construct(CartHelper $cartHelper)
    {
        $this->cartHelper = $cartHelper;
    }

    public function index()
    {
        $cart = $this->cartHelper->getCart();

        // Get recommended products
        $recommendedProducts = [];
        if (count($cart['items'] ?? []) > 0) {
            $recommendedProducts = $this->getRecommendedProducts();
        }

        return view('customer.cart.index', compact('cart', 'recommendedProducts'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'attributes' => 'sometimes|array'
        ]);

        try {
            $result = $this->cartHelper->addToCart(
                $request->variant_id,
                $request->quantity,
                $request->attributes ?? []
            );

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function updateQuantity(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $cart = $this->cartHelper->updateItemQuantity($itemId, $request->quantity);

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully',
                'data' => [
                    'cart' => $cart,
                    'cart_count' => $cart['items_count']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function removeItem($itemId)
    {
        try {
            $cart = $this->cartHelper->removeItem($itemId);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart',
                'data' => [
                    'cart' => $cart,
                    'cart_count' => $cart['items_count']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function getCartSummary()
    {
        $cart = $this->cartHelper->getCart();

        return response()->json([
            'success' => true,
            'data' => $cart
        ]);
    }

    public function getCartCount()
    {
        return response()->json([
            'success' => true,
            'count' => $this->cartHelper->getCartCount()
        ]);
    }

    public function syncCart()
    {
        try {
            $result = $this->cartHelper->syncCart();

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cart synced successfully'
                ]);
            } else {
                throw new \Exception('Failed to sync cart');
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function clearCart()
    {
        try {
            $cart = $this->cartHelper->clearCart();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully',
                'data' => [
                    'cart' => $cart,
                    'cart_count' => $cart['items_count']
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        try {
            $result = $this->cartHelper->applyCoupon($request->coupon_code);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function removeCoupon()
    {
        try {
            $result = $this->cartHelper->removeCoupon();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    private function getRecommendedProducts()
    {
        try {
            // Get 4 random products that are in stock
            $products = Product::with(['mainImage', 'variants'])
                ->where('status', true)
                ->inRandomOrder()
                ->limit(4)
                ->get()
                ->map(function ($product) {
                    $minPrice = $product->variants->min('price') ?? 0;
                    $maxComparePrice = $product->variants->max('compare_price') ?? 0;

                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $minPrice,
                        'compare_price' => $maxComparePrice > $minPrice ? $maxComparePrice : null,
                        'main_image' => $product->mainImage->path ?? null
                    ];
                })
                ->toArray();

            return $products;

        } catch (\Exception $e) {
            \Log::error('Error getting recommended products: ' . $e->getMessage());
            return [];
        }
    }
}
