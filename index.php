<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Nilai</title>
    <link rel="icon" href="assets/icon/enilai.ico">
</head>

<body>
    <?php
    include "utils/path.php";
    include "db/connect.php";
    ?>
    <?php include "component/navbar.php" ?>
    <div class="main"></div>
    <?php
    $page   = isset($_GET['page']) ? basename($_GET['page']) : 'home';
    $action = isset($_GET['action']) ? basename($_GET['action']) : null;

    // Define accessible pages
    $publicPages       = ['home', 'nilai', 'login'];
    $loggedInPages     = ['dashboard'];
    $dashboardSections = ['users', 'scores', 'dashboard', 'profile'];
    $adminSections     = ['users'];

    $validPages = array_merge($publicPages, $loggedInPages, $dashboardSections);

    // Action controllers
    if ($action) {
        $controllerPath = "controller/{$action}.php";
        if (file_exists($controllerPath)) {
            include $controllerPath;
        } else {
            echo "❌ Controller not found: $controllerPath";
        }
    }

    // Get pages
    if (in_array($page, $validPages)) {
        $filePath = "page/{$page}.php";
        if (file_exists($filePath)) {
            include $filePath;
        } else {
            include "page/404.php";
        }
    } else {
        include "page/home.php";
    }
    ?>

</body>

</html>