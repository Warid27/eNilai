<?php
require '../api.php';

$client = new API('http://localhost/project/belajar_cURL/api', [
    'Authorization' => 'Bearer YOUR_TOKEN'
]);

try {
    $response = $client->delete('/controller.php/26', [
        'timeout' => 10
    ]);
    echo "Deleted User ID: " . $response['data']['id'] . "\n";
    echo "Status: " . $response['status'] . " " . $response['statusText'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
