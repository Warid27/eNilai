<?php
session_start();
$base_path = defined('BASE_PATH') ? BASE_PATH : dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/';
define('BASE_PATH', $base_path);

ob_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu!';
    header('Location: ?page=login');
    exit;
}
?>
<h2>Dashboard</h2>
<p>Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
<div class="sidebar">
    <?php require_once dirname(__FILE__) . '/../components/SidebarComponent.php'; ?>
</div>
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>