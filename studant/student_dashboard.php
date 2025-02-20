<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] !== 'student') {
    header("Location: ../auth/login/login.php");
    exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/navbar/navbar.php';

?>



<div class="dashboard">
    <h1>Bienvenue dans l'Espace Étudiant</h1>
    <p>Cet espace est dédié aux étudiants pour accéder à leurs services et informations.</p>
</div>

<link rel="stylesheet" href="../assets/css/dashboard.css">

