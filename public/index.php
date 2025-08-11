<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Define base URL dynamically
$base_path = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/';
define('BASE_PATH', $base_path);

// Require controllers
require_once dirname(__FILE__) . '/../app/controllers/AuthController.php';
require_once dirname(__FILE__) . '/../app/controllers/ScoreController.php';
require_once dirname(__FILE__) . '/../app/controllers/GalleryController.php';
require_once dirname(__FILE__) . '/../app/controllers/UserController.php';
require_once dirname(__FILE__) . '/../app/controllers/ProfileController.php';

// Get page parameter
$page = trim($_GET['page'] ?? 'home');

// Restricted pages requiring login
$restricted_pages = ['dashboard', 'nilai', 'gallery', 'user', 'nilai_create', 'user_create'];

// Redirect to login if accessing restricted page without login
if (in_array($page, $restricted_pages) && !isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu!';
    header('Location: ?page=login');
    exit;
}

switch ($page) {
    case 'home':
        require_once dirname(__FILE__) . '/../app/views/HomeView.php';
        break;
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'gallery':
        $controller = new GalleryController();
        $controller->index();
        break;
    case 'dashboard':
        require_once dirname(__FILE__) . '/../app/views/DashboardView.php';
        break;
    case 'user':
    case 'users':
        $controller = new UserController();
        if (isset($_GET['edit'])) {
            $controller->edit($_GET['edit']);
        } elseif (isset($_GET['delete'])) {
            $controller->delete($_GET['delete']);
        } else {
            $controller->index();
        }
        break;
    case 'profile':
        $controller = new ProfileController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
                $controller->updateProfile();
            } elseif (isset($_POST['action']) && $_POST['action'] === 'change_password') {
                $controller->changePassword();
            }
        } else {
            $controller->index();
        }
        break;
    case 'nilai':
        $controller = new ScoreController();
        if (isset($_GET['edit'])) {
            $controller->edit($_GET['edit']);
        } elseif (isset($_GET['delete'])) {
            $controller->delete($_GET['delete']);
        } else {
            $controller->index();
        }
        break;
    case 'user_create':
        $controller = new UserController();
        $controller->create();
        break;
    case 'nilai_create':
        $controller = new ScoreController();
        $controller->create();
        break;
    default:
        require_once dirname(__FILE__) . '/../app/views/NotFoundView.php';
        break;
}