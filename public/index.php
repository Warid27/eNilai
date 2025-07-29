<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
session_start();

// ✅ Define base URL dynamically
$base_url = strtok($_SERVER['REQUEST_URI'], '?'); 
$script_dir = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim(dirname($script_dir), '/');
$base_path = $base_path . '/'; 

// Optional: define constant
define('BASE_PATH', $base_path);

require_once dirname(__FILE__) . '/../app/controllers/AuthController.php';
require_once dirname(__FILE__) . '/../app/controllers/ScoreController.php';
require_once dirname(__FILE__) . '/../app/controllers/GalleryController.php';
require_once dirname(__FILE__) . '/../app/controllers/UserController.php';

$page = $_GET['page'] ?? 'home';

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
        $controller = new UserController();
        if (isset($_GET['edit'])) {
            $controller->edit($_GET['edit']);
        } elseif (isset($_GET['delete'])) {
            $controller->delete($_GET['delete']);
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
        $controller->index();
        $controller->create();
        break;
    default:
        require_once dirname(__FILE__) . '/../app/views/NotFoundView.php';
        break;
}