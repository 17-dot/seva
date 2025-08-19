<?php
require 'db_connect.php';

// Fetch all blogs where category='impact stories'
$sql = "SELECT * FROM blogs WHERE category = 'impact-stories' ORDER BY publishDate DESC";
$result = $conn->query($sql);

if (!$result) {
    echo "<p>Error retrieving data: " . htmlspecialchars($conn->error) . "</p>";
    exit;
}
?>

<section class="section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5 mt-3">
            <h2 class="fw-bold">Impact Stories</h2>
            <p class="lead">Real stories from children who have transformed their lives</p>
        </div>
        <div class="row">

            <?php while ($blog = $result->fetch_assoc()) : 
                $image = !empty($blog['image_url']) ? htmlspecialchars($blog['image_url']) : 'https://via.placeholder.com/600x400?text=No+Image';
                $heading = !empty($blog['heading']) ? htmlspecialchars($blog['heading']) : htmlspecialchars($blog['title']);
                $excerpt = strip_tags($blog['content']);
                if (strlen($excerpt) > 200) {
                    $excerpt = substr($excerpt, 0, 197) . '...';
                }
                // Set Read More link here as needed
                $readMoreLink = '#';
            ?>
                <div class="col-lg-6 mb-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="row g-0">
                            <div class="col-md-5">
                                <img src="<?= $image ?>" alt="<?= $heading ?>" class="img-fluid h-100 object-fit-cover rounded-start">
                            </div>
                            <div class="col-md-7">
                                <div class="card-body h-100 d-flex flex-column">
                                    <span class="badge bg-danger align-self-start mb-2">Impact Story</span>
                                    <h4><?= $heading ?></h4>
                                    <p class="flex-grow-1"><?= $excerpt ?></p>
                                    <!-- <a href="<?= $readMoreLink ?>" class="btn btn-outline-primary align-self-start">Read Full Story</a> -->
                                    <a href="php/impact_blog_details.php?id=<?= $blog['id'] ?>" class="btn btn-outline-danger align-self-start">Read Full Story</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>

        </div>
        <div class="text-center mb-5">
            <a href="php/view_all_impact.php" class="btn btn-danger btn-lg mb-3">View All</a>
        </div>
    </div>
</section>

<?php
$conn->close();
?>
