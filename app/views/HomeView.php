<?php
session_start();
$base_path = defined('BASE_PATH') ? BASE_PATH : dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/';
define('BASE_PATH', $base_path);

ob_start();
?>
<h1>Selamat Datang di eNilai</h1>
<p>Aplikasi ini memungkinkan Anda untuk mengelola nilai siswa dan galeri gambar.</p>
<?php if (!isset($_SESSION['user_id'])): ?>
    <p>Silakan <a href="?page=login" class="btn">Login</a> untuk mengakses fitur lengkap.</p>
<?php else: ?>
    <p>Klik <a href="?page=dashboard" class="btn">Dashboard</a> untuk mulai mengelola data.</p>
<?php endif; ?>
<img src="<?php echo htmlspecialchars(BASE_PATH . 'public/assets/img/enilai.png'); ?>" alt="eNilai Logo" style="max-width: 200px;">
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>