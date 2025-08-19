<?php
require 'db_connect.php';

// Fetch the latest 6 blogs from all categories
$sql = "SELECT * FROM blogs ORDER BY publishDate DESC LIMIT 6";
$result = $conn->query($sql);

if (!$result) {
    echo "<p>Error retrieving data: " . htmlspecialchars($conn->error) . "</p>";
    exit;
}
?>

<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5 mt-3">
            <h2 class="fw-bold">Latest Stories</h2>
            <p class="lead">Browse recent updates from all categories</p>
        </div>
        <div class="row">
            <?php while ($blog = $result->fetch_assoc()) : 
                // Image URL handling
                $imgRaw = isset($blog['image_url']) ? trim($blog['image_url']) : '';
                if (!empty($imgRaw)) {
                    if (preg_match('/^https?:\/\//', $imgRaw)) {
                        // Full URL
                        $image = htmlspecialchars($imgRaw);
                    } else {
                        // Local path under /seva-main/
                        $image = '/seva-main/' . ltrim($imgRaw, '/');
                        $image = htmlspecialchars($image);
                    }
                } else {
                    $image = 'https://via.placeholder.com/600x400?text=No+Image';
                }

                // Use heading if available, else title
                $heading = !empty($blog['heading']) 
                    ? htmlspecialchars($blog['heading']) 
                    : htmlspecialchars($blog['title']);

                // Excerpt
                $excerpt = strip_tags($blog['content']);
                if (strlen($excerpt) > 200) {
                    $excerpt = substr($excerpt, 0, 197) . '...';
                }

                // Category badge color map
                $badgeColors = [
                    'news' => 'primary',
                    'milestone' => 'success',
                    'award' => 'warning',
                    'expansion' => 'info',
                    'event' => 'secondary',
                    'fundraising' => 'success',
                    'programs' => 'info',
                    'impact-stories' => 'danger'
                ];
                $categoryLower = strtolower($blog['category']);
                $badgeClass = $badgeColors[$categoryLower] ?? 'dark';
            ?>
                <div class="col-lg-6 mb-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <img src="<?= $image ?>" alt="<?= $heading ?>" 
                                     class="img-fluid h-100 object-fit-cover rounded-start"
                                     onerror="this.onerror=null;this.src='https://via.placeholder.com/600x400?text=No+Image';">
                            </div>
                            <div class="col-md-7">
                                <div class="card-body h-100 d-flex flex-column">
                                    <span class="badge bg-<?= $badgeClass ?> align-self-start mb-2">
                                        <?= ucfirst(str_replace('-', ' ', $blog['category'])) ?>
                                    </span>
                                    <h4><?= $heading ?></h4>
                                    <p class="flex-grow-1"><?= $excerpt ?></p>
                                    <a href="php/blog-details.php?id=<?= $blog['id'] ?>" 
                                       class="btn btn-outline-danger align-self-start">
                                        Read Full Story
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- View All Button -->
        <div class="text-center mb-5">
            <a href="php/show_all_blogs.php" class="btn btn-danger btn-lg mb-3">View All</a>
        </div>
        
    </div>
</section>

<?php
$conn->close();
?>
