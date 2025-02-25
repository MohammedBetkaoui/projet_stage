<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login/login.php");
    exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php';
?>

<link rel="stylesheet" href="/assets/css/dashboard.css">

<div class="welcome-section">
                    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
                    <p>Gérez vos offres de stage et interagissez avec les étudiants.</p>
                </div>

<link rel="stylesheet" href="../assets//css/dashboard.css">
