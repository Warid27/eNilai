<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eNilai</title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    <div class="navbar">
        <?php require_once dirname(__FILE__) . '/../components/NavbarComponent.php'; ?>
    </div>
    <div class="container">
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
</body>
</html>