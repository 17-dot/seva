<?php
require 'db_connect.php';  // Adjust this to your actual DB connection file

header('Content-Type: application/json');

// Initialize the response array
$response = [
    'total_blogs' => 0,
    'impact_stories' => 0,
    'news' => 0,
    'programs' => 0,
    'events' => 0,
];

// Get total blogs count
$sql = "SELECT COUNT(*) as total FROM blogs";
$result = $conn->query($sql);
if($result && $row = $result->fetch_assoc()) {
    $response['total_blogs'] = (int)$row['total'];
}

// Count blogs by category
$categories = ['impact-stories', 'news', 'programs', 'events'];

foreach ($categories as $category) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM blogs WHERE category = ?");
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $catResult = $stmt->get_result();
    if ($catResult && $catRow = $catResult->fetch_assoc()) {
        $response[$category] = (int)$catRow['total'];
    }
    $stmt->close();
}

$conn->close();

echo json_encode($response);
exit;
?>
