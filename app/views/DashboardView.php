<?php
// Authentication check
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu!';
    header('Location: ?page=login');
    exit;
}

// Define base path for assets
$base_path = defined('BASE_PATH') ? BASE_PATH : dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/';
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $base_path);
}

// Start output buffering for content
ob_start();
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </h1>
        <p class="dashboard-welcome">
            Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
        </p>
    </div>

    <div class="dashboard-grid">
        <!-- Quick Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Users</h3>
                    <p class="stat-number">150</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <h3>Students</h3>
                    <p class="stat-number">120</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-content">
                    <h3>Teachers</h3>
                    <p class="stat-number">25</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content">
                    <h3>Avg Score</h3>
                    <p class="stat-number">85.2</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2 class="section-title">
                <i class="fas fa-bolt"></i>
                Quick Actions
            </h2>
            <div class="actions-grid">
                <?php if ($_SESSION['id_role'] <= 1): // Admin and Superadmin ?>
                    <a href="?page=users" class="action-card">
                        <i class="fas fa-user-plus"></i>
                        <span>Manage Users</span>
                    </a>
                <?php endif; ?>
                
                <?php if ($_SESSION['id_role'] <= 4): // Teachers and above ?>
                    <a href="?page=nilai" class="action-card">
                        <i class="fas fa-edit"></i>
                        <span>Manage Scores</span>
                    </a>
                <?php endif; ?>
                
                <a href="?page=gallery" class="action-card">
                    <i class="fas fa-images"></i>
                    <span>View Gallery</span>
                </a>
                
                <a href="?page=profile" class="action-card">
                    <i class="fas fa-user-cog"></i>
                    <span>My Profile</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2 class="section-title">
                <i class="fas fa-clock"></i>
                Recent Activity
            </h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="activity-content">
                        <p><strong>Login berhasil</strong></p>
                        <span class="activity-time">Just now</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="activity-content">
                        <p><strong>Profile updated</strong></p>
                        <span class="activity-time">2 hours ago</span>
                    </div>
                </div>
                
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="activity-content">
                        <p><strong>Scores reviewed</strong></p>
                        <span class="activity-time">1 day ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>