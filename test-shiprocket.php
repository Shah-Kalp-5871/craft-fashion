<?php

use App\Services\Customer\ShiprocketService;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$service = app(ShiprocketService::class);

echo "Testing Shiprocket API...\n\n";

// Test with a valid Delhi pincode
$pincode = '110001';
echo "Testing pincode: $pincode\n";

try {
    $result = $service->checkServiceability($pincode, 1.0, [
        'length' => 10,
        'width' => 10,
        'height' => 10
    ]);
    
    echo "\nResult:\n";
    print_r($result);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
