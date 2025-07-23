<?php
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $client = new API('http://localhost/project/eNilai/controller');

    try {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $response = $client->post('/login.php', [
            'username' => $username,
            'password' => $password,
        ], [
            'timeout' => 10
        ]);

        // Debug output (you can remove this later)
        // echo "<pre>" . print_r($response, true) . "</pre>";

        // Access the nested data structure
        if (isset($response['data']['data']) && is_array($response['data']['data'])) {
            $userData = $response['data']['data'];

            echo "Login success, id: " . $userData['id'] . "<br>";
            echo "Login success, username: " . $userData['username'] . "<br>";
            echo "Login success, nis: " . $userData['nis'] . "<br>";
            echo "Login success, id_role: " . $userData['id_role'] . "<br>";
            echo "Status: " . $response['data']['status'] . " " . $response['data']['statusText'] . "<br>";
        } else {
            echo "Unexpected response structure<br>";
            echo "<pre>" . print_r($response, true) . "</pre>";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}
?>

<form action="" method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br><br>

    <label>Password:</label>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>