<?php

use App\Services\Customer\LocalCartService;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

// Ensure we are testing as guest
Auth::guard('customer')->logout();
Session::start();

$service = new LocalCartService();
$service->clearCart();

// Find a product with tax class
$product = Product::whereHas('taxClass')->with('variants')->first();

if (!$product) {
    echo "No product with tax class found. Test cannot proceed.\n";
    exit;
}

$variant = $product->variants->first();
if (!$variant) {
    echo "Product {$product->name} has no variants.\n";
    exit;
}

echo "Testing with Product: {$product->name} (Price: {$variant->price})\n";
echo "Tax Class: {$product->taxClass->name} (Rate: {$product->taxClass->total_rate}%)\n";

try {
    $cart = $service->addItem($variant->id, 1);
    
    echo "\nCart Summary:\n";
    echo "Subtotal: {$cart['subtotal']}\n";
    echo "Tax Total: {$cart['tax_total']}\n";
    echo "Grand Total: {$cart['grand_total']}\n";
    
    $expectedTax = round($variant->price * ($product->taxClass->total_rate / 100), 2);
    echo "\nExpected Tax: {$expectedTax}\n";
    
    if (abs($cart['tax_total'] - $expectedTax) < 0.01) {
        echo "SUCCESS: Tax calculation is correct.\n";
    } else {
        echo "FAILURE: Tax calculation incorrect.\n";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
