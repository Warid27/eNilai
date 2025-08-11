<?php
session_start();
$base_path = defined('BASE_PATH') ? BASE_PATH : dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/';
define('BASE_PATH', $base_path);

// Get user role name for display
function getRoleName($roleId) {
    $roles = [
        0 => 'Superadmin',
        1 => 'Admin', 
        2 => 'Guru Bahasa Indonesia',
        3 => 'Guru Bahasa Inggris',
        4 => 'Guru Matematika',
        5 => 'Walikelas',
        6 => 'Siswa'
    ];
    return $roles[$roleId] ?? 'Unknown';
}

ob_start();
?>

<!-- Profile Page -->
<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar-large">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <h1><?= htmlspecialchars($userData['username']) ?></h1>
                <p class="profile-role"><?= htmlspecialchars(getRoleName($userData['id_role'])) ?></p>
                <?php if (!empty($userData['nis'])): ?>
                    <p class="profile-nis">NIS: <?= htmlspecialchars($userData['nis']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="profile-actions">
            <a href="?page=dashboard" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <div class="profile-grid">
            <!-- Edit Profile Card -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user-edit"></i> Edit Profile</h3>
                </div>
                <div class="card-body">
                    <form method="POST" class="profile-form">
                        <input type="hidden" name="action" value="update_profile">
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" 
                                   value="<?= htmlspecialchars($userData['username']) ?>" 
                                   required maxlength="50">
                            <small class="form-help">Username harus unik dan tidak boleh kosong</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="nis">NIS (Nomor Induk Siswa)</label>
                            <input type="text" id="nis" name="nis" 
                                   value="<?= htmlspecialchars($userData['nis'] ?? '') ?>" 
                                   maxlength="20" placeholder="Opsional">
                            <small class="form-help">Kosongkan jika tidak berlaku untuk role Anda</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Role</label>
                            <div class="role-display">
                                <span class="status-badge status-active">
                                    <?= htmlspecialchars(getRoleName($userData['id_role'])) ?>
                                </span>
                            </div>
                            <small class="form-help">Role tidak dapat diubah sendiri. Hubungi administrator jika perlu perubahan.</small>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-lock"></i> Ubah Password</h3>
                </div>
                <div class="card-body">
                    <form method="POST" class="profile-form">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div class="form-group">
                            <label for="current_password">Password Saat Ini</label>
                            <div class="password-input-group">
                                <input type="password" id="current_password" name="current_password" 
                                       required minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <div class="password-input-group">
                                <input type="password" id="new_password" name="new_password" 
                                       required minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <small class="form-help">Minimal 6 karakter</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password Baru</label>
                            <div class="password-input-group">
                                <input type="password" id="confirm_password" name="confirm_password" 
                                       required minlength="6">
                                <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i>
                            <span>Ubah Password</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Informasi Akun</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>User ID</label>
                            <span><?= htmlspecialchars($userData['id']) ?></span>
                        </div>
                        
                        <div class="info-item">
                            <label>Username</label>
                            <span><?= htmlspecialchars($userData['username']) ?></span>
                        </div>
                        
                        <div class="info-item">
                            <label>Role</label>
                            <span><?= htmlspecialchars(getRoleName($userData['id_role'])) ?></span>
                        </div>
                        
                        <?php if (!empty($userData['nis'])): ?>
                        <div class="info-item">
                            <label>NIS</label>
                            <span><?= htmlspecialchars($userData['nis']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="account-actions">
                        <h4>Aksi Akun</h4>
                        <div class="action-buttons">
                            <?php if (in_array($userData['id_role'], [0, 1, 2, 3, 4, 5])): ?>
                                <a href="?page=nilai" class="btn btn-success btn-sm">
                                    <i class="fas fa-book"></i>
                                    <span>Kelola Nilai</span>
                                </a>
                            <?php endif; ?>
                            
                            <a href="?page=gallery" class="btn btn-info btn-sm">
                                <i class="fas fa-images"></i>
                                <span>Lihat Galeri</span>
                            </a>
                            
                            <?php if (in_array($userData['id_role'], [0, 1])): ?>
                                <a href="?page=user" class="btn btn-primary btn-sm">
                                    <i class="fas fa-users"></i>
                                    <span>Kelola Pengguna</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password toggle functionality
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const changePasswordForm = document.querySelector('form[action*="change_password"]');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Password baru dan konfirmasi password tidak cocok!');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Password baru minimal 6 karakter!');
                return false;
            }
        });
    }
});
</script>

<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>
