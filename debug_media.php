<?php
try {
    $controller = new \App\Http\Controllers\Admin\MediaController();
    // Verify dependencies
    if (!class_exists(\Intervention\Image\ImageManager::class)) {
        echo "ImageManager class not found!\n";
    }
    
    $request = \Illuminate\Http\Request::create('/admin/media/data', 'GET');
    $response = $controller->getData($request);
    echo "Response Status: " . $response->getStatusCode() . "\n";
    echo "Response Content: " . $response->getContent() . "\n";
} catch (\Throwable $e) {
    echo "CAUGHT EXCEPTION:\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    // echo $e->getTraceAsString();
}
