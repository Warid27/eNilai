<?php
ob_start();
?>
<h1>Selamat Datang di eNilai</h1>
<p>Aplikasi ini memungkinkan Anda untuk mengelola nilai siswa dan galeri gambar.</p>
<?php if (!isset($_SESSION['user_id'])): ?>
    <p>Silakan <a href="?page=login">login</a> untuk mengakses fitur lengkap.</p>
<?php else: ?>
    <p>Klik <a href="?page=dashboard">Dashboard</a> untuk mulai mengelola data.</p>
<?php endif; ?>
<img src="<?= htmlspecialchars($base_path) ?>public/assets/img/enilai.png" alt="eNilai Logo" style="max-width: 200px;">
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>