<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get user role name for display (inline function to avoid conflicts)
if (!function_exists('getRoleName')) {
    function getRoleName($roleId) {
        $roles = [
            0 => 'Superadmin',
            1 => 'Admin', 
            2 => 'Guru Bahasa Indonesia',
            3 => 'Guru Bahasa Inggris',
            4 => 'Guru Matematika',
            5 => 'Wali Kelas',
            6 => 'Siswa'
        ];
        return $roles[$roleId] ?? 'Unknown';
    }
}
?>

<nav class="modern-navbar">
    <div class="navbar-container">
        <!-- Left side: Logo and burger menu -->
        <div class="navbar-left">
            <?php if (isset($_SESSION['user_id'])): ?>
                <button class="burger-menu" onclick="toggleSidebar()" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            <?php endif; ?>
            
            <a href="?page=home" class="navbar-brand">
                <i class="fas fa-graduation-cap"></i>
                <span>eNilai</span>
            </a>
        </div>
        
        <!-- Right side: User menu or login -->
        <div class="navbar-right">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- User Menu -->
                <div class="user-menu">
                    <button class="user-menu-btn" onclick="toggleUserDropdown()" aria-label="User menu">
                        <div class="user-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-info">
                            <span class="user-name"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                            <span class="user-role"><?= htmlspecialchars(getRoleName($_SESSION['id_role'] ?? 6)) ?></span>
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </button>
                    
                    <!-- User Dropdown -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="dropdown-header">
                            <div class="user-avatar-large">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="user-details">
                                <h4><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></h4>
                                <p><?= htmlspecialchars(getRoleName($_SESSION['id_role'] ?? 6)) ?></p>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="?page=profile" class="dropdown-item">
                            <i class="fas fa-user-circle"></i>
                            <span>Profile</span>
                        </a>
                        <a href="?page=logout" class="dropdown-item logout">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Log Out</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Login Button -->
                <a href="?page=login" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>