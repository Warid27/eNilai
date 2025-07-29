<div class="sidebar">
    <h3>Menu</h3>
    <ul>
        <li><a href="?page=dashboard">Dashboard</a></li>
        <li><a href="?page=nilai">Nilai</a></li>
        <li><a href="?page=gallery">Galeri</a></li>
        <?php if (isset($_SESSION['id_role']) && $_SESSION['id_role'] == 1): ?>
            <li><a href="?page=user">Manajemen Pengguna</a></li>
        <?php endif; ?>
    </ul>
</div>