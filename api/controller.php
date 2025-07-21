<?php
ob_start();
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
error_reporting(E_ALL);

require "../connect.php";
header('Content-Type: application/json; charset=utf-8');

// Define BASE_URL based on the project structure
$BASE_URL = '/project/belajar_cURL'; // Adjust if your base path differs

// Get request path and method
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Log path for debugging
error_log("Request Path: $path, Method: $method");

// Extract ID from path for PUT requests
$path_parts = explode('/', trim($path, '/'));
$user_id = isset($path_parts[array_search('controller.php', $path_parts) + 1]) ? (int)$path_parts[array_search('controller.php', $path_parts) + 1] : null;

// Normalize path for comparison
$controller_path = "$BASE_URL/api/controller.php";

// GET: List users
if ($method === 'GET' && strpos($path, $controller_path) !== false && !$user_id) {
    $limit = isset($_GET['_limit']) ? (int)$_GET['_limit'] : 10;
    $result = mysqli_query($conn, "SELECT * FROM `users` LIMIT $limit");
    
    if (!$result) {
        error_log("GET query failed: " . mysqli_error($conn));
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['error' => 'Database query failed']);
        exit;
    }

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    ob_end_clean();
    http_response_code(200);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

// POST: Create user
if ($method === 'POST' && strpos($path, $controller_path) !== false && !$user_id) {
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("POST JSON decode failed: " . json_last_error_msg());
        ob_end_clean();
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        exit;
    }

    if (!isset($input['username']) || !isset($input['password']) || !isset($input['nis']) || !isset($input['id_role'])) {
        ob_end_clean();
        http_response_code(400);
        echo json_encode(['error' => 'Username, password, nis, and id_role are required']);
        exit;
    }

    $username = mysqli_real_escape_string($conn, $input['username']);
    $password = password_hash($input['password'], PASSWORD_BCRYPT);
    $nis = (int)$input['nis'];
    $id_role = (int)$input['id_role'];

    $checkUsername = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$username'");
    if (mysqli_num_rows($checkUsername) > 0) {
        ob_end_clean();
        http_response_code(409);
        echo json_encode(['error' => 'Username already exists']);
        exit;
    }

    $query = "INSERT INTO `users` (username, password, nis, id_role) VALUES ('$username', '$password', $nis, $id_role)";
    if (mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn);
        ob_end_clean();
        http_response_code(201);
        echo json_encode([
            'status' => 201,
            'statusText' => 'Created',
            'data' => [
                'id' => $user_id,
                'username' => $username,
                'nis' => $nis,
                'id_role' => $id_role
            ]
        ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    } else {
        error_log("POST query failed: " . mysqli_error($conn));
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create user']);
    }
    exit;
}

// PUT: Update user
if ($method === 'PUT' && strpos($path, $controller_path) !== false && $user_id) {
    error_log("PUT request matched: ID = $user_id");
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("PUT JSON decode failed: " . json_last_error_msg());
        ob_end_clean();
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON input']);
        exit;
    }

    if (empty($input)) {
        ob_end_clean();
        http_response_code(400);
        echo json_encode(['error' => 'At least one field (username, password, nis, or id_role) must be provided']);
        exit;
    }

    $checkUser = mysqli_query($conn, "SELECT * FROM `users` WHERE id = $user_id");
    if (mysqli_num_rows($checkUser) === 0) {
        ob_end_clean();
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $update_fields = [];
    $response_data = ['id' => $user_id];

    if (isset($input['username'])) {
        $username = mysqli_real_escape_string($conn, $input['username']);
        $checkUsername = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$username' AND id != $user_id");
        if (mysqli_num_rows($checkUsername) > 0) {
            ob_end_clean();
            http_response_code(409);
            echo json_encode(['error' => 'Username already exists']);
            exit;
        }
        $update_fields[] = "username = '$username'";
        $response_data['username'] = $username;
    }

    if (isset($input['password'])) {
        $password = mysqli_real_escape_string($conn, $input['password']);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $update_fields[] = "password = '$hashed_password'";
        $response_data['password'] = $hashed_password;
    }

    if (isset($input['nis'])) {
        $nis = (int)$input['nis'];
        $update_fields[] = "nis = $nis";
        $response_data['nis'] = $nis;
    }

    if (isset($input['id_role'])) {
        $id_role = (int)$input['id_role'];
        $update_fields[] = "id_role = $id_role";
        $response_data['id_role'] = $id_role;
    }

    $query = "UPDATE `users` SET " . implode(', ', $update_fields) . " WHERE id = $user_id";
    if (mysqli_query($conn, $query)) {
        $result = mysqli_query($conn, "SELECT username, nis, id_role FROM `users` WHERE id = $user_id");
        if ($result) {
            $updated_user = mysqli_fetch_assoc($result);
            ob_end_clean();
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'statusText' => 'OK',
                'data' => [
                    'id' => $user_id,
                    'username' => $updated_user['username'],
                    'nis' => (int)$updated_user['nis'],
                    'id_role' => (int)$updated_user['id_role']
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        } else {
            error_log("PUT select query failed: " . mysqli_error($conn));
            ob_end_clean();
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch updated user']);
        }
    } else {
        error_log("PUT update query failed: " . mysqli_error($conn));
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update user']);
    }
    exit;
}

// DELETE: Delete user
if ($method === 'DELETE' && strpos($path, $controller_path) !== false && $user_id) {
    error_log("DELETE request matched: ID = $user_id");

    $checkUser = mysqli_query($conn, "SELECT * FROM `users` WHERE id = $user_id");
    if (mysqli_num_rows($checkUser) === 0) {
        ob_end_clean();
        http_response_code(404);
        echo json_encode(['error' => 'User not found']);
        exit;
    }

    $query = "UPDATE `users` SET `status` = 1 WHERE id = $user_id";
    if (mysqli_query($conn, $query)) {
        $result = mysqli_query($conn, "SELECT username, nis, id_role FROM `users` WHERE id = $user_id");
        if ($result) {
            $updated_user = mysqli_fetch_assoc($result);
            ob_end_clean();
            http_response_code(200);
            echo json_encode([
                'status' => 200,
                'statusText' => 'OK',
                'data' => [
                    'id' => $user_id,
                    'username' => $updated_user['username'],
                    'nis' => (int)$updated_user['nis'],
                    'id_role' => (int)$updated_user['id_role']
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        } else {
            error_log("PUT select query failed: " . mysqli_error($conn));
            ob_end_clean();
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch updated user']);
        }
    } else {
        error_log("PUT update query failed: " . mysqli_error($conn));
        ob_end_clean();
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update user']);
    }
    exit;
}

// Log if no conditions matched
error_log("No endpoint matched: Path = $path, Method = $method, User ID = " . ($user_id ?: 'null'));
ob_end_clean();
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
?>