<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__FILE__) . "/../db/connect.php";

class UserController
{
    private $conn;
    private $baseUrl;

    public function __construct($conn, $baseUrl)
    {
        $this->conn = $conn;
        $this->baseUrl = $BASE_URL;
    }

    private function sendResponse(int $status, array $data): void
    {
        ob_end_clean();
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        exit;
    }

    private function validateInput(array $input, array $requiredFields): ?array
    {
        foreach ($requiredFields as $field) {
            if (!isset($input[$field])) {
                return ['error' => 'Missing required field: ' . $field];
            }
        }
        return null;
    }

    public function handleRequest(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $pathParts = explode('/', trim($path, '/'));
        $userId = isset($pathParts[array_search('users.php', $pathParts) + 1])
            ? (int)$pathParts[array_search('users.php', $pathParts) + 1]
            : null;
        $controllerPath = $this->baseUrl . '/controller/users.php';

        if ($method === 'GET' && strpos($path, $controllerPath) !== false && !$userId) {
            $this->listUsers();
        } elseif ($method === 'POST' && strpos($path, $controllerPath) !== false && !$userId) {
            $this->createUser();
        } elseif ($method === 'PUT' && strpos($path, $controllerPath) !== false && $userId) {
            $this->updateUser($userId);
        } elseif ($method === 'DELETE' && strpos($path, $controllerPath) !== false && $userId) {
            $this->deleteUser($userId);
        } else {
            error_log("No endpoint matched: Path = $path, Method = $method, User ID = " . ($userId ?: 'null'));
            $this->sendResponse(404, ['error' => "No endpoint matched: Path = $path, Method = $method, User ID = " . ($userId ?: 'null')]);
        }
    }

