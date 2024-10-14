<?php  
include '../config.php'; // Include configuration
session_start(); // Start the session

// Get the PDO connection
$pdo = getDbConnection();

// Fetch user's liked articles from the database
$likedArticles = [];
if (isset($_SESSION['user_id'])) {
    // Join likes with articles to get the title
    $stmt = $pdo->prepare("
        SELECT l.article_id, l.article_url, a.title AS article_title 
        FROM likes l 
        JOIN articles a ON l.article_id = a.id 
        WHERE l.user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $likedArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Please log in to view liked articles.";
}

// Handle delete action for likes
if (isset($_GET['delete'])) {
    if (isset($_SESSION['user_id'])) {
        $articleId = $_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = ? AND article_id = ?");
        $stmt->execute([$_SESSION['user_id'], $articleId]);
        header("Location: mylikes.php"); // Redirect after deletion
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liked Articles</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Custom styles -->
</head>

<body>
<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h1 class="text-center">Liked Articles</h1>

    <div class="row">
        <?php if (!empty($likedArticles)): ?>
            <?php foreach ($likedArticles as $article): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($article['article_title'], ENT_QUOTES, 'UTF-8') ?></h5>
                            <a href="<?= htmlspecialchars($article['article_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" class="btn btn-primary">Read Article</a>
                            <a href="?delete=<?= urlencode($article['article_id']) ?>" class="btn btn-danger">Remove Like</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No liked articles available.</p>
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
