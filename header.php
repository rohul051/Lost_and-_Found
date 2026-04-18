<!DOCTYPE html>
<html>
<head>
    <title>Lost & Found System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/project/style.css" rel="stylesheet">
</head>
<body>
<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/project/dashboard.php">Lost & Found</a>

        <div class="d-flex align-items-center gap-2">
            <button id="theme-toggle" class="theme-toggle" title="Toggle theme">
                <i class="fas fa-moon"></i>
            </button>
            <?php if($user): ?>
                <a href="/project/user/dashboard.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                </a>
                <a href="/project/authentication/logout.php" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            <?php else: ?>
                <a href="/project/authentication/login.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-sign-in-alt me-1"></i>Login
                </a>
                <a href="/project/authentication/register.php" class="btn btn-warning btn-sm">
                    <i class="fas fa-user-plus me-1"></i>Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-4">
