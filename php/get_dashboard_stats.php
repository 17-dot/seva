<?php
require 'db_connect.php';

$data = [
    'total_blogs' => 0,
    'published_today' => 0,
    'impact_stories' => 0,
    'news_articles' => 0,
];

// Total blogs
$sql = "SELECT COUNT(*) as total FROM blogs";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $data['total_blogs'] = (int)$row['total'];
}

// Published today
$sql = "SELECT COUNT(*) as total FROM blogs WHERE DATE(publishDate) = CURDATE()";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $data['published_today'] = (int)$row['total'];
}

// Impact Stories count
$sql = "SELECT COUNT(*) as total FROM blogs WHERE category = 'impact-stories'";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $data['impact_stories'] = (int)$row['total'];
}

// News Articles count
$sql = "SELECT COUNT(*) as total FROM blogs WHERE category = 'news'";
$result = $conn->query($sql);
if ($result && $row = $result->fetch_assoc()) {
    $data['news_articles'] = (int)$row['total'];
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
exit;
