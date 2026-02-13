<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $view = view('emails.otp_verify', ['otp' => '123456'])->render();
    echo "View rendered successfully.\n";
} catch (\Exception $e) {
    echo "View Error: " . $e->getMessage() . "\n";
}
