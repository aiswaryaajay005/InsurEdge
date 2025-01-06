<?php
// db_connection.php

$servername = getenv('DB_SERVERNAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$dbname = getenv('DB_DBNAME');

// Create a connection
$conn = new mysqli('localhost','root', '', 'users');

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to utf8mb4 to support special characters
$conn->set_charset("utf8mb4");
?>