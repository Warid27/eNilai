<?php
require '../api.php';

$client = new API($baseURL, [
    'Authorization' => 'Bearer YOUR_TOKEN'
]);

try {
    $response = $client->put('/controller.php/26', [
        'username' => 'TEST_UPDATED2',
        'password' => 'TEZZZZZZ',
        'nis' => 5555,
        'id_role' => 6
    ], [
        'timeout' => 10
    ]);
    echo "Updated User ID: " . $response['data']['id'] . "\n";
    echo "Status: " . $response['status'] . " " . $response['statusText'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
