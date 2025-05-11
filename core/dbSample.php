<?php
// Rename this file to db.php and update with your actual DB credentials

$host = "localhost";
$user = "your_db_username";
$password = "your_db_password";
$database = "your_database_name";

$conn = new mysqli($host, $user, $password, $database);

// Set character set to UTF-8 (recommended for emoji, multibyte support)
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>