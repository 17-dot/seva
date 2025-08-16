<?php
require 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    die("Invalid blog ID");
}

$sql = "SELECT * FROM blogs WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows !== 1) {
    die("Blog not found");
}
$blog = $result->fetch_assoc();

$conn->close();

$image_url = !empty($blog['image_url']) ? htmlspecialchars($blog['image_url']) : "images/default-impact.jpeg";
$title = htmlspecialchars($blog['title']);
$category = ucfirst(htmlspecialchars($blog['category']));
$author = htmlspecialchars($blog['author']);
$publish_date = date("F j, Y", strtotime($blog['publishDate']));
$content = $blog['content'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title><?= $title ?> - Blog Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS and fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        .container-fluid {
            padding-left: 2rem;
            padding-right: 2rem;
            max-width: 100vw;
        }

        .blog-header {
            background: linear-gradient(to right, #0062E6, #33AEFF 90%);
            color: white;
            padding: 2.5rem 1rem;
            text-align: center;
        }

        .blog-header h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin: 0;
            word-break: break-word;
        }

        .blog-header .date {
            color: #d9ecff;
            font-weight: 500;
            font-size: 1rem;
            margin-top: 0.3rem;
        }

        .content-area {
            display: flex;
            flex-wrap: wrap;
            margin-top: 2rem;
            gap: 1rem;
            max-width: 100vw;
            box-sizing: border-box;
        }

        .content-area .image-section {
            flex: 1 1 40%;
            display: flex;
            flex-direction: column;
            max-width: 100%;
        }

        .content-area .image-section img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 6px 40px rgba(40, 62, 115, 0.11);
        }

        .content-area .image-section .back-link {
            margin-top: 10px;
            align-self: flex-start;
            font-weight: 600;
            font-size: 1rem;
            color: #0062e6;
            background: #e9f2ff;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(79, 173, 251, 0.15);
            transition: background-color 0.3s, color 0.3s;
            display: inline-block;
        }

        .content-area .image-section .back-link:hover {
            background-color: #0062e6;
            color: white;
            text-decoration: none;
        }

        .content-area .details-section {
            flex: 1 1 55%;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .info-bar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
            padding: 1rem 1.5rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .info-bar .category-badge {
            background: linear-gradient(90deg, #12c2e9 0%, #0062e6 100%);
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 0.5rem 1.5rem;
            border-radius: 30px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
            letter-spacing: 0.5px;
        }

        .info-bar .author-date {
            color: #3b4b5b;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .info-bar .author-date i {
            color: #2386e6;
        }

        .blog-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
            color: #333;
            font-size: 1.15rem;
            line-height: 1.7;
            position: relative;
        }

        .blog-content .back-link-bottom {
            margin-top: 20px;
            display: flex;
            justify-content: flex-start;
        }

        .blog-content .back-link-bottom a {
            font-weight: 600;
            font-size: 1rem;
            color: #0062e6;
            background: #e9f2ff;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            text-decoration: none;
            box-shadow: 0 2px 8px rgba(79, 173, 251, 0.15);
            transition: background-color 0.3s, color 0.3s;
        }

        .blog-content .back-link-bottom a:hover {
            background-color: #0062e6;
            color: white;
            text-decoration: none;
        }

        @media (max-width: 991px) {
            .content-area {
                flex-direction: column;
            }

            .content-area .image-section,
            .content-area .details-section {
                flex: 1 1 100%;
                max-width: 100%;
            }

            .content-area .image-section .back-link {
                align-self: center;
            }

            .blog-content .back-link-bottom {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <div class="blog-header">
        <div class="container-fluid">
            <h1><?= $title ?></h1>
            <div class="date"><?= $publish_date ?></div>
        </div>
    </div>

    <div class="container-fluid content-area">
        <div class="image-section">
            <img src="../<?= $image_url ?>" alt="<?= $title ?>" onerror="this.onerror=null;this.src='../images/default-impact.jpeg';" />
            <a href="/seva-main/news-stories.php" class="back-link"><i class="fas fa-arrow-left me-2"></i>Back to Stories</a>
        </div>

        <div class="details-section">
            <div class="info-bar">
                <span class="category-badge"><?= $category ?></span>
                <div class="author-date">
                    <i class="fas fa-user"></i> <?= $author ?>
                    <span>|</span>
                    <span><?= $publish_date ?></span>
                </div>
            </div>

            <div class="blog-content">
                <?= $content ?>
                <!-- <div class="back-link-bottom">
                    <a href="/seva-main/impact_stories.php"><i class="fas fa-arrow-left me-2"></i>Back to Stories</a>
                </div> -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
