<?php
$host = 'localhost';
$user = 'root';
$pass = ''; // Replace with your actual password
$db   = 'enilai';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully"; // Uncomment for testing
?>