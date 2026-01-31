<?php

// app/Services/Customer/CartService.php
namespace App\Services\Customer;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $cart;
    protected $customerId;
    protected $sessionId;

    public function __construct()
    {
        $this->customerId = Auth::guard('customer')->id();
        $this->sessionId = Session::getId();
        $this->loadCart();
    }

    private function loadCart()
    {
        if ($this->customerId) {
            $this->cart = Cart::firstOrCreate([
                'customer_id' => $this->customerId,
                'status' => 'active'
            ], [
                'session_id' => $this->sessionId,
                'subtotal' => 0,
                'tax_total' => 0,
                'shipping_total' => 0,
                'discount_total' => 0,
                'grand_total' => 0
            ]);
        } else {
            $this->cart = Cart::firstOrCreate([
                'session_id' => $this->sessionId,
                'customer_id' => null,
                'status' => 'active'
            ], [
                'subtotal' => 0,
                'tax_total' => 0,
                'shipping_total' => 0,
                'discount_total' => 0,
                'grand_total' => 0
            ]);
        }
    }

    public function addItem($variantId, $quantity = 1, $attributes = [])
    {
        $variant = ProductVariant::with('product')->findOrFail($variantId);

        // Check stock
        if ($variant->stock_quantity < $quantity + $this->getReservedQuantity($variantId)) {
            throw new \Exception('Insufficient stock available');
        }

        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $this->cart->id,
                'product_variant_id' => $variantId
            ],
            [
                'quantity' => \DB::raw("quantity + {$quantity}"),
                'unit_price' => $variant->price,
                'total' => \DB::raw("unit_price * (quantity + {$quantity})"),
                'attributes' => json_encode($attributes)
            ]
        );

        $this->updateStockReservation($variantId, $quantity, 'add');
        $this->recalculateCart();

        return $cartItem;
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::where('cart_id', $this->cart->id)
            ->findOrFail($cartItemId);

        $oldQuantity = $cartItem->quantity;
        $difference = $quantity - $oldQuantity;

        if ($difference > 0) {
            $this->updateStockReservation($cartItem->product_variant_id, $difference, 'add');
        } else {
            $this->updateStockReservation($cartItem->product_variant_id, abs($difference), 'remove');
        }

        $cartItem->update([
            'quantity' => $quantity,
            'total' => $cartItem->unit_price * $quantity
        ]);

        $this->recalculateCart();

        return $cartItem;
    }

    public function removeItem($cartItemId)
    {
        $cartItem = CartItem::where('cart_id', $this->cart->id)
            ->findOrFail($cartItemId);

        $this->updateStockReservation($cartItem->product_variant_id, $cartItem->quantity, 'remove');

        $cartItem->delete();
        $this->recalculateCart();

        return true;
    }

    public function getCart()
    {
        return $this->cart->load(['items.variant.product', 'items.variant.variantImages.media']);
    }

    public function getCartCount()
    {
        return $this->cart->items()->sum('quantity');
    }

    public function getCartTotal()
    {
        return $this->cart->grand_total;
    }

    public function clearCart()
    {
        // Release all reserved stock
        foreach ($this->cart->items as $item) {
            $this->updateStockReservation($item->product_variant_id, $item->quantity, 'remove');
        }

        $this->cart->items()->delete();
        $this->recalculateCart();

        return true;
    }

    public function applyOffer($offerCode)
    {
        // Implement offer application logic
        // This will need to calculate discounts based on offer rules
    }

    private function updateStockReservation($variantId, $quantity, $action)
    {
        $variant = ProductVariant::find($variantId);

        if ($action === 'add') {
            $variant->increment('reserved_quantity', $quantity);
        } else {
            $variant->decrement('reserved_quantity', $quantity);
        }

        // Log to stock history
        \App\Models\StockHistory::create([
            'product_variant_id' => $variantId,
            'change_type' => $action === 'add' ? 'reservation' : 'release',
            'quantity' => $quantity,
            'old_quantity' => $variant->stock_quantity,
            'new_quantity' => $variant->stock_quantity - ($action === 'add' ? $quantity : -$quantity),
            'source_type' => 'cart',
            'source_id' => $this->cart->id,
            'reason' => 'Cart ' . ($action === 'add' ? 'addition' : 'removal')
        ]);
    }

    private function getReservedQuantity($variantId)
    {
        return CartItem::join('carts', 'cart_items.cart_id', '=', 'carts.id')
            ->where('carts.status', 'active')
            ->where('cart_items.product_variant_id', $variantId)
            ->where('carts.id', '!=', $this->cart->id)
            ->sum('cart_items.quantity');
    }

    private function recalculateCart()
    {
        $this->cart->load('items.variant.product.taxClass.rates');

        $subtotal = 0;
        $taxTotal = 0;

        foreach ($this->cart->items as $item) {
            $subtotal += $item->total;
            
            $product = $item->productVariant->product;
            $taxRate = 0;
            
            if ($product && $product->taxClass) {
                $taxRate = $product->taxClass->total_rate;
            }
            
            $taxTotal += ($item->total * ($taxRate / 100));
        }

        $shippingTotal = $this->calculateShipping($subtotal);
        $discountTotal = $this->calculateDiscounts($subtotal);

        $grandTotal = $subtotal + $taxTotal + $shippingTotal - $discountTotal;

        $this->cart->update([
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'shipping_total' => $shippingTotal,
            'discount_total' => $discountTotal,
            'grand_total' => $grandTotal
        ]);
    }

    private function calculateTax($subtotal)
    {
        // This method is now integrated into recalculateCart for per-item calculation
        return 0;
    }

    private function calculateShipping($subtotal = 0)
    {
        // Implement shipping calculation (will integrate with Shiprocket)
        return 0; // Placeholder
    }

    private function calculateDiscounts($subtotal)
    {
        // Calculate applicable discounts from offers
        return 0; // Placeholder
    }

    public function mergeGuestCart($customerId)
    {
        $guestCart = Cart::where('session_id', $this->sessionId)
            ->where('customer_id', null)
            ->where('status', 'active')
            ->first();

        if ($guestCart && $guestCart->id !== $this->cart->id) {
            // Merge guest cart items into customer cart
            foreach ($guestCart->items as $item) {
                $this->addItem($item->product_variant_id, $item->quantity, json_decode($item->attributes, true));
            }

            $guestCart->update(['status' => 'converted']);
        }
    }
}
