<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db   = 'enilai';
header('Content-Type: application/json');
// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// echo "Connected successfully"; // Uncomment for testing
?>