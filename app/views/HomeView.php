<?php
session_start();
$base_path = defined('BASE_PATH') ? BASE_PATH : dirname(dirname($_SERVER['SCRIPT_NAME'])) . '/';
$base_path = '/' . trim($base_path, '/') . '/';
define('BASE_PATH', $base_path);

ob_start();
?>

<!-- Modern Landing Page -->
<div class="landing-hero">
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">
                <span class="hero-title-main">eNilai</span>
                <span class="hero-title-sub">Modern Student Management System</span>
            </h1>
            <p class="hero-description">
                Sistem manajemen nilai siswa yang modern dan efisien. Kelola nilai, galeri, dan data siswa dengan mudah dalam satu platform terintegrasi.
            </p>
            
            <div class="hero-actions">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="?page=login" class="btn btn-primary btn-lg">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Masuk ke Sistem</span>
                    </a>
                    <a href="#features" class="btn btn-secondary btn-lg">
                        <i class="fas fa-info-circle"></i>
                        <span>Pelajari Lebih Lanjut</span>
                    </a>
                <?php else: ?>
                    <a href="?page=dashboard" class="btn btn-primary btn-lg">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Buka Dashboard</span>
                    </a>
                    <a href="?page=nilai" class="btn btn-secondary btn-lg">
                        <i class="fas fa-book"></i>
                        <span>Kelola Nilai</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="hero-visual">
            <div class="hero-image-container">
                <img src="<?php echo htmlspecialchars(BASE_PATH . 'public/assets/img/enilai.png'); ?>" alt="eNilai Logo" class="hero-image">
                <div class="hero-decoration">
                    <div class="decoration-circle circle-1"></div>
                    <div class="decoration-circle circle-2"></div>
                    <div class="decoration-circle circle-3"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="section-header">
        <h2>Fitur Unggulan</h2>
        <p>Sistem yang dirancang khusus untuk memudahkan pengelolaan akademik</p>
    </div>
    
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-book"></i>
            </div>
            <h3>Manajemen Nilai</h3>
            <p>Kelola nilai siswa dengan sistem role-based yang aman. Guru hanya dapat mengedit mata pelajaran sesuai bidangnya.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Multi-Role System</h3>
            <p>Sistem peran yang lengkap: Superadmin, Admin, Guru, Walikelas, dan Siswa dengan hak akses yang berbeda.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-image"></i>
            </div>
            <h3>Galeri Terintegrasi</h3>
            <p>Kelola galeri foto dan dokumen dengan mudah. Upload, lihat, dan organisir file dalam satu tempat.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3>Dashboard Analytics</h3>
            <p>Pantau performa dan statistik dengan dashboard yang informatif dan mudah dipahami.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h3>Responsive Design</h3>
            <p>Akses dari perangkat apapun. Desain yang responsif dan modern untuk pengalaman terbaik.</p>
        </div>
        
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Keamanan Terjamin</h3>
            <p>Sistem keamanan berlapis dengan enkripsi data dan kontrol akses yang ketat.</p>
        </div>
    </div>
</section>

<!-- Stats Section -->
<?php if (isset($_SESSION['user_id'])): ?>
<section class="stats-section">
    <div class="section-header">
        <h2>Selamat Datang Kembali, <?= htmlspecialchars($_SESSION['username']) ?>!</h2>
        <p>Berikut ringkasan aktivitas Anda</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                <h3>Total Siswa</h3>
                <p class="stat-number">150+</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="stat-content">
                <h3>Mata Pelajaran</h3>
                <p class="stat-number">3</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-images"></i>
            </div>
            <div class="stat-content">
                <h3>File Galeri</h3>
                <p class="stat-number">500+</p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <h3>Pengguna Aktif</h3>
                <p class="stat-number">25+</p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="cta-section">
    <div class="cta-content">
        <h2>Siap Memulai?</h2>
        <p>Bergabunglah dengan sistem manajemen nilai yang modern dan efisien</p>
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="?page=login" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket"></i>
                <span>Mulai Sekarang</span>
            </a>
        <?php else: ?>
            <a href="?page=dashboard" class="btn btn-primary btn-lg">
                <i class="fas fa-arrow-right"></i>
                <span>Lanjutkan ke Dashboard</span>
            </a>
        <?php endif; ?>
    </div>
</section>

<?php
$content = ob_get_clean();
require_once dirname(__FILE__) . '/layout.php';
?>