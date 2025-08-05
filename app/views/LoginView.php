<?php
session_start();
$base_path = defined('BASE_PATH') ? BASE_PATH : dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/';
define('BASE_PATH', $base_path);

ob_start();
if (isset($_SESSION['user_id'])) {
    header('Location: ?page=dashboard');
    exit;
}
?>
<h2>Login</h2>
<form method="POST" action="?page=login">
    <label>Username:</label>
    <input type="text" name="username" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>