<?php    
include '../config.php'; // Include configuration
session_start(); // Start the session

// Get the PDO connection
$pdo = getDbConnection();

// Fetch user's bookmarked articles from the database
$bookmarkedArticles = [];
if (isset($_SESSION['user_id'])) {
    // Join bookmarks with articles to get the title
    $stmt = $pdo->prepare("
        SELECT b.article_id, b.article_url, a.title AS article_title 
        FROM bookmarks b 
        JOIN articles a ON b.article_id = a.id 
        WHERE b.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $bookmarkedArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Please log in to view your bookmarks.";
}

// Handle delete action for bookmarks
if (isset($_GET['delete'])) {
    if (isset($_SESSION['user_id'])) {
        $articleId = $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE user_id = ? AND article_id = ?");
        $stmt->execute([$_SESSION['user_id'], $articleId]);
        header("Location: mybookmarks.php"); // Redirect after deletion
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookmarked Articles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Custom styles -->
</head>

<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Bookmarked Articles</h1>

    <div class="row">
        <?php if (!empty($bookmarkedArticles)): ?>
            <?php foreach ($bookmarkedArticles as $article): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($article['article_title'], ENT_QUOTES, 'UTF-8') ?></h5>
                            <a href="<?= htmlspecialchars($article['article_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-primary">Read Article</a>
                            <a href="?delete=<?= urlencode($article['article_id']) ?>" class="btn btn-danger">Delete Bookmark</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No bookmarked articles available.</p>
        <?php endif; ?>
    </div>
</div>

<footer class="text-center mt-4">
    <p>&copy; <?= date("Y") ?> Tech News Portal</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
