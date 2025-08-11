<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eNilai - Modern Student Management System</title>
    <!-- Google Fonts: Inter & Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Toastify CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0/src/toastify.min.css">
    <link rel="icon" href="<?= htmlspecialchars($base_path) ?>public/assets/icon/enilai.ico">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_path) ?>public/assets/css/style.css">
</head>
<body>
    <!-- Modern Navbar -->
    <?php require_once dirname(__FILE__) . '/../components/NavbarComponent.php'; ?>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php require_once dirname(__FILE__) . '/../components/SidebarComponent.php'; ?>
    <?php endif; ?>
    
    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <?php if (isset($_SESSION['message'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Toastify({
                        text: "<?php echo htmlspecialchars($_SESSION['message']); ?>",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#10b981",
                        stopOnFocus: true
                    }).showToast();
                });
            </script>
            <?php unset($_SESSION['message']); ?>
        <?php elseif (isset($_SESSION['error'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Toastify({
                        text: "<?php echo htmlspecialchars($_SESSION['error']); ?>",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#dc2626",
                        stopOnFocus: true
                    }).showToast();
                });
            </script>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <?php echo $content; ?>
    </div>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js@1.12.0"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Sidebar toggle functionality
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebar) {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
                mainContent.classList.toggle('sidebar-open');
            }
        }
        
        // User dropdown toggle
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) {
                dropdown.classList.toggle('active');
            }
        }
        
        // Close sidebar when clicking overlay
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('sidebarOverlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    toggleSidebar();
                });
            }
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const dropdown = document.getElementById('userDropdown');
                const userBtn = document.querySelector('.user-menu-btn');
                
                if (dropdown && !dropdown.contains(e.target) && !userBtn.contains(e.target)) {
                    dropdown.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>