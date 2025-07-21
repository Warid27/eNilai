<?php
require '../api.php';

// Point to local controller endpoint
$client = new API('http://localhost/project/belajar_cURL/api');

try {
    $response = $client->get('/controller.php', [
        'params' => ['_limit' => 10], // Query params
        'headers' => ['X-Custom-Header' => 'value']
    ]);

    $data = $response['data'] ?? []; 
    if (empty($data)) {
        echo "No data returned\n";
    } else {
        foreach ($data as $item) {
            echo "Username: " . ($item['username'] ?? 'No username') . "\n";
            echo "ID: " . ($item['id'] ?? 'No id') . "\n";
        }
        echo "Status: " . $response['status'] . " " . $response['statusText'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}