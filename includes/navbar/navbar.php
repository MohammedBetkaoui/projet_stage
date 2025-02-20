<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['role'] ?? 'guest';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Responsive Navbar</title>
    <link rel="stylesheet" href="/includes/navbar/navbar.css"> 
  </head>
  <body>
 

<nav>
    <div class="logo">
        <h1>LOGO</h1>
    </div>
    <ul>
        <li><a href="../../index.php">Accueil</a></li>
        

        <?php if ($role === 'student'): ?>
            <li><a href="../../studant/student_dashboard.php">Espace Étudiant</a></li>
        <?php elseif ($role === 'company'): ?>
            <li><a href="../../company/company_dashboard.php">Espace Entreprise</a></li>
        <?php elseif ($role === 'admin'): ?>
            <li><a href="../../admin/admin_dashboard.php">Espace Admin</a></li>
        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="../../auth/logout/logout.php">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="../../auth/login/login.php">Connexion</a></li>
        <?php endif; ?>
    </ul>
    <div class="hamburger" onclick="toggleMenu()">
        <span class="line"></span>
        <span class="line"></span>
        <span class="line"></span>
    </div>
</nav>

<div class="menubar" id="mobileMenu" style="display: none;">
    <ul>
        <li><a href="../../index.php">Accueil</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Blog</a></li>
        <li><a href="#">Contact</a></li>
        <?php if ($role !== 'guest'): ?>
            <li><a href="../../<?= $role ?>_dashboard.php">Mon Espace</a></li>
            <li><a href="../../auth/logout/logout.php">Déconnexion</a></li>
        <?php else: ?>
            <li><a href="../../auth/login/login.php">Connexion</a></li>
        <?php endif; ?>
    </ul>
</div>

<script src="/includes/navbar/navbar.js"></script> <!-- Même principe pour le JS -->
</body>
</html>