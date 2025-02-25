<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['role'] ?? 'guest';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Stage</title>
    <link rel="stylesheet" href="/includes/navbar/navbar.css"> 
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> <!-- Boxicons pour les icônes -->
</head>
<body>
    <nav>
        <div class="logo">
            <h1>StageFinder</h1>
        </div>

        <!-- Menu desktop -->
        <ul class="desktop-menu">
            <li><a href="/home/index.php"><i class='bx bx-home'></i> Accueil</a></li>
            <li><a href="/home/propos/propos.php"><i class='bx bx-info-circle'></i> À propos</a></li>
            <li><a href="/home/offres.php"><i class='bx bx-info-circle'></i> Offres</a></li>

            <?php if ($role === 'student'): ?>
                <li><a href="../../studant/home_dashboard/student_dashboard.php"><i class='bx bx-user'></i> Espace Étudiant</a></li>
            <?php elseif ($role === 'company'): ?>
                <li><a href="../../company/company_dashboard.php"><i class='bx bx-briefcase'></i> Espace Entreprise</a></li>
            <?php elseif ($role === 'admin'): ?>
                <li><a href="../../admin/admin_dashboard.php"><i class='bx bx-shield'></i> Espace Admin</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="../../auth/logout/logout.php"><i class='bx bx-log-out'></i> Déconnexion</a></li>
            <?php else: ?>
                <li><a href="../../auth/login/login.php"><i class='bx bx-log-in'></i> Connexion</a></li>
            <?php endif; ?>
        </ul>

        <!-- Bouton hamburger pour mobile -->
        <div class="hamburger" onclick="toggleMenu()">
            <span class="line">|</span>
            <span class="line">|</span>
            <span class="line">|</span>
        </div>

        <!-- Menu mobile -->
        <div class="menubar" id="mobileMenu">
            <ul>
                <li><a href="/home/index.php"><i class='bx bx-home'></i> Accueil</a></li>
                <li><a href="/home/propos/propos.php"><i class='bx bx-info-circle'></i> À propos</a></li>
                <li><a href="/home/offres.php"><i class='bx bx-info-circle'></i> Offres</a></li>

                <?php if ($role === 'student'): ?>
                    <li><a href="../../studant/home_dashboard/student_dashboard.php"><i class='bx bx-user'></i> Espace Étudiant</a></li>
                <?php elseif ($role === 'company'): ?>
                    <li><a href="../../company/company_dashboard.php"><i class='bx bx-briefcase'></i> Espace Entreprise</a></li>
                <?php elseif ($role === 'admin'): ?>
                    <li><a href="../../admin/admin_dashboard.php"><i class='bx bx-shield'></i> Espace Admin</a></li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="../../auth/logout/logout.php"><i class='bx bx-log-out'></i> Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="../../auth/login/login.php"><i class='bx bx-log-in'></i> Connexion</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Overlay pour mobile -->
        <div class="overlay" id="overlay" onclick="toggleMenu()"></div>
    </nav>

    <script src="/includes/navbar/navbar.js"></script> <!-- Script pour le toggle -->
</body>
</html>