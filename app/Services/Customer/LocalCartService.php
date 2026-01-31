<?php
// app/Services/Customer/LocalCartService.php
namespace App\Services\Customer;

use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LocalCartService
{
    protected $cartKey = 'guest_cart';
    protected $cartExpiry = 43200; // 30 days in minutes

    public function getCart()
    {
        // If user is logged in, get from database
        if (Auth::guard('customer')->check()) {
            return $this->getDatabaseCart();
        }

        // For guest users, get from localStorage (via cookie)
        return $this->getLocalCart();
    }

    public function addItem($variantId, $quantity = 1, $attributes = [])
    {
        try {
            // If user is logged in, add to database
            if (Auth::guard('customer')->check()) {
                $cart = $this->addToDatabaseCart($variantId, $quantity, $attributes);
            } else {
                // For guest users, add to localStorage
                $cart = $this->addToLocalCart($variantId, $quantity, $attributes);
            }

            // Update session cart count for immediate display
            Session::put('cart_count', $cart['items_count'] ?? 0);

            return $cart;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateQuantity($itemId, $quantity)
    {
        if (Auth::guard('customer')->check()) {
            $cart = $this->updateDatabaseCart($itemId, $quantity);
        } else {
            $cart = $this->updateLocalCart($itemId, $quantity);
        }

        Session::put('cart_count', $cart['items_count'] ?? 0);
        return $cart;
    }

    public function removeItem($itemId)
    {
        if (Auth::guard('customer')->check()) {
            $cart = $this->removeFromDatabaseCart($itemId);
        } else {
            $cart = $this->removeFromLocalCart($itemId);
        }

        Session::put('cart_count', $cart['items_count'] ?? 0);
        return $cart;
    }

    public function getCartCount()
    {
        $cart = $this->getCart();
        return $cart['items_count'] ?? 0;
    }

    public function getCartTotal()
    {
        $cart = $this->getCart();
        return $cart['grand_total'] ?? 0;
    }

    public function clearCart()
    {
        if (Auth::guard('customer')->check()) {
            $cart = $this->clearDatabaseCart();
        } else {
            $cart = $this->clearLocalCart();
        }

        Session::forget('cart_count');
        return $cart;
    }

    public function syncCartToDatabase($customerId)
    {
        $localCart = $this->getLocalCart();

        if (empty($localCart['items'])) {
            return true;
        }

        try {
            // Clear existing database cart first
            $this->clearDatabaseCart();

            // Add each item from localStorage to database
            foreach ($localCart['items'] as $item) {
                $this->addToDatabaseCart(
                    $item['variant_id'],
                    $item['quantity'],
                    $item['attributes'] ?? []
                );
            }

            // Clear localStorage cart
            $this->clearLocalCart();

            // Update session
            Session::put('cart_count', $this->getDatabaseCart()['items_count']);

            return true;
        } catch (\Exception $e) {
            \Log::error('Cart sync failed: ' . $e->getMessage());
            throw new \Exception('Failed to sync cart: ' . $e->getMessage());
        }
    }

    // ==================== LOCAL STORAGE METHODS ====================

    private function getLocalCart()
    {
        $cartJson = Cookie::get($this->cartKey);

        if ($cartJson) {
            $cart = json_decode($cartJson, true);
        }

        if (empty($cart) || !is_array($cart)) {
            $cart = $this->createEmptyCart();
        }

        return $cart;
    }

    private function saveLocalCart($cart)
    {
        $cart['updated_at'] = now()->timestamp;
        $cartJson = json_encode($cart);

        Cookie::queue(
            Cookie::make($this->cartKey, $cartJson, $this->cartExpiry, null, null, false, false)
        );

        return $cart;
    }

    private function addToLocalCart($variantId, $quantity, $attributes)
    {
        $cart = $this->getLocalCart();

        // Get product variant details
        $variant = ProductVariant::with(['product.taxClass.rates', 'variantImages.media'])->find($variantId);

        if (!$variant) {
            throw new \Exception('Product variant not found');
        }

        // Check stock
        if ($variant->stock_quantity < $quantity) {
            throw new \Exception('Insufficient stock available');
        }

        // Check if item already exists
        $itemExists = false;
        foreach ($cart['items'] as &$item) {
            if ($item['variant_id'] == $variantId &&
                json_encode($item['attributes']) == json_encode($attributes)) {
                $item['quantity'] += $quantity;
                $item['total'] = $item['unit_price'] * $item['quantity'];
                $itemExists = true;
                break;
            }
        }

        if (!$itemExists) {
            // Add new item
            $cart['items'][] = [
                'id' => uniqid('cart_'),
                'variant_id' => $variantId,
                'product_id' => $variant->product_id,
                'product_name' => $variant->product->name,
                'sku' => $variant->sku,
                'unit_price' => $variant->price,
                'quantity' => $quantity,
                'total' => $variant->price * $quantity,
                'attributes' => $attributes,
                'image' => optional($variant->variantImages->first()->media)->file_path ??
                          optional($variant->product->images->first()->media)->file_path ?? null,
                'stock_quantity' => $variant->stock_quantity,
                'tax_rate' => $variant->product->taxClass ? $variant->product->taxClass->total_rate : 0,
                'added_at' => now()->timestamp
            ];
        }

        // Recalculate totals
        return $this->recalculateLocalCart($cart);
    }

    private function updateLocalCart($itemId, $quantity)
    {
        $cart = $this->getLocalCart();

        foreach ($cart['items'] as $index => &$item) {
            if ($item['id'] == $itemId) {
                if ($quantity < 1) {
                    // Remove item if quantity is 0 or less
                    array_splice($cart['items'], $index, 1);
                } else {
                    // Update quantity
                    $item['quantity'] = $quantity;
                    $item['total'] = $item['unit_price'] * $quantity;
                }
                break;
            }
        }

        // Recalculate totals
        return $this->recalculateLocalCart($cart);
    }

    private function removeFromLocalCart($itemId)
    {
        $cart = $this->getLocalCart();

        $cart['items'] = array_values(array_filter($cart['items'], function($item) use ($itemId) {
            return $item['id'] != $itemId;
        }));

        // Recalculate totals
        return $this->recalculateLocalCart($cart);
    }

    private function clearLocalCart()
    {
        Cookie::queue(Cookie::forget($this->cartKey));
        return $this->createEmptyCart();
    }

    private function recalculateLocalCart($cart)
    {
        $subtotal = 0;
        $itemsCount = 0;

        foreach ($cart['items'] as $item) {
            $subtotal += $item['total'];
            $itemsCount += $item['quantity'];
            
            $itemTaxRate = $item['tax_rate'] ?? 0;
            $taxTotal += ($item['total'] * ($itemTaxRate / 100));
        }

        // Calculate shipping (free for orders above ₹999)
        $shippingTotal = $subtotal >= 999 ? 0 : 50;

        // Calculate discount (placeholder)
        $discountTotal = 0;

        $grandTotal = $subtotal + $taxTotal + $shippingTotal - $discountTotal;

        $cart['subtotal'] = round($subtotal, 2);
        $cart['tax_total'] = round($taxTotal, 2);
        $cart['shipping_total'] = round($shippingTotal, 2);
        $cart['discount_total'] = round($discountTotal, 2);
        $cart['grand_total'] = round($grandTotal, 2);
        $cart['items_count'] = $itemsCount;

        return $this->saveLocalCart($cart);
    }

    private function createEmptyCart()
    {
        $cart = [
            'session_id' => session()->getId(),
            'items' => [],
            'items_count' => 0,
            'subtotal' => 0,
            'tax_total' => 0,
            'shipping_total' => 0,
            'discount_total' => 0,
            'grand_total' => 0,
            'created_at' => now()->timestamp,
            'updated_at' => now()->timestamp
        ];

        return $this->saveLocalCart($cart);
    }

    // ==================== DATABASE CART METHODS ====================

    private function getDatabaseCart()
    {
        $cartService = app(\App\Services\Customer\CartService::class);
        $cart = $cartService->getCart();

        return [
            'id' => $cart->id,
            'session_id' => $cart->session_id,
            'items' => $cart->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'variant_id' => $item->product_variant_id,
                    'product_id' => $item->productVariant->product_id,
                    'product_name' => $item->productVariant->product->name,
                    'sku' => $item->productVariant->sku,
                    'unit_price' => (float) $item->unit_price,
                    'quantity' => $item->quantity,
                    'total' => (float) $item->total,
                    'attributes' => json_decode($item->attributes, true) ?? [],
                    'image' => optional($item->productVariant->variantImages->first()->media)->file_path ??
                              optional($item->productVariant->product->images->first()->media)->file_path ?? null,
                    'stock_quantity' => $item->productVariant->stock_quantity
                ];
            })->toArray(),
            'items_count' => (int) $cart->items->sum('quantity'),
            'subtotal' => (float) $cart->subtotal,
            'tax_total' => (float) $cart->tax_total,
            'shipping_total' => (float) $cart->shipping_total,
            'discount_total' => (float) $cart->discount_total,
            'grand_total' => (float) $cart->grand_total,
            'is_logged_in' => true
        ];
    }

    private function addToDatabaseCart($variantId, $quantity, $attributes)
    {
        $cartService = app(\App\Services\Customer\CartService::class);
        $cartItem = $cartService->addItem($variantId, $quantity, $attributes);

        return $this->getDatabaseCart();
    }

    private function updateDatabaseCart($itemId, $quantity)
    {
        $cartService = app(\App\Services\Customer\CartService::class);
        $cartItem = $cartService->updateQuantity($itemId, $quantity);

        return $this->getDatabaseCart();
    }

    private function removeFromDatabaseCart($itemId)
    {
        $cartService = app(\App\Services\Customer\CartService::class);
        $cartService->removeItem($itemId);

        return $this->getDatabaseCart();
    }

    private function clearDatabaseCart()
    {
        $cartService = app(\App\Services\Customer\CartService::class);
        $cartService->clearCart();

        return $this->getDatabaseCart();
    }
}
