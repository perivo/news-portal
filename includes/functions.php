<?php
// includes/functions.php

include_once 'db.php'; // Ensure database connection is included

/**
 * Fetch the latest articles (limit by number)
 *
 * @param int $limit
 * @return array
 */
function fetchLatestArticles($limit) {
    global $pdo; // Use the $pdo variable instead of $conn
    $sql = "SELECT * FROM articles ORDER BY created_at DESC LIMIT :limit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); // Use bindValue for constants
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetch article by ID
 *
 * @param int $id
 * @return array|null
 */
function getArticleById($id) {
    global $pdo; // Use the $pdo variable instead of $conn
    $sql = "SELECT * FROM articles WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * User login function
 *
 * @param string $email
 * @param string $password
 * @return array|bool
 */
function userLogin($email, $password) {
    global $pdo; // Use the $pdo variable instead of $conn
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if password matches
    if ($user && password_verify($password, $user['password'])) {
        return $user; // Return user data on success
    } else {
        return false; // Return false on failure
    }
}

/**
 * Register new user
 *
 * @param string $username
 * @param string $email
 * @param string $password
 * @return bool
 */
function registerUser($username, $email, $password) {
    global $pdo; // Use the $pdo variable instead of $conn
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
    return $stmt->execute();
}

/**
 * Fetch all articles from the database.
 *
 * @return array
 */
function getAllArticles() {
    global $pdo; // Use the $pdo variable instead of $conn
    $sql = "SELECT * FROM articles ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Sanitize user input to prevent XSS attacks.
 *
 * @param string $data
 * @return string
 */
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Admin login function
 *
 * @param string $email
 * @param string $password
 * @return array|bool
 */
function adminLogin($email, $password) {
    global $pdo; // Use the $pdo variable instead of $conn
    $sql = "SELECT * FROM admins WHERE email = :email"; // Use PDO for consistency
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if password matches
    if ($admin && password_verify($password, $admin['password'])) {
        return $admin; // Return admin data on success
    } else {
        return false; // Return false on failure
    }
}

function checkPassword($userId, $password) {
    $conn = getDbConnection(); // Get the PDO connection
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Assuming passwords are hashed
        return password_verify($password, $user['password']);
    }
    return false;
}

function updateUserProfile($userId, $username, $email, $newPassword = null) {
    $conn = getDbConnection(); // Get the PDO connection
    $sql = "UPDATE users SET username = ?, email = ?" . ($newPassword ? ", password = ?" : "") . " WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if ($newPassword) {
        // Hash the new password before updating
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->execute([$username, $email, $hashedPassword, $userId]);
    } else {
        $stmt->execute([$username, $email, $userId]);
    }
}

function updateProfilePicture($userId, $newFileName) {
    $conn = getDbConnection(); // Get the PDO connection
    $sql = "UPDATE users SET profile_picture = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$newFileName, $userId]);
}

?>
