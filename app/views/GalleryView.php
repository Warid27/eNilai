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
<h2>Galeri Gambar</h2>
<form method="POST" action="?page=gallery" enctype="multipart/form-data" id="uploadForm">
    <label>ID Pengguna:</label>
    <input type="number" name="id_user" value="<?php echo htmlspecialchars($_SESSION['user_id']); ?>" readonly><br>
    <label>Pilih Gambar:</label>
    <input type="file" name="image" accept="image/jpeg,image/png" required><br>
    <button type="submit">Upload Gambar</button>
</form>

<div class="gallery">
    <?php foreach ($images as $image): ?>
        <div class="gallery-item">
            <img src="<?php echo htmlspecialchars(BASE_PATH . $image['image']); ?>" alt="Gallery Image" onerror="this.src='<?php echo htmlspecialchars(BASE_PATH . 'public/assets/img/enilai.png'); ?>'">
            <p>Pengguna: <?php echo htmlspecialchars($image['username']); ?></p>
            <button class="btn-edit" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($image)); ?>)">Edit</button>
            <button class="btn-delete" onclick="confirmDelete(<?php echo $image['id']; ?>)">Hapus</button>
        </div>
    <?php endforeach; ?>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="modal-close">&times;</span>
        <h3>Edit Gambar</h3>
        <form id="editForm" method="POST" action="?page=gallery" enctype="multipart/form-data">
            <input type="hidden" name="id" id="editId">
            <label>ID Pengguna:</label>
            <input type="number" name="id_user" id="editIdUser" readonly><br>
            <label>Pilih Gambar Baru:</label>
            <input type="file" name="image" accept="image/jpeg,image/png"><br>
            <button type="submit">Update Gambar</button>
        </form>
    </div>
</div>

<script>
function openEditModal(image) {
    const modal = document.getElementById('editModal');
    const editId = document.getElementById('editId');
    const editIdUser = document.getElementById('editIdUser');
    editId.value = image.id;
    editIdUser.value = image.id_user;
    modal.style.display = 'block';
}

function confirmDelete(id) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: 'Apakah Anda yakin ingin menghapus gambar ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '?page=gallery&delete=' + id;
        }
    });
}

// Close modal
document.querySelector('.modal-close').addEventListener('click', () => {
    document.getElementById('editModal').style.display = 'none';
});

// Close modal when clicking outside
window.addEventListener('click', (event) => {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});
</script>
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>