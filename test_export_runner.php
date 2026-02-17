<?php

function fetchUrl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    curl_close($ch);
    return ['status' => $httpCode, 'header' => $header, 'body' => $body];
}

echo "Testing CSV Export...\n";
$responseCsv = fetchUrl('http://127.0.0.1:8000/admin/orders/export?export=csv');
echo "Status: " . $responseCsv['status'] . "\n";
// Extract Content-Type and Content-Disposition
if (preg_match('/Content-Type: (.*)/i', $responseCsv['header'], $matches)) {
    echo "Content-Type: " . trim($matches[1]) . "\n";
}
if (preg_match('/Content-Disposition: (.*)/i', $responseCsv['header'], $matches)) {
    echo "Content-Disposition: " . trim($matches[1]) . "\n";
}

echo "\n--------------------------------------------------\n\n";

echo "Testing Excel Export...\n";
$responseExcel = fetchUrl('http://127.0.0.1:8000/admin/orders/export?export=excel');
echo "Status: " . $responseExcel['status'] . "\n";
// Extract Content-Type
if (preg_match('/Content-Type: (.*)/i', $responseExcel['header'], $matches)) {
    echo "Content-Type: " . trim($matches[1]) . "\n";
}
if (preg_match('/Content-Disposition: (.*)/i', $responseExcel['header'], $matches)) {
    echo "Content-Disposition: " . trim($matches[1]) . "\n";
}

