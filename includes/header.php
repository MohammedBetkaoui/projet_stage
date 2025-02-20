<?php
require_once 'config.php';
require_once 'auth.php';

$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Plateforme de Stages' ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
</head>
<body>
    <nav class="main-nav">
        <div class="nav-container">
            <a href="/" class="logo">Stages<span>Pro</span></a>
            
            <div class="nav-links">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['role'] === 'student'): ?>
                        <a href="/student/dashboard.php" <?= $currentPage === 'dashboard.php' ? 'class="active"' : '' ?>>Tableau de bord</a>
                        <a href="/student/applications.php" <?= $currentPage === 'applications.php' ? 'class="active"' : '' ?>>Mes candidatures</a>
                    <?php elseif ($_SESSION['role'] === 'company'): ?>
                        <a href="/company/dashboard.php" <?= $currentPage === 'dashboard.php' ? 'class="active"' : '' ?>>Offres</a>
                        <a href="/company/applications.php" <?= $currentPage === 'applications.php' ? 'class="active"' : '' ?>>Candidatures</a>
                    <?php endif; ?>
                    <a href="/auth/logout.php" class="logout">Déconnexion</a>
                <?php else: ?>
                    <a href="/auth/login.php" <?= $currentPage === 'login.php' ? 'class="active"' : '' ?>>Connexion</a>
                    <a href="/auth/register.php" <?= $currentPage === 'register.php' ? 'class="active"' : '' ?>>Inscription</a>
                <?php endif; ?>
            </div>
            
            <button class="mobile-menu-btn">☰</button>
        </div>
    </nav>

    <main class="container">