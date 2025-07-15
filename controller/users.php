<?php
include dirname(__FILE__) . "/../service/scores.php";

$userService = new Scores($conn);

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $username = $_POST['username'];
        $password = $_POST['password'];
        $nis      = $_POST['nis'] === '' ? NULL : $_POST['nis'] ?? NULL;
        $id_role  = $_POST['id_role'] ?? 6;

        if ($userService->createScore($username, $password, $nis, $id_role)) {
            header("Location:?page=login&message=" . urlencode("Score created"));
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
            if ($userService->updateScore($id, $username, $password, $nis, $id_role)) {
                echo "✅ Score updated.";
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

        if ($id && $userService->deleteScore($id)) {
            echo "✅ Score deleted.";
        } else {
            echo "❌ Failed to delete user.";
        }
        break;

    case 'GET':
        if (isset($_GET['id'])) {
            $user = $userService->getScore($_GET['id']);
            echo json_encode($user);
        } else {
            $params = NULL;
            if (isset($_GET['params'])) $params = $_GET['params'];
            $users = $userService->getAllScores($params);
        }
        break;

    default:
        echo "❌ Unsupported request method.";
}
