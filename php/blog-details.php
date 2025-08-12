<!-- <?php
require 'db_connect.php';
$id = intval($_GET['id']);
$sql = "SELECT * FROM blogs WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $blog = $result->fetch_assoc();
} else {
    die("Blog not found.");
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($blog['title']); ?></title>
</head>
<body>
    <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
    <p><strong>By:</strong> <?php echo htmlspecialchars($blog['author']); ?> | <strong>Date:</strong> <?php echo date("F j, Y", strtotime($blog['publishDate'])); ?></p>
    <?php if (!empty($blog['image_url'])): ?>
        <img src="<?php echo $blog['image_url']; ?>" style="max-width:100%;">
    <?php endif; ?>
    <div><?php echo $blog['content']; ?></div>
</body>
</html> -->


<?php
require 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid blog ID.");
}

$sql = "SELECT * FROM blogs WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $blog = $result->fetch_assoc();
} else {
    die("Blog not found.");
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($blog['title']); ?> - Blog Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .blog-header {
            background: linear-gradient(to right, #0062E6, #33AEFF 90%);
            padding: 48px 0 32px 0;
            color: white;
            text-align: center;
        }
        .blog-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 2.7rem;
            letter-spacing: -1px;
        }
        .blog-meta-top {
            font-size: 1.08rem;
            font-weight: 500;
            color: #d9ecff;
            margin-bottom: 3px;
        }
        .blog-main-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 32px 0 rgba(40,62,115,0.07);
            padding: 32px;
        }
        .blog-image {
            width: 100%;
            max-height: 360px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 6px 40px 0 rgba(40,62,115,0.11);
        }
        @media (max-width: 991px) {
            .blog-main-card {padding: 16px;}
        }
        .blog-category-badge {
            font-size: 1.03rem;
            font-weight: 600;
            padding: 8px 22px !important;
            border-radius: 28px !important;
            box-shadow: 0 1px 4px 0 rgba(0,0,0,0.07);
            margin-bottom: 22px;
            background: linear-gradient(to right, #12c2e9 55%, #0062E6 100%);
            color: #fff;
            letter-spacing: 0.5px;
            border: none;
            display: inline-block;
        }
        .author-card {
            background: linear-gradient(95deg,#e8f1ff,#ffffff 85%);
            border-radius: 8px;
            padding: 13px 23px 11px 23px;
            display: flex;
            align-items: center;
            margin-bottom: 24px;
            box-shadow: 0 3px 18px 0 rgba(40,62,115,.06);
        }
        .author-icon {
            font-size: 1.3rem;
            color: #2386e6;
            margin-right: 11px;
        }
        .author-name-date {
            font-size: 1.01rem;
            color: #2c3e50;
        }
        .blog-content {
            font-size: 1.08rem;
            line-height: 1.74;
            margin-top: 7px;
            color: #2c2e35;
        }
        .back-link {
            display: inline-block;
            margin-top: 22px;
            text-decoration: none;
            color: #0062E6;
            background: #e8f1ff;
            font-weight: 600;
            padding: 9px 19px;
            border-radius: 23px;
            transition: background 0.15s, color 0.14s;
            box-shadow: 0 2px 12px rgba(79,173,251,0.09);
        }
        .back-link:hover {
            background: #0062E6;
            color: #fff;
            text-decoration: none;
        }
        @media (max-width: 767px) {
            .blog-main-card {padding:10px;}
            .author-card {padding: 9px 11px;}
        }
    </style>
    <!-- Font Awesome for user icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Header -->
    <div class="blog-header">
        <div class="container">
            <div class="blog-meta-top"><?php echo date("F j, Y", strtotime($blog['publishDate'])); ?></div>
            <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
        </div>
    </div>

    <div class="container my-5">
        <div class="row gx-5 gy-5">
            <!-- Left: Image + Back link -->
            <div class="col-lg-5 col-md-6">
                <?php if (!empty($blog['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($blog['image_url']); ?>" class="blog-image" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/500x360.png?text=No+Image" class="blog-image" alt="No Image">
                <?php endif; ?>
                <a href="/Sankat-Saathi/news-stories.php" class="back-link mb-4">
                    <i class="fa fa-arrow-left me-2"></i>Back to Latest News
                </a>
            </div>

            <!-- Right: Category, Author, Content -->
            <div class="col-lg-7 col-md-6">
                <!-- Category badge -->
                <div>
                    <span class="blog-category-badge">
                        <?php echo ucfirst(htmlspecialchars($blog['category'])); ?>
                    </span>
                </div>
                <!-- Author Card -->
                <div class="author-card mb-3">
                    <span class="author-icon"><i class="fa-solid fa-user"></i></span>
                    <span class="author-name-date">
                        <?php echo htmlspecialchars($blog['author']); ?>
                        <span style="color:#888;">|</span>
                        <span><?php echo date("F j, Y", strtotime($blog['publishDate'])); ?></span>
                    </span>
                </div>
                <!-- Blog Content -->
                <div class="blog-content">
                    <?php echo $blog['content']; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
