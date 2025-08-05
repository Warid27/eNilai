<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div class="navbar">
    <a href="?page=home"><i class="fas fa-home"></i> Home</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php if (in_array($_SESSION['id_role'], [1, 2, 3, 4, 5, 6])): ?>
            <a href="?page=nilai"><i class="fas fa-book"></i> Nilai</a>
        <?php endif; ?>
        <a href="?page=gallery"><i class="fas fa-image"></i> Galeri</a>
        <a href="?page=dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <?php if ($_SESSION['id_role'] == 1): ?>
            <a href="?page=user"><i class="fas fa-users"></i> Pengguna</a>
        <?php endif; ?>
        <a href="?page=logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    <?php else: ?>
        <a href="?page=login"><i class="fas fa-sign-in-alt"></i> Login</a>
    <?php endif; ?>
</div>