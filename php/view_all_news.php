<?php
require 'db_connect.php';

// Fetch all blogs where category='news'
$sql = "SELECT * FROM blogs WHERE category = 'news' ORDER BY publishDate DESC";
$result = $conn->query($sql);

$badgeColors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>All News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        .card-img-top {
            height: 160px; /* Smaller card height */
            object-fit: cover;
        }
        /* Back icon button */
        .btn-back-icon {
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background-color: #fff;
            border: 2px solid #0d6efd;
            color: #0d6efd;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: all 0.2s ease-in-out;
        }
        /* .btn-back-icon:hover {
            background-color: #0d6efd;
            color: #fff;
            transform: scale(1.1);
        } */
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
        /* Back icon button */
.btn-back-icon {
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    background-color: #fff;
    border: 2px solid #0d6efd;
    color: #0d6efd;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: all 0.2s ease-in-out;
    text-decoration: none; /* 🔹 removes underline */
}
.btn-back-icon:hover {
    background-color: #0d6efd;
    color: #fff;
    /* transform: scale(1.1); */
    text-decoration: none; /* 🔹 prevents underline on hover */
}

    </style>
</head>
<body>
    <section class="section-padding bg-light py-3">
        <div class="container">
            <!-- Centered Heading + Back button icon on right -->
            <div class="header-container mb-4 mt-2">
                <h2 class="fw-bold mb-1">Latest News</h2>
                <p class="lead mb-0 text-muted">Stay updated with our latest announcements</p>
                <a href="/seva-main/news-stories.php" class="btn-back-icon" title="Back">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            <div class="row mt-3">
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($blog = $result->fetch_assoc()) {
                        $title = htmlspecialchars($blog['title']);
                        $category = htmlspecialchars($blog['category']);
                        $author = htmlspecialchars($blog['author']);
                        $date = date("F j, Y", strtotime($blog['publishDate']));

                        // Fixed image logic
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

                        $badgeClass = $badgeColors[array_rand($badgeColors)];
                        $excerpt = strip_tags($blog['content']);
                        if (strlen($excerpt) > 120) $excerpt = substr($excerpt, 0, 117) . '...';

                        echo '
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border-0 shadow-sm h-100">
                                <img src="'.$image.'" class="card-img-top"
                                    alt="'.$title.'"
                                    onerror="this.onerror=null;this.src=\'https://via.placeholder.com/400x250?text=No+Image\';">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-'.$badgeClass.'">'.ucfirst($category).'</span>
                                        <small class="text-muted">'.$date.'</small>
                                    </div>
                                    <h5 class="card-title fw-bold">'.$title.'</h5>
                                    <p class="card-text">'.$excerpt.'</p>
                                    <a href="/seva-main/php/blog-details.php?id='.$blog['id'].'" class="btn btn-outline-primary mt-auto">Read More</a>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p class="text-center">No news available right now.</p>';
                }
                ?>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>
