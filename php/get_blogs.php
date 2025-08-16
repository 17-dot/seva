<?php
header('Content-Type: application/json; charset=utf-8');
require 'db_connect.php';

// Query - fetch only required columns
$sql = "SELECT id, title, heading ,author, category, image_url, content, publishDate 
        FROM blogs 
        ORDER BY publishDate DESC";

$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => $conn->error]);
    $conn->close();
    exit;
}

$blogs = [];
while ($row = $result->fetch_assoc()) {
    $blogs[] = $row;
}

echo json_encode($blogs, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
