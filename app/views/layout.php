<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eNilai</title>
    <link rel="icon" href="<?= htmlspecialchars($base_path) ?>public/assets/icon/enilai.ico">
    <link rel="stylesheet" href="<?= htmlspecialchars($base_path) ?>public/assets/css/style.css">
</head>

<body>
    <div class="navbar">
        <?php require_once dirname(__FILE__) . '/../components/NavbarComponent.php'; ?>
    </div>
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert success">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php elseif (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?php echo htmlspecialchars($_SESSION['error']); ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        <?php echo $content; ?>
    </div>
</body>

</html>