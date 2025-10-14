<?php
/**
 * Simple test script to check customer API endpoint
 */

// Test the admin customer API endpoint directly
$url = 'http://localhost:8000/admin/customers';
$headers = [
    'Accept: application/json',
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

echo "Testing Customer API Endpoint\n";
echo "=============================\n";
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
        print_r($data);
    } else {
        echo "\nFailed to decode JSON response\n";
    }
}