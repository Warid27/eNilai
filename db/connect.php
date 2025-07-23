<?php

include dirname(__FILE__) . "/../config.php";


// Database connection configuration
$host = $DB_HOST;
$username = $DB_USERNAME;
$password = $DB_PASSWORD;
$dbname = $DB_NAME;


try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}