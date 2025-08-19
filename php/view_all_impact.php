<?php
require 'db_connect.php';

// Fetch all blogs where category='impact-stories'
$sql = "SELECT * FROM blogs WHERE category = 'impact-stories' ORDER BY publishDate DESC";
$result = $conn->query($sql);

if (!$result) {
    echo "<p>Error retrieving data: " . htmlspecialchars($conn->error) . "</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Impact Stories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        .card-img-fixed {
            width: 100%;
            max-height: 230px;
            min-height: 180px;
            object-fit: cover;
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }
        .card-body h4 {
            font-weight: 600;
        }
        .card.h-100 {
            height: 100%;
        }
        .row.g-4 > [class^='col-'] {
            display: flex;
        }
        /* Back icon button style */
        .btn-back-icon {
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background-color: #fff;
            border: 2px solid #e60000ff;
            color: #e60000ff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }
        .btn-back-icon:hover {
            background-color: #e60000ff;
            color: #fff;
            transform: scale(1.1);
            text-decoration: none;
        }
        /* Center heading */
        .header-container {
            position: relative;
            text-align: center;
        }
        .header-container .btn-back-icon {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>

<body>
    <section class="section-padding bg-light py-4">
        <div class="container">
            <!-- New styled header with back button -->
            <div class="header-container mb-4">
                <h2 class="fw-bold mb-1">Impact Stories</h2>
                <p class="lead mb-0 text-muted">Real stories from children who have transformed their lives</p>
                <a href="/seva-main/news-stories.php" class="btn-back-icon" title="Back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="row g-4">
                <?php while ($blog = $result->fetch_assoc()) :
                    $imgRaw = isset($blog['image_url']) ? trim($blog['image_url']) : '';

                    // Image fetching logic
                    if (!empty($imgRaw)) {
                        if (preg_match('/^https?:\/\//', $imgRaw)) {
                            $image = htmlspecialchars($imgRaw);
                        } else {
                            $image = '/seva-main/' . ltrim($imgRaw, '/');
                            $image = htmlspecialchars($image);
                        }
                    } else {
                        $image = 'https://via.placeholder.com/600x400?text=No+Image';
                    }

                    $heading = !empty($blog['heading']) ? htmlspecialchars($blog['heading']) : htmlspecialchars($blog['title']);
                    $excerpt = strip_tags($blog['content']);
                    if (strlen($excerpt) > 200) $excerpt = substr($excerpt, 0, 197) . '...';
                    $readMoreLink = "php/impact_blog_details.php?id=" . urlencode($blog['id']);
                ?>
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card border-0 shadow-sm h-100 w-100">
                            <div class="row g-0 h-100">
                                <div class="col-md-5">
                                    <img src="<?= $image ?>" 
                                         alt="<?= $heading ?>" 
                                         class="card-img-fixed"
                                         onerror="this.onerror=null;this.src='https://via.placeholder.com/600x400?text=No+Image';">
                                </div>
                                <div class="col-md-7 d-flex flex-column">
                                    <div class="card-body d-flex flex-column h-100">
                                        <span class="badge bg-danger mb-2 align-self-start">Impact Story</span>
                                        <h4><?= $heading ?></h4>
                                        <p class="flex-grow-1"><?= $excerpt ?></p>
                                        <a href="impact_blog_details.php?id=<?= $blog['id'] ?>" class="btn btn-outline-danger align-self-start mt-auto">Read Full Story</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
