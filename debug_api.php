<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$request = Illuminate\Http\Request::create('/admin/api/offers', 'GET');
$response = $app->handle($request);
echo $response->getContent();


