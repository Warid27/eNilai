<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get current page for active state
$currentPage = $_GET['page'] ?? 'home';
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i>
            <span>eNilai</span>
        </div>
        <button class="sidebar-close" onclick="toggleSidebar()" aria-label="Close sidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <div class="nav-section">
            <h4 class="nav-section-title">Main</h4>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="?page=dashboard" class="nav-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=home" class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <?php if (isset($_SESSION['id_role']) && in_array($_SESSION['id_role'], [0, 1, 2, 3, 4, 5])): ?>
        <div class="nav-section">
            <h4 class="nav-section-title">Academic</h4>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="?page=nilai" class="nav-link <?= $currentPage === 'nilai' ? 'active' : '' ?>">
                        <i class="fas fa-book"></i>
                        <span>Nilai Siswa</span>
                        <?php if ($_SESSION['id_role'] == 5): ?>
                            <span class="nav-badge">View Only</span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
        
        <div class="nav-section">
            <h4 class="nav-section-title">Media</h4>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="?page=gallery" class="nav-link <?= $currentPage === 'gallery' ? 'active' : '' ?>">
                        <i class="fas fa-image"></i>
                        <span>Galeri</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <?php if (isset($_SESSION['id_role']) && in_array($_SESSION['id_role'], [0, 1])): ?>
        <div class="nav-section">
            <h4 class="nav-section-title">Administration</h4>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="?page=user" class="nav-link <?= $currentPage === 'user' ? 'active' : '' ?>">
                        <i class="fas fa-users"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </nav>
    
    <div class="sidebar-footer">
        <div class="user-info-sidebar">
            <div class="user-avatar-small">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details-small">
                <span class="user-name-small"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></span>
                <span class="user-role-small"><?= htmlspecialchars(getRoleName($_SESSION['id_role'] ?? 6)) ?></span>
            </div>
        </div>
    </div>
</aside>