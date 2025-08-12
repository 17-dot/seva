<?php
header('Content-Type: application/json');
require 'db_connect.php';

$blogId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($blogId <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid blog ID"]);
    exit;
}

$sql = "SELECT id, title, author, category, image_url, content 
        FROM blogs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blogId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["status" => "error", "message" => "Blog not found"]);
}

$stmt->close();
$conn->close();
?>
