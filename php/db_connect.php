<?php
$servername = "localhost"; // Your database server
$username = "root";        // Default XAMPP username
$password = "";            // Default XAMPP password (empty)
$dbname = "ngo_blog";      // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
