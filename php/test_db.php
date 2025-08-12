<?php
$servername = "localhost";
$username = "root";
$password = ""; // Use your password here
$dbname = "ngo_blog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>
