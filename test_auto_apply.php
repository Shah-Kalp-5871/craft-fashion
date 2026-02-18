<?php
use App\Helpers\CartHelper;
use App\Models\Offer;
use App\Models\ProductVariant;

$cartHelper = app(CartHelper::class);

// 1. Setup Auto-Apply Offer
$offer = Offer::where('code', 'summer')->first();
if (!$offer) {
    echo "Offer 'summer' not found\n";
    exit;
}

$offer->update([
    'is_auto_apply' => true,
    'status' => true,
    'min_cart_amount' => 500,
    'starts_at' => now()->subDay(),
    'ends_at' => now()->addDay()
]);

echo "Offer 'summer' setup: is_auto_apply=" . ($offer->is_auto_apply ? 'true' : 'false') . ", min_amount=" . $offer->min_cart_amount . "\n";

// 2. Clear current local cart (simulated)
$cartHelper->clearLocalCart();

// 3. Add item below min_amount
$variant = ProductVariant::where('stock_quantity', '>', 0)->first();
echo "Adding variant: " . $variant->id . " Price: " . $variant->price . "\n";

$result = $cartHelper->addToCart($variant->id, 1);
$cart = $result['cart'];
echo "Cart Subtotal: " . $cart['subtotal'] . ", Discount: " . $cart['discount_total'] . "\n";

if ($cart['subtotal'] < 500 && $cart['discount_total'] == 0) {
    echo "PASS: No auto-apply for subtotal < 500\n";
} else {
    echo "FAIL: Unexpected discount state (Below 500)\n";
}

// 4. Add more items (by adding same variant again) to exceed min_amount
$qtyNeeded = ceil(600 / $variant->price);
echo "Adding more quantity: " . ($qtyNeeded - 1) . "\n";
$result = $cartHelper->addToCart($variant->id, $qtyNeeded - 1);
$cart = $result['cart'];
echo "Cart Subtotal: " . $cart['subtotal'] . ", Discount: " . $cart['discount_total'] . "\n";

if ($cart['subtotal'] >= 500 && $cart['discount_total'] > 0) {
    echo "PASS: Auto-apply applied for subtotal >= 500\n";
} else {
    echo "FAIL: Auto-apply NOT applied for subtotal >= 500\n";
}

