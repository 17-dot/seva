<?php
require 'db_connect.php';

// Fetch latest 6 posts
$sql = "SELECT * FROM blogs ORDER BY publishDate DESC LIMIT 6";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($blog = $result->fetch_assoc()) {
        // Choose a badge color (optional: map categories to colors)
        $badgeColors = [
            'news' => 'primary',
            'milestone' => 'success',
            'award' => 'warning',
            'expansion' => 'info',
            'event' => 'secondary',
            'fundraising' => 'success'
        ];
        $badgeClass = isset($badgeColors[strtolower($blog['category'])]) 
                        ? $badgeColors[strtolower($blog['category'])] 
                        : 'primary';

        // Escape data for HTML output
        $title = htmlspecialchars($blog['title']);
        $category = htmlspecialchars($blog['category']);
        $author = htmlspecialchars($blog['author']);
        $date = date("F j, Y", strtotime($blog['publishDate']));
        $image = !empty($blog['image_url']) ? $blog['image_url'] : 'https://via.placeholder.com/400x250?text=No+Image';
        $excerpt = strip_tags(substr($blog['content'], 0, 150)) . '...';

        echo '
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <img src="'.$image.'" class="card-img-top" style="height: 200px; object-fit: cover;" alt="'.$title.'">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-'.$badgeClass.'">'.ucfirst($category).'</span>
                        <small class="text-muted">'.$date.'</small>
                    </div>
                    <h5 class="card-title fw-bold">'.$title.'</h5>
                    <p class="card-text">'.$excerpt.'</p>
                    <a href="/Sankat-Saathi/php/blog-details.php?id='.$blog['id'].'" class="btn btn-outline-primary">Read More</a>
                </div>
            </div>
        </div>';
    }
} else {
    echo '<p class="text-center">No blog posts available right now.</p>';
}

$conn->close();
?>
