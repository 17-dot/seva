<?php
require 'db_connect.php'; // Adjust as needed

// Set headers to prompt download as CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=akshaya_patra_blogs_' . date('Y-m-d') . '.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, ['ID', 'Title', 'Author', 'Category', 'Publish Date', 'Image URL', 'Content']);

// Fetch blogs from database
$sql = "SELECT id, title, author, category, publishDate, image_url, content FROM blogs ORDER BY publishDate DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Clean content from HTML tags for CSV
        $contentText = strip_tags($row['content']);
        // Format publish date
        $publishDate = date('Y-m-d', strtotime($row['publishDate']));
        // Compose row
        fputcsv($output, [
            $row['id'],
            $row['title'],
            $row['author'],
            $row['category'],
            $publishDate,
            $row['image_url'],
            $contentText,
        ]);
    }
} else {
    // No blogs found: optional - output a message row
    fputcsv($output, ['No blogs found']);
}

$conn->close();
exit;
