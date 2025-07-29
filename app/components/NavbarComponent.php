<div class="navbar">
    <a href="?page=home">Home</a>
    <a href="?page=nilai">Nilai</a>
    <a href="?page=gallery">Galeri</a>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="?page=dashboard">Dashboard</a>
        <?php if ($_SESSION['id_role'] == 1): ?>
            <a href="?page=user">Pengguna</a>
        <?php endif; ?>
        <a href="?page=logout">Logout</a>
    <?php else: ?>
        <a href="?page=login">Login</a>
    <?php endif; ?>
</div>