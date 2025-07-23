<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $client = new API('http://localhost/project/eNilai/controller', [
        'Authorization' => 'Bearer YOUR_TOKEN'
    ]);

    try {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $nis = $_POST['nis'] ?? null;
        $id_role = $_POST['id_role'];

        $response = $client->post('/users.php', [
            'username' => $username,
            'password' => $password,
            'nis' => $nis,
            'id_role' => $id_role
        ], [
            'timeout' => 10
        ]);

        $data = $response['data'] ?? [];
        // Access the nested data structure
        if (isset($response['data']['data']) && is_array($response['data']['data'])) {
            $userData = $response['data']['data'];

            echo "id: " . $userData['id'] . "<br>";
            echo "username: " . $userData['username'] . "<br>";
            echo "nis: " . $userData['nis'] . "<br>";
            echo "id_role: " . $userData['id_role'] . "<br>";
        } else {
            echo "Unexpected response structure<br>";
            echo "<pre>" . print_r($response, true) . "</pre>";
        }
        echo "Status: " . $response['status'] . " " . $response['statusText'] . "\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}


// Point to local controller endpoint
$client = new API('http://localhost/project/eNilai/controller');

try {
    $response = $client->get('/users.php', [
        'params' => ['_limit' => 10, 'show' => 'siswa'], // Query params
        'headers' => ['X-Custom-Header' => 'value']
    ]);

    $data = $response['data'] ?? [];
    if (empty($data)) {
        echo "No data returned\n";
    } else {
        $allowedKeys = ['username', 'nis', 'subjects', 'value'];
        $customHeaders = ['username' => 'Nama', 'nis' => 'NIS', 'subjects' => 'Mapel', 'value' => 'Nilai'];
        $filteredUsers = array_map(function ($item) use ($allowedKeys) {
            return array_filter($item, function ($key) use ($allowedKeys) {
                return in_array($key, $allowedKeys);
            }, ARRAY_FILTER_USE_KEY);
        }, $data);

?>
        <h2>Nilai Siswa</h2>
<?php
        renderTable($filteredUsers, $customHeaders);
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>


<?php
$roles = [
    1 => 'Admin',
    2 => 'Guru Bahasa Indonesia',
    3 => 'Guru Bahasa Inggris',
    4 => 'Guru Matematika',
    5 => 'Wali kelas',
    6 => 'Siswa'
];
?>

<h1>INPUT DATA:</h1>
<form action="" method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br>

    <label>Password:</label>
    <input type="password" name="password" required><br>

    <label>NIS (optional):</label>
    <input type="text" name="nis"><br>

    <label>Role:</label>
    <select name="id_role" required>
        <?php foreach ($roles as $key => $value): ?>
            <option value="<?= $key ?>"><?= htmlspecialchars($value) ?></option>
        <?php endforeach; ?>
    </select><br>


    <button type="submit">Tambah Pengguna</button>
</form>