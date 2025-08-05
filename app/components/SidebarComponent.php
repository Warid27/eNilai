<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="sidebar">
    <h3><i class="fas fa-bars"></i> Menu</h3>
    <ul>
        <li><a href="?page=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <?php if (isset($_SESSION['id_role']) && in_array($_SESSION['id_role'], [1, 2, 3, 4, 5])): ?>
            <li><a href="?page=nilai"><i class="fas fa-book"></i> Nilai</a></li>
        <?php endif; ?>
        <li><a href="?page=gallery"><i class="fas fa-image"></i> Galeri</a></li>
        <?php if (isset($_SESSION['id_role']) && $_SESSION['id_role'] == 1): ?>
            <li><a href="?page=user"><i class="fas fa-users"></i> Manajemen Pengguna</a></li>
        <?php endif; ?>
    </ul>
</div>