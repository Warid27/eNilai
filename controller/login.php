<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json; charset=utf-8');

require_once dirname(__FILE__) . "/../db/connect.php";

class LoginController
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
        $controllerPath = $this->baseUrl . '/controller/login.php';

        if ($method === 'POST' && strpos($path, $controllerPath) !== false) {
            $this->loginUser();
        } else {
            error_log("No endpoint matched: Path = $path, Method = $method");
            $this->sendResponse(404, ['error' => "No endpoint matched: Path = $controllerPath, Method = $method"]);
        }
    }

    private function loginUser(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log("POST JSON decode failed: " . json_last_error_msg());
            $this->sendResponse(400, ['error' => 'Invalid JSON input']);
        }

        $requiredFields = ['username', 'password'];
        if ($error = $this->validateInput($input, $requiredFields)) {
            $this->sendResponse(400, $error);
        }

        $username = mysqli_real_escape_string($this->conn, $input['username']);
        $password = $input['password'];

        $query = "SELECT id, username, password, nis, id_role FROM `users` WHERE username = ? && id_role != 6";
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (!$result) {
            error_log("Login query failed: " . mysqli_error($this->conn));
            $this->sendResponse(500, ['error' => 'Database query failed']);
        }

        if (mysqli_num_rows($result) === 0) {
            $this->sendResponse(401, ['error' => 'Invalid username or password']);
        }

        $user = mysqli_fetch_assoc($result);
        if (!password_verify($password, $user['password'])) {
            $this->sendResponse(401, ['error' => 'Invalid username or password']);
        }

        $this->sendResponse(200, [
            'status' => 200,
            'statusText' => 'OK',
            'data' => [
                'id' => (int)$user['id'],
                'username' => $user['username'],
                'nis' => (int)$user['nis'],
                'id_role' => (int)$user['id_role']
            ]
        ]);
    }
}

$controller = new LoginController($conn, $BASE_URL);
$controller->handleRequest();
