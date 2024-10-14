<?php
// config.php

// Database configurations
define('DB_HOST', 'localhost'); 
define('DB_PORT', '3305'); 
define('DB_NAME', 'news_portal'); 
define('DB_USER', 'root'); 
define('DB_PASS', ''); 

// NewsAPI key
define('NEWS_API_KEY', '51aa71e325704d7f975cd15eaa418b29'); 

// Function to create a PDO connection
function getDbConnection() {
    try {
        // Create PDO instance
        $conn = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn; // Return the PDO instance
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}
?>
