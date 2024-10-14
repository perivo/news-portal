<?php 
// users/profile.php

session_start();
include '../includes/functions.php'; // Include functions

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// User ID from session
$userId = $_SESSION['user_id'];

try {
    $conn = getDbConnection(); // Get the PDO connection
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching user data: " . $e->getMessage());
}

// Handle profile update and logout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['logout'])) {
        session_destroy(); // Destroy the session
        header("Location: login.php"); // Redirect to login page
        exit();
    }

    // Handle profile update code here
    if (isset($_POST['update_profile'])) {
        // Sanitize and validate input
        $username = sanitizeInput($_POST['username']);
        $email = sanitizeInput($_POST['email']);
        $oldPassword = $_POST['old_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Password validation and updating logic
        if (checkPassword($userId, $oldPassword)) {
            if ($newPassword === $confirmPassword) {
                // Update user profile and password
                updateUserProfile($userId, $username, $email, $newPassword);
                $successMessage = "Profile updated successfully.";
            } else {
                $error = "New passwords do not match.";
            }
        } else {
            $error = "Old password is incorrect.";
        }
    }

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];

        // Validate file type and size
        if (in_array($fileType, $allowedFileTypes) && $fileSize < 5000000) { // 5 MB limit
            $newFileName = uniqid() . '-' . basename($fileName);
            $uploadFileDir = '../uploads/profile_pics/';
            $dest_path = $uploadFileDir . $newFileName;

            // Move the file to the uploads directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Update user profile picture in the database
                updateProfilePicture($userId, $newFileName);
                $successMessage = "Profile picture updated successfully.";
            } else {
                $error = "There was an error uploading the file.";
            }
        } else {
            $error = "Only JPG, PNG, and GIF files are allowed, and the file size must be less than 5 MB.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Custom styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background */
        }
        .container {
            max-width: 600px; /* Limit container width */
            margin-top: 50px; /* Center vertically */
        }
        .profile-card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            background-color: white; /* White background for form */
        }
        .profile-pic {
            max-width: 150px; /* Limit profile picture size */
            border-radius: 50%; /* Circle profile picture */
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    <?php include '../includes/header.php'; ?>

    <div class="container">
        <div class="profile-card">
            <h2 class="text-center">Welcome, <?= htmlspecialchars($user['username']); ?>!</h2>
            <div class="text-center mb-3">
    <img src="../uploads/profile_pics/<?= htmlspecialchars($user['profile_picture'] ?? 'default.png'); ?>" 
         alt="Profile Picture" class="profile-pic">
</div>


            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($successMessage); ?></div>
            <?php endif; ?>

            <form method="POST" action="profile.php" class="mt-4" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="old_password">Old Password:</label>
                    <input type="password" class="form-control" name="old_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" name="new_password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" class="form-control" name="confirm_password">
                </div>
                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <input type="file" class="form-control" name="profile_picture" accept="image/*">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary btn-block">Update Profile</button>
            </form>
            
            <form method="POST" action="profile.php" class="mt-3">
                <button type="submit" name="logout" class="btn btn-danger btn-block">Logout</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
