<?php
// article.php

include 'config.php'; // Include configuration
include 'includes/db.php'; // Include database connection

// Get article ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php'); // Redirect to homepage if no ID is provided
    exit;
}

$articleId = (int)$_GET['id']; // Ensure ID is treated as an integer

// Fetch article details from the database
$stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
$stmt->execute([$articleId]);
$article = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    die("Article not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="assets/css/styles.css"> <!-- Custom styles -->
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Tech News Portal</a>
</nav>

<div class="container mt-5">
    <h1><?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <?php if (!empty($article['image_url'])): ?>
        <img src="<?= htmlspecialchars($article['image_url'], ENT_QUOTES, 'UTF-8') ?>" class="img-fluid" alt="Article Image">
    <?php endif; ?>
    <p class="mt-4"><?= nl2br(htmlspecialchars($article['description'], ENT_QUOTES, 'UTF-8')) ?></p>
    <a href="<?= htmlspecialchars($article['url'], ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary mt-3" target="_blank">Read Full Article</a>
    <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
</div>

<footer class="text-center mt-4">
    <p>&copy; <?= date("Y") ?> Tech News Portal</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
