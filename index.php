<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eNilai</title>
    <link rel="icon" href="assets/icon/enilai.ico">
</head>

<body>
    <?php

    require_once dirname(__FILE__) . "/component/table.php";
    require_once dirname(__FILE__) . "/component/navbar.php";
    require_once dirname(__FILE__) . "/db/connect.php";
    require_once dirname(__FILE__) . "/api.php";


    $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'home';

    // Define a simple routing array
    $routes = [
        'home' => 'view/home.php',
        'nilai' => 'view/nilai.php',
        'login' => 'view/login.php',
        'dashboard' => 'view/dashboard.php',
    ];

    // Check if the requested page exists in routes
    if (array_key_exists($page, $routes)) {
        // Include the corresponding page file
        require_once dirname(__FILE__) . "/$routes[$page]";
    } else {
        // Handle 404 - page not found
        http_response_code(404);
        require_once dirname(__FILE__) . "/view/404.php";
    }

    ?>

</body>

</html>