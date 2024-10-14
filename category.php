<?php 
include 'config.php'; // Include configuration
include 'newsapi_fetch.php'; // Include the script to fetch news
session_start(); // Start the session

// Get the PDO connection
$pdo = getDbConnection();

// Get the category from the URL, default to 'general' if not provided
$category = isset($_GET['category']) ? $_GET['category'] : 'general';

// Fetch articles from NewsAPI based on the selected category
$articles = fetchNewsArticles($category);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($category) ?> News</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Custom styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- FontAwesome -->
</head>

<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center"><?= ucfirst($category) ?> News</h1>

    <div class="row">
        <?php if (!empty($articles)): ?>
            <?php foreach ($articles as $article): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($article['imageUrl'])): ?>
                            <img src="<?= htmlspecialchars($article['imageUrl'], ENT_QUOTES, 'UTF-8') ?>" class="card-img-top" alt="Article Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></h5>
                            <p class="card-text"><?= htmlspecialchars($article['description'], ENT_QUOTES, 'UTF-8') ?></p>
                            <a href="<?= htmlspecialchars($article['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-primary">Read More</a>
                            <button class="btn btn-outline-info" onclick="copyLink('<?= htmlspecialchars($article['url'], ENT_QUOTES, 'UTF-8') ?>')">
                                <i class="fas fa-link"></i> Share
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No articles available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center mt-4">
    <p>&copy; <?= date("Y") ?> News Portal</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function copyLink(url) {
        navigator.clipboard.writeText(url).then(function() {
            alert("Link copied to clipboard!");
        }, function(err) {
            console.error("Could not copy text: ", err);
        });
    }
</script>
</body>
</html>
