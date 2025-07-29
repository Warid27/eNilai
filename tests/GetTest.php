<!-- eNilai-main/tests/GetTest.php -->
<?php
require_once dirname(__FILE__) . "/../app/core/APIClient.php";

$client = new APIClient('http://localhost/eNilai-main/controller');

try {
    $response = $client->get('/users.php', [
        'params' => ['_limit' => 10],
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
?>