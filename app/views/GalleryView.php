<?php

$base_path = dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/'; // Clean it
define('BASE_PATH', $base_path);


ob_start();
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu!';
    header('Location: ?page=login');
    exit;
}
?>
<h2>Galeri Gambar</h2>
<form method="POST" action="?page=gallery" enctype="multipart/form-data">
    <?php if ($edit): ?>
        <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
    <?php endif; ?>
    <label>ID Pengguna:</label>
    <input type="number" name="id_user" value="<?php echo $edit['id_user'] ?? $_SESSION['user_id']; ?>" required><br>
    <label>Pilih Gambar:</label>
    <input type="file" name="image" accept="image/*" required><br>
    <button type="submit"><?php echo $edit ? 'Update' : 'Upload'; ?> Gambar</button>
</form>
<div class="gallery">
    <?php foreach ($images as $image): ?>
        <div class="gallery-item">
            <img src="<?php echo htmlspecialchars($image['image']); ?>" alt="Gallery Image">
            <p>Pengguna: <?php echo htmlspecialchars($image['username']); ?></p>
            <a href="?page=gallery&edit=<?php echo $image['id']; ?>">Edit</a> |
            <a href="?page=gallery&delete=<?php echo $image['id']; ?>" onclick="return confirm('Hapus gambar ini?')">Hapus</a>
        </div>
    <?php endforeach; ?>
</div>
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>