    private function listUsers(): void
    {
        $limit = isset($_GET['_limit']) ? (int)$_GET['_limit'] : 10;
        $show = isset($_GET['show']) ? $_GET['show'] : "all";

        $siswa = "SELECT users.id AS user_id, users.username, users.nis, users.id_role, scores.subjects, scores.value 
          FROM users 
          LEFT JOIN scores ON users.id = scores.id_user 
          WHERE users.id_role = 6 
          LIMIT ?";
        $query = "SELECT * FROM `users` LIMIT ?";



        $stmt = mysqli_prepare($this->conn, $show === "siswa" ? $siswa : $query);
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            error_log("GET query failed: " . mysqli_error($this->conn));
            $this->sendResponse(500, ['error' => 'Database query failed']);
        }

        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        $this->sendResponse(200, $data);
    }

    private function createUser(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("POST JSON decode failed: " . json_last_error_msg());
            $this->sendResponse(400, ['error' => 'Invalid JSON input']);
        }

        $requiredFields = ['username', 'password', 'nis', 'id_role'];
        if ($error = $this->validateInput($input, $requiredFields)) {
            $this->sendResponse(400, $error);
        }

        $username = mysqli_real_escape_string($this->conn, $input['username']);
        $password = password_hash($input['password'], PASSWORD_BCRYPT);
        $nis = (int)$input['nis'];
        $id_role = (int)$input['id_role'];

        $checkUsername = mysqli_query($this->conn, "SELECT * FROM `users` WHERE username = '$username'");
        if (mysqli_num_rows($checkUsername) > 0) {
            $this->sendResponse(409, ['error' => 'Username already exists']);
        }

        $query = "INSERT INTO `users` (username, password, nis, id_role) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssii', $username, $password, $nis, $id_role);

        if (mysqli_stmt_execute($stmt)) {
            $userId = mysqli_insert_id($this->conn);
            $this->sendResponse(201, [
                'status' => 201,
                'statusText' => 'Created',
                'data' => [
                    'id' => $userId,
                    'username' => $username,
                    'nis' => $nis,
                    'id_role' => $id_role
                ]
            ]);
        } else {
            error_log("POST query failed: " . mysqli_error($this->conn));
            $this->sendResponse(500, ['error' => 'Failed to create user']);
        }
    }

    private function updateUser(int $userId): void
    {
        error_log("PUT request matched: ID = $userId");
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("PUT JSON decode failed: " . json_last_error_msg());
            $this->sendResponse(400, ['error' => 'Invalid JSON input']);
        }

        if (empty($input)) {
            $this->sendResponse(400, ['error' => 'At least one field (username, password, nis, or id_role) must be provided']);
        }

        $checkUser = mysqli_query($this->conn, "SELECT * FROM `users` WHERE id = $userId");
        if (mysqli_num_rows($checkUser) === 0) {
            $this->sendResponse(404, ['error' => 'User not found']);
        }

        $updateFields = [];
        $responseData = ['id' => $userId];

        if (isset($input['username'])) {
            $username = mysqli_real_escape_string($this->conn, $input['username']);
            $checkUsername = mysqli_query($this->conn, "SELECT * FROM `users` WHERE username = '$username' AND id != $userId");
            if (mysqli_num_rows($checkUsername) > 0) {
                $this->sendResponse(409, ['error' => 'Username already exists']);
            }
            $updateFields[] = "username = '$username'";
            $responseData['username'] = $username;
        }

        if (isset($input['password'])) {
            $password = password_hash($input['password'], PASSWORD_BCRYPT);
            $updateFields[] = "password = '$password'";
            $responseData['password'] = $password;
        }

        if (isset($input['nis'])) {
            $nis = (int)$input['nis'];
            $updateFields[] = "nis = $nis";
            $responseData['nis'] = $nis;
        }

        if (isset($input['id_role'])) {
            $id_role = (int)$input['id_role'];
            $updateFields[] = "id_role = $id_role";
            $responseData['id_role'] = $id_role;
        }

        $query = "UPDATE `users` SET " . implode(', ', $updateFields) . " WHERE id = $userId";
        if (mysqli_query($this->conn, $query)) {
            $result = mysqli_query($this->conn, "SELECT username, nis, id_role FROM `users` WHERE id = $userId");
            if ($result) {
                $updatedUser = mysqli_fetch_assoc($result);
                $this->sendResponse(200, [
                    'status' => 200,
                    'statusText' => 'OK',
                    'data' => [
                        'id' => $userId,
                        'username' => $updatedUser['username'],
                        'nis' => (int)$updatedUser['nis'],
                        'id_role' => (int)$updatedUser['id_role']
                    ]
                ]);
            } else {
                error_log("PUT select query failed: " . mysqli_error($this->conn));
                $this->sendResponse(500, ['error' => 'Failed to fetch updated user']);
            }
        } else {
            error_log("PUT update query failed: " . mysqli_error($this->conn));
            $this->sendResponse(500, ['error' => 'Failed to update user']);
        }
    }

    private function deleteUser(int $userId): void
    {
        error_log("DELETE request matched: ID = $userId");
        $checkUser = mysqli_query($this->conn, "SELECT * FROM `users` WHERE id = $userId");
        if (mysqli_num_rows($checkUser) === 0) {
            $this->sendResponse(404, ['error' => 'User not found']);
        }

        $query = "UPDATE `users` SET `status` = 1 WHERE id = $userId";
        if (mysqli_query($this->conn, $query)) {
            $result = mysqli_query($this->conn, "SELECT username, nis, id_role FROM `users` WHERE id = $userId");
            if ($result) {
                $updatedUser = mysqli_fetch_assoc($result);
                $this->sendResponse(200, [
                    'status' => 200,
                    'statusText' => 'OK',
                    'data' => [
                        'id' => $userId,
                        'username' => $updatedUser['username'],
                        'nis' => (int)$updatedUser['nis'],
                        'id_role' => (int)$updatedUser['id_role']
                    ]
                ]);
            } else {
                error_log("DELETE select query failed: " . mysqli_error($this->conn));
                $this->sendResponse(500, ['error' => 'Failed to fetch updated user']);
            }
        } else {
            error_log("DELETE update query failed: " . mysqli_error($this->conn));
            $this->sendResponse(500, ['error' => 'Failed to update user']);
        }
    }
}

$controller = new UserController($conn, $BASE_URL);
$controller->handleRequest();
