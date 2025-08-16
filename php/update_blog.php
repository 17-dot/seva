<?php
header('Content-Type: application/json');
require 'db_connect.php';

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Validate
if (!isset($data['id']) || !isset($data['title']) || !isset($data['author']) || !isset($data['category'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$id       = intval($data['id']);
$title    = trim($data['title']);
$author   = trim($data['author']);
$category = trim($data['category']);
$imageUrl = trim($data['image_url']);
$content  = trim($data['content']);

$sql = "UPDATE blogs 
        SET title = ?, author = ?, category = ?, image_url = ?, content = ?, publishDate = NOW() 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $title, $author, $category, $imageUrl, $content, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Blog updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update blog"]);
}

$stmt->close();
$conn->close();
?>
