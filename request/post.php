<?php
require '../api.php';

$client = new API('http://localhost/project/belajar_cURL/api', [
    'Authorization' => 'Bearer YOUR_TOKEN'
]);

try {
    $response = $client->post('/controller.php', [
        'username' => 'TEST',
        'password' => 'TEST',
        'nis' => 5555,
        'id_role' => 6
    ], [
        'timeout' => 10
    ]);
    echo "Created User ID: " . $response['data']['id'] . "\n";
    echo "Status: " . $response['status'] . " " . $response['statusText'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
