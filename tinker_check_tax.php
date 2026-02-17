<?php

use App\Models\Product;
use App\Models\TaxClass;

$products = Product::where('status', 'active')->take(5)->with('taxClass.rates')->get();

foreach ($products as $product) {
    echo "Product: {$product->name}\n";
    echo "  Tax Class ID: " . ($product->tax_class_id ?? 'NULL') . "\n";
    if ($product->taxClass) {
        echo "  Tax Class Name: {$product->taxClass->name}\n";
        echo "  Total Rate (Model Accessor/Attr): " . ($product->taxClass->total_rate ?? 'N/A') . "\n";
        echo "  Rates Count: " . $product->taxClass->rates->count() . "\n";
        foreach ($product->taxClass->rates as $rate) {
            echo "    - {$rate->name}: {$rate->rate}%\n";
        }
    } else {
        echo "  No Tax Class Assigned.\n";
    }
    echo "\n";
}

$taxes = TaxClass::with('rates')->get();
echo "Available Tax Classes:\n";
foreach ($taxes as $tax) {
    echo "- {$tax->name} (Total Rate: " . ($tax->total_rate ?? 'N/A') . ")\n";
}
