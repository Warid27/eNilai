<?php
require_once dirname(__FILE__) . "/../db/connect.php";
include dirname(__FILE__) . "/../service/users.php";

$userService = new Users($conn);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $username = $_POST['username'];
        $password = $_POST['password'];
        $nis      = $_POST['nis'] === '' ? NULL : $_POST['nis'] ?? NULL;
        $id_role  = $_POST['id_role'] ?? 6;

        if ($userService->createUser($username, $password, $nis, $id_role)) {
            header("Location:?page=login&message=" . urlencode("User created"));
        } else {
            header("Location:?page=login&message=" . urlencode("❌ Failed to create user."));
        }
        break;

    case 'PUT':
    case 'PATCH':
        // Update
        parse_str(file_get_contents("php://input"), $_PUT);
        $id       = $_PUT['id'] ?? null;
        $username = $_PUT['username'] ?? null;
        $password = $_PUT['password'] ?? null;
        $nis      = $_PUT['nis'] ?? null;
        $id_role  = $_PUT['id_role'] ?? 2;

        if ($id && $username && $password) {
            if ($userService->updateUser($id, $username, $password, $nis, $id_role)) {
                echo "✅ User updated.";
            } else {
                echo "❌ Failed to update user.";
            }
        } else {
            echo "❌ Missing fields.";
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'] ?? null;

        if ($id && $userService->deleteUser($id)) {
            echo "✅ User deleted.";
        } else {
            echo "❌ Failed to delete user.";
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $user = $userService->getUser($_GET['id']);
            echo json_encode($user);
        } else {
            $params = NULL;
            if (isset($_GET['params'])) $params = $_GET['params'];
            $users = $userService->getAllUsers($params);
            echo json_encode($users);
        }
        break;

    default:
        echo "❌ Unsupported request method.";
}
