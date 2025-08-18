<?php
require 'db_connect.php';

// Fetch latest 6 posts from 'news'
$sql = "SELECT * FROM blogs WHERE category = 'news' ORDER BY publishDate DESC LIMIT 6";
$result = $conn->query($sql);
?>

<div class="row">
<?php
if ($result && $result->num_rows > 0) {
    // Badge colors
    $badgeColors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];

    while ($blog = $result->fetch_assoc()) {
        // Escape data

        $title = htmlspecialchars($blog['title']);
        $category = htmlspecialchars($blog['category']);
        $author = htmlspecialchars($blog['author']);
        $date = date("F j, Y", strtotime($blog['publishDate']));

        // Fixed image path logic
        $imgRaw = isset($blog['image_url']) ? trim($blog['image_url']) : '';
        if (!empty($imgRaw)) {
            if (preg_match('/^https?:\/\//', $imgRaw)) {
                $image = htmlspecialchars($imgRaw);
            } else {
                $image = '/seva-main/' . ltrim($imgRaw, '/');
                $image = htmlspecialchars($image);
            }
        } else {
            $image = 'https://via.placeholder.com/400x250?text=No+Image';
        }

        // Badge class random
        $badgeClass = $badgeColors[array_rand($badgeColors)];

        // Excerpt
        $excerpt = strip_tags($blog['content']);
        if (strlen($excerpt) > 150) $excerpt = substr($excerpt, 0, 147) . '...';

        // Output each card
        echo '
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <img src="'.$image.'" class="card-img-top" style="height: 200px; object-fit: cover;" alt="'.$title.'" 
                     onerror="this.onerror=null;this.src=\'https://via.placeholder.com/400x250?text=No+Image\';">
                <div class="card-body d-flex flex-column">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-'.$badgeClass.'">'.ucfirst($category).'</span>
                        <small class="text-muted">'.$date.'</small>
                    </div>
                    <h5 class="card-title fw-bold">'.$title.'</h5>

                    <p class="card-text flex-grow-1">'.$excerpt.'</p>
                    <a href="/seva-main/php/blog-details.php?id='.$blog['id'].'" class="btn btn-outline-primary mt-auto">Read More</a>

                </div>
            </div>
        </div>';
    }
} else {
    echo '<p class="text-center">No blog posts available right now.</p>';
}
?>
</div>

<?php
// Show "View All" button only once, at the bottom center
if ($result && $result->num_rows > 0) {
    echo '
    <div class="text-center mt-4">
        <a href="/seva-main/php/view_all_news.php" class="btn btn-primary btn-lg">View All</a>
    </div>';
}


$conn->close();
?>
