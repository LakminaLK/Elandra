<?php
/**
 * Test the new Customer API endpoint
 */

// Test the new public customer API endpoint
$url = 'http://localhost:8000/api/public/customers';
$headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'X-Requested-With: XMLHttpRequest'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Testing NEW Customer API Endpoint\n";
echo "=================================\n";
echo "URL: $url\n";
echo "HTTP Code: $httpCode\n";

if ($error) {
    echo "CURL Error: $error\n";
} else {
    echo "Response:\n";
    echo $response . "\n";
    
    // Try to decode JSON
    $data = json_decode($response, true);
    if ($data) {
        echo "\nJSON Decoded Successfully:\n";
        if (isset($data['success'])) {
            echo "Success: " . ($data['success'] ? 'true' : 'false') . "\n";
        }
        if (isset($data['message'])) {
            echo "Message: " . $data['message'] . "\n";
        }
        if (isset($data['data']) && is_array($data['data'])) {
            echo "Customers count: " . count($data['data']) . "\n";
        }
    } else {
        echo "\nFailed to decode JSON response\n";
    }
}

// Also test the test endpoint
echo "\n" . str_repeat("=", 50) . "\n";
echo "Testing API Test Endpoint\n";

$testUrl = 'http://localhost:8000/api/public/customers/test/api';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $testUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$testResponse = curl_exec($ch);
$testHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$testError = curl_error($ch);
curl_close($ch);

echo "Test URL: $testUrl\n";
echo "HTTP Code: $testHttpCode\n";

if ($testError) {
    echo "CURL Error: $testError\n";
} else {
    echo "Test Response:\n";
    echo $testResponse . "\n";
}