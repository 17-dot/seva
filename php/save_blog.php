<?php
header('Content-Type: application/json');
require 'db_connect.php';

// Get JSON from request body
$data = json_decode(file_get_contents("php://input"), true);

// Validation
if (!isset($data['title']) || !isset($data['author']) || !isset($data['content'])) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit;
}

$title = $conn->real_escape_string($data['title']);
$category = $conn->real_escape_string($data['category']);
$author = $conn->real_escape_string($data['author']);
$image_url = $conn->real_escape_string($data['image_url']);
$content = $conn->real_escape_string($data['content']);

$sql = "INSERT INTO blogs (title, category, author, image_url, content) 
        VALUES ('$title', '$category', '$author', '$image_url', '$content')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Blog post saved!"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$conn->close();
?>
