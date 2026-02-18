<?php
$code = 'summer';
$offer = \App\Models\Offer::where('code', $code)->first();
if ($offer) {
    echo "Offer Found: " . $offer->name . "\n";
    echo "Status: " . ($offer->status ? 'Active' : 'Inactive') . "\n";
    echo "Type: " . $offer->offer_type . "\n";
    echo "Value: " . $offer->discount_value . "\n";
} else {
    echo "Offer 'summer' not found\n";
    // List some active offers
    $offers = \App\Models\Offer::where('status', true)->get();
    echo "Active Offers:\n";
    foreach ($offers as $o) {
        echo "- " . $o->code . " (" . $o->name . ")\n";
    }
}
