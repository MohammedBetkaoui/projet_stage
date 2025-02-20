<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] !== 'company') {
    header("Location: ../auth/login/login.php");
    exit;
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/navbar/navbar.php';

?>

<div class="dashboard company">
    <h1>Bienvenue dans l'Espace Entreprise</h1>
    <p>Ici, vous pouvez gérer vos services et offres pour les étudiants.</p>
</div>

<link rel="stylesheet" href="../assets/css/dashboard.css">
