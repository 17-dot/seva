<?php
session_start();
header('Content-Type: application/json');
require 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username']);
$password = trim($data['password']);

$sql = "SELECT * FROM admins WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $admin = $result->fetch_assoc();

    // Direct comparison (plain text)
    if ($password === $admin['password']) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
