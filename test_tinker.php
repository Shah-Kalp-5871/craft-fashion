<?php
$controller = app(App\Http\Controllers\Api\Admin\OfferController::class);
$request = Illuminate\Http\Request::create('/admin/api/offers', 'GET');
$response = $controller->index($request);
$data = $response->getData();

echo "Response Success: " . ($data->success ? 'true' : 'false') . "\n";
echo "Data has 'data': " . (isset($data->data->data) ? 'true' : 'false') . "\n";
echo "Data has 'meta': " . (isset($data->data->meta) ? 'true' : 'false') . "\n";

if ($data->success && isset($data->data->data)) {
    echo "Items Count: " . count($data->data->data) . "\n";
    if (count($data->data->data) > 0) {
        echo "First Item Name: " . $data->data->data[0]->name . "\n";
    }
} else {
    print_r($data);
}

