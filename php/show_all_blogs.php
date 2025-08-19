<?php
require 'db_connect.php';  // Adjust this to your DB connection file

// Preferred order of categories
$categoryOrder = ['impact-stories', 'news', 'programs', 'events'];

// Fetch all blogs ordered by category and publish date desc
$sql = "SELECT id, title, heading , author, category, publishDate, image_url, content FROM blogs ORDER BY FIELD(category, '" . implode("','", $categoryOrder) . "'), publishDate DESC";
$result = $conn->query($sql);

$blogsByCategory = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cat = $row['category'];
        if (!isset($blogsByCategory[$cat])) {
            $blogsByCategory[$cat] = [];
        }
        $blogsByCategory[$cat][] = $row;
    }
}

$badgeColors = [
    'news' => 'primary',
    'milestone' => 'success',
    'award' => 'warning',
    'expansion' => 'info',
    'event' => 'secondary',
    'fundraising' => 'success',
    'programs' => 'primary',
    'impact-stories' => 'danger',
];
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>All Blogs - Sankat Sathi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body { padding: 1rem; }
        .search-container { max-width: 350px; }
        .back-button { margin-left: auto; }
        .card-img-top { height: 200px; object-fit: cover; }
    </style>
</head>
<body>
    <h1 class="text-center mb-4">All Blogs - Sankat Sathi</h1>

    <div class="d-flex align-items-center mb-4">
        <div class="input-group search-container">
            <input type="text" id="categorySearch" class="form-control" placeholder="Search or select category..." aria-label="Search category" autocomplete="off" />
            <button class="btn btn-outline-secondary" type="button" id="searchGoBtn"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
        <a href="/seva-main/blogs.php" class="btn btn-outline-secondary back-button ms-30">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <div id="blogsContainer">
        <?php if (empty($blogsByCategory)): ?>
            <p class="text-center">No blogs found.</p>
        <?php else: ?>
            <?php foreach ($categoryOrder as $catKey): 
                if (!isset($blogsByCategory[$catKey])) continue;
                $displayName = ucwords(str_replace('-', ' ', $catKey));
            ?>
                <section id="<?= htmlspecialchars($catKey) ?>" class="mb-5">
                    <h3 class="fw-bold text-primary mb-4"><?= htmlspecialchars($displayName) ?></h3>

                    <div class="row">
                        <?php foreach ($blogsByCategory[$catKey] as $blog):
                            $title = htmlspecialchars($blog['title']);
                            $heading = htmlspecialchars($blog['heading']);
                            $author = htmlspecialchars($blog['author']);
                            $catLower = strtolower($blog['category']);
                            $badgeClass = $badgeColors[$catLower] ?? 'primary';
                            $date = date('F j, Y', strtotime($blog['publishDate']));
                            // $image = !empty($blog['image_url']) ? $blog['image_url'] : 'https://via.placeholder.com/400x250?text=No+Image';
                            // If database value is e.g. 'images/download.jpeg'
// And your images directory is at '/seva-main/images/'
// You want <img src="/seva-main/images/download.jpeg">
$image = !empty($blog['image_url'])
    ? '/seva-main/' . ltrim($blog['image_url'], '/')
    : 'https://via.placeholder.com/400x250?text=No+Image';


                            $excerpt = strip_tags($blog['content']);
                            if (strlen($excerpt) > 150) $excerpt = substr($excerpt, 0, 147) . '...';
                        ?>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 shadow-sm border-0">
                                    <!-- <img src="<?= $image ?>" alt="<?= $title ?>" class="card-img-top" /> -->
                                     <img src="<?= htmlspecialchars($image) ?>" alt="<?= $title ?>" class="card-img-top" />

                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-<?= $badgeClass ?>"><?= ucwords(str_replace('-', ' ', $catLower)) ?></span>
                                            <small class="text-muted"><?= $date ?></small>
                                        </div>
                                        <h5 class="card-title fw-bold"><?= $title ?></h5>
                                        <p class="card-text"><?= $excerpt ?></p>
                                        <a href="blog-details.php?id=<?= $blog['id'] ?>" class="btn btn-outline-primary mt-auto">Read More</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        const categoryMap = {
            'impact stories': 'impact-stories',
            'news': 'news',
            'programs': 'programs',
            'events': 'events',
        };

        function findMatchingCategory(query) {
            query = query.toLowerCase().trim();
            for (const key in categoryMap) {
                if (key.includes(query)) {
                    return categoryMap[key];
                }
            }
            return null;
        }

        document.getElementById('searchGoBtn').addEventListener('click', () => {
            const input = document.getElementById('categorySearch');
            const val = input.value;
            if (!val) return;
            const targetId = findMatchingCategory(val);
            if (targetId) {
                const targetSection = document.getElementById(targetId);
                if (targetSection) {
                    targetSection.scrollIntoView({behavior: 'smooth'});
                    // Highlight effect
                    targetSection.style.transition = 'background-color 0.5s ease';
                    const prevBg = targetSection.style.backgroundColor;
                    targetSection.style.backgroundColor = '#ffff99';
                    setTimeout(() => {
                        targetSection.style.backgroundColor = prevBg;
                    }, 1500);
                }
            }
        });

        // Optional: Also support enter key in input box
        document.getElementById('categorySearch').addEventListener('keyup', e => {
            if (e.key === 'Enter') {
                document.getElementById('searchGoBtn').click();
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
