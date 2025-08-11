<?php
// Authentication and authorization checks
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu!';
    header('Location: ?page=login');
    exit;
}

// Role-based access: Only superadmin (0) and admin (1) can manage users
if ($_SESSION['id_role'] > 1) {
    $_SESSION['error'] = 'Akses ditolak! Hanya superadmin dan admin yang dapat mengelola pengguna.';
    header('Location: ?page=dashboard');
    exit;
}

// Define base path for assets
$base_path = defined('BASE_PATH') ? BASE_PATH : dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/';
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $base_path);
}

// Helper function to get role name
function getRoleName($roleId) {
    $roleNames = [
        0 => 'Superadmin',
        1 => 'Admin', 
        2 => 'Guru Bahasa Indonesia',
        3 => 'Guru Bahasa Inggris',
        4 => 'Guru Matematika',
        5 => 'Wali Kelas',
        6 => 'Siswa'
    ];
    return $roleNames[$roleId] ?? 'Unknown';
}

// Start output buffering for content
ob_start();
?>
<?php if (isset($_GET['page']) && $_GET['page'] == 'users' && !isset($_GET['edit'])) { ?>
    <div class="page-header">
        <div class="page-title">
            <h1><i class="fas fa-users"></i> Manajemen Pengguna</h1>
            <p>Kelola pengguna sistem e-Nilai</p>
        </div>
        <div class="page-actions">
            <a href="?page=user_create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pengguna
            </a>
        </div>
    </div>

    <?php if (isset($users) && !empty($users)): ?>
        <div class="table-container">
            <div class="table-wrapper">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-user"></i> Username</th>
                            <th><i class="fas fa-id-card"></i> NIS</th>
                            <th><i class="fas fa-user-tag"></i> Role</th>
                            <th><i class="fas fa-cogs"></i> Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <span class="username"><?= htmlspecialchars($user['username']) ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="nis-badge">
                                        <?= $user['nis'] ? htmlspecialchars($user['nis']) : '-' ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="role-badge role-<?= $user['id_role'] ?>">
                                        <?= getRoleName($user['id_role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="?page=users&edit=<?= $user['id'] ?>" class="btn btn-sm btn-secondary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['id_role'] == 0 || ($user['id_role'] > $_SESSION['id_role'])): ?>
                                            <button onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['username']) ?>')" 
                                                    class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            function deleteUser(userId, username) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: `Apakah Anda yakin ingin menghapus pengguna "${username}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `?page=users&delete=${userId}`;
                    }
                });
            }
        </script>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Belum Ada Pengguna</h3>
            <p>Mulai dengan menambahkan pengguna pertama</p>
            <a href="?page=user_create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pengguna
            </a>
        </div>
    <?php endif; ?>
<?php } ?>
<?php if (isset($_GET['edit'])): ?>
    <div class="page-header">
        <div class="page-title">
            <h1>
                <a href="?page=users" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-user-edit"></i> Edit Pengguna
            </h1>
            <p>Perbarui informasi pengguna</p>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form method="POST" action="?page=users&edit=<?php echo $user['id']; ?>" id="editUserForm">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" 
                           id="username"
                           name="username" 
                           value="<?php echo htmlspecialchars($user['username']); ?>" 
                           required
                           class="form-control">
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                        <small>(kosongkan jika tidak ingin mengubah)</small>
                    </label>
                    <div class="password-input-group">
                        <input type="password" 
                               id="password"
                               name="password" 
                               class="form-control">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- NIS Field - Initially shown only if current role is 6 -->
                <div class="form-group" id="nisField" <?php echo $user['id_role'] != 6 ? 'style="display:none;"' : ''; ?>>
                    <label for="nis">
                        <i class="fas fa-id-card"></i> NIS
                    </label>
                    <input type="number" 
                           id="nisInput"
                           name="nis" 
                           value="<?php echo htmlspecialchars($user['nis'] ?? ''); ?>" 
                           class="form-control"
                           <?php echo $user['id_role'] == 6 ? 'required' : ''; ?>>
                </div>

                <div class="form-group">
                    <label for="roleSelect">
                        <i class="fas fa-user-tag"></i> Role
                    </label>
                    <select name="id_role" id="roleSelect" required class="form-control">
                        <?php foreach ($roles as $role): ?>
                            <?php 
                            // Superadmin can edit all roles, Admin can't edit superadmin
                            if ($_SESSION['id_role'] == 1 && $role['id'] == 0) continue;
                            ?>
                            <option value="<?php echo $role['id']; ?>" 
                                    <?php echo $user['id_role'] == $role['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($role['role']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="?page=users" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Toggle NIS field based on role
        document.getElementById('roleSelect').addEventListener('change', function() {
            const nisField = document.getElementById('nisField');
            const nisInput = document.getElementById('nisInput');

            if (this.value == '6') {
                nisField.style.display = 'block';
                nisInput.required = true;
            } else {
                nisField.style.display = 'none';
                nisInput.required = false;
                nisInput.value = '';
            }
        });
    </script>
<?php elseif (isset($_GET['page']) && $_GET['page'] == 'user_create'): ?>
    <div class="page-header">
        <div class="page-title">
            <h1>
                <a href="?page=users" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <i class="fas fa-user-plus"></i> Tambah Pengguna
            </h1>
            <p>Buat pengguna baru untuk sistem e-Nilai</p>
        </div>
    </div>

    <div class="form-container">
        <div class="form-card">
            <form method="POST" action="?page=user_create" id="createUserForm">
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <input type="text" 
                           id="username"
                           name="username" 
                           required
                           class="form-control"
                           placeholder="Masukkan username">
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="password-input-group">
                        <input type="password" 
                               id="password"
                               name="password" 
                               required
                               class="form-control"
                               placeholder="Masukkan password">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- NIS Field (conditionally shown) -->
                <div class="form-group" id="nisField" style="display: none;">
                    <label for="nis">
                        <i class="fas fa-id-card"></i> NIS
                    </label>
                    <input type="number" 
                           id="nisInput"
                           name="nis" 
                           class="form-control"
                           placeholder="Masukkan NIS">
                </div>

                <div class="form-group">
                    <label for="roleSelect">
                        <i class="fas fa-user-tag"></i> Role
                    </label>
                    <select name="id_role" id="roleSelect" required class="form-control">
                        <option value="">Pilih Role</option>
                        <?php foreach ($roles as $role): ?>
                            <?php 
                            // Admin can't create superadmin
                            if ($_SESSION['id_role'] == 1 && $role['id'] == 0) continue;
                            ?>
                            <option value="<?php echo $role['id']; ?>">
                                <?php echo htmlspecialchars($role['role']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="?page=users" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Toggle NIS field based on role
        document.getElementById('roleSelect').addEventListener('change', function() {
            const nisField = document.getElementById('nisField');
            const nisInput = document.getElementById('nisInput');

            if (this.value == '6') {
                nisField.style.display = 'block';
                nisInput.required = true;
            } else {
                nisField.style.display = 'none';
                nisInput.required = false;
                nisInput.value = '';
            }
        });

        // Trigger on page load to check initial role selection
        document.getElementById('roleSelect').dispatchEvent(new Event('change'));
    </script>
<?php endif; ?>
<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>