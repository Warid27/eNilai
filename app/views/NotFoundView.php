<?php
ob_start();
?>
<h1>404 - Halaman Tidak Ditemukan</h1>
<p>Maaf, halaman yang Anda cari tidak ada. Silakan kembali ke <a href="?page=home" class="btn-back">Beranda</a>.</p>
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';