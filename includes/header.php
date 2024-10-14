<?php  
// Ensure the session is started if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/news-portal/index.php">Tech News Portal</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="/news-portal/index.php"><i class="fas fa-home"></i> Home</a>
            </li>
            <!-- Individual Category Links -->
            <li class="nav-item">
                <a class="nav-link" href="/news-portal/category.php?category=technology"><i class="fas fa-laptop-code"></i> Technology</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/news-portal/category.php?category=science"><i class="fas fa-flask"></i> Science</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/news-portal/category.php?category=sports"><i class="fas fa-futbol"></i> Sports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/news-portal/category.php?category=entertainment"><i class="fas fa-film"></i> Entertainment</a>
            </li>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link" href="/news-portal/users/profile.php"><i class="fas fa-user"></i> Profile</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="logout()"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="/news-portal/users/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/news-portal/users/register.php"><i class="fas fa-user-plus"></i> Register</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<!-- Include Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function logout() {
        if (confirm("Are you sure you want to logout?")) {
            window.location.href = "/news-portal/users/logout.php";
        }
    }
</script>
