<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login/login.php");
    exit;
}
?>

<?php include 'navbar.php'; ?>

<div class="dashboard admin">
    <h1>Panneau d'administration</h1>
    <p>Gérez les utilisateurs, les entreprises, et les étudiants depuis cet espace sécurisé.</p>
</div>

<link rel="stylesheet" href="../assets//css/dashboard.css">
