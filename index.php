<?php  
include 'config.php'; // Include configuration
include 'newsapi_fetch.php'; // Include the script to fetch news
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: users/login.php"); // Redirect to login page
    exit(); // Terminate the script after redirection
}

// Get the PDO connection
$pdo = getDbConnection();

// Determine selected category, default to technology
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'technology';

// Fetch articles from NewsAPI based on selected category
$articles = fetchNewsArticles($selectedCategory);

// Check if there's a search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Filter articles if search query is present
if ($searchQuery) {
    $articles = array_filter($articles, function($article) use ($searchQuery) {
        return stripos($article['title'], $searchQuery) !== false || stripos($article['description'], $searchQuery) !== false;
    });
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech News Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Custom styles -->
</head>

<body>
<?php include 'includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Latest News</h1>

    <!-- Category Selection -->
    <form class="form-inline mb-4" method="GET" action="">
        <select class="form-control mr-2" name="category" onchange="this.form.submit()">
            <option value="technology" <?= $selectedCategory === 'technology' ? 'selected' : '' ?>>Technology</option>
            <option value="business" <?= $selectedCategory === 'business' ? 'selected' : '' ?>>Business</option>
            <option value="entertainment" <?= $selectedCategory === 'entertainment' ? 'selected' : '' ?>>Entertainment</option>
            <option value="health" <?= $selectedCategory === 'health' ? 'selected' : '' ?>>Health</option>
            <option value="science" <?= $selectedCategory === 'science' ? 'selected' : '' ?>>Science</option>
            <option value="sports" <?= $selectedCategory === 'sports' ? 'selected' : '' ?>>Sports</option>
            <option value="general" <?= $selectedCategory === 'general' ? 'selected' : '' ?>>General</option>
        </select>
        <input class="form-control mr-2" type="text" name="search" placeholder="Search articles..." value="<?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?>">
        <button class="btn btn-primary" type="submit">Search</button>
    </form>

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
    <p>&copy; <?= date("Y") ?> Tech News Portal</p>
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
