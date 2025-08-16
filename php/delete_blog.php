<?php
header('Content-Type: application/json');
require 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$blogId = isset($data['id']) ? intval($data['id']) : 0;

if ($blogId <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid blog ID"]);
    exit;
}

$sql = "DELETE FROM blogs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $blogId);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Blog deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to delete blog"]);
}

$stmt->close();
$conn->close();
?>
