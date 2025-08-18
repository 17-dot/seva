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
            padding: 35px 0 32px 0;

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


        .blog-category-badge {
            font-size: 1.03rem;
            font-weight: 600;
            padding: 8px 22px !important;
            border-radius: 28px !important;
            box-shadow: 0 1px 4px 0 rgba(0,0,0,0.07);
            background: linear-gradient(to right, #12c2e9 55%, #0062E6 100%);
            color: #fff;
            letter-spacing: 0.5px;
            border: none;
            display: inline-block;
        }
        /* Small circular back icon */
        .btn-back-icon-sm {
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            background-color: #fff;
            border: 2px solid #0d6efd;
            color: #0d6efd;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: all 0.2s ease-in-out;
            text-decoration: none;
        }
        .btn-back-icon-sm:hover {
            background-color: #0d6efd;
            color: #fff;
            transform: scale(1.08);
            text-decoration: none;
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
    </style>
    <!-- Font Awesome -->

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
            <!-- Left: Image -->
            <div class="col-lg-5 col-md-6">
                <?php
                $image_url = !empty($blog['image_url']) ? htmlspecialchars($blog['image_url']) : "images/default-impact-stories.jpeg";
                $title = htmlspecialchars($blog['title']);
                ?>
                <img src="../<?php echo $image_url; ?>"
                     class="blog-image"
                     alt="<?php echo $title; ?>"
                     onerror="this.onerror=null;this.src='../images/default-news.jpg';">
            </div>

            <!-- Right: Category + Back icon, Author, Content -->
            <div class="col-lg-7 col-md-6">

                <!-- Category badge row with back icon -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="blog-category-badge">
                        <?php echo ucfirst(htmlspecialchars($blog['category'])); ?>
                    </span>
                    <a href="/seva-main/news-stories.php" class="btn-back-icon-sm" title="Back">
                        <i class="fas fa-arrow-left"></i>
                    </a>
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
