<?php

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../../../index.php');
    exit();
}


// Récupérer le rôle de l'utilisateur
$role = $_SESSION['role'];
$auth = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/includes/sidebar/sidebar.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <title>Sidebar</title>
</head>
<body>
    <nav class="sidebar close">
        <header>
            <div class="image-text">
                <span class="image">
                    <!--<img src="logo.png" alt="">-->
                </span>
                <div class="text logo-text">
                    <span class="name">Bonjour</span>
                    <span class="profession"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </div>
            </div>
            <i class='bx bx-chevron-right toggle'></i>
        </header>
        <div class="menu-bar">
            <div class="menu">
                
                <ul class="menu-links">
                    <li class="nav-link">
                        <a href="/home/index.php">
                            <i class='bx bx-globe icon'></i>
                            <span class="text nav-text">Accueil</span>
                        </a>
                    </li>
                    <li class="nav-link">
                        <a href="/company/company_dashboard.php">
                            <i class='bx bx-home-alt icon'></i>
                            <span class="text nav-text">Tableau de bord</span>
                        </a>
                    </li>

                    <?php if ($role === 'company'): ?>
                        <li class="nav-link">
                            <a href="../../company/offre/ajouter_offre.php">
                                <i class='bx bx-plus-circle icon'></i>
                                <span class="text nav-text">Publier une offre</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="../../company/get_offres/mes_offres.php">
                                <i class='bx bx-list-ul icon'></i>
                                <span class="text nav-text">Mes offres</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/company/condidature/condidature.php">
                                <i class='bx bx-file icon'></i>
                                <span class="text nav-text">Mes candidatures</span>
                            </a>
                        </li>
                    <?php elseif ($role === 'student'): ?>
                        <li class="nav-link">
                            <a href="#">
                                <i class='bx bx-file icon'></i>
                                <span class="text nav-text">Mes candidatures</span>
                            </a>
                        </li>
                        <li class="nav-link">
                            <a href="/student/offres_disponibles.php">
                                <i class='bx bx-briefcase icon'></i>
                                <span class="text nav-text">Offres disponibles</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-link">
                        <a href="/notifications.php">
                            <i class='bx bx-bell icon'></i>
                            <span class="text nav-text">Notifications</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <li class="">
                    <?php if($auth){  ?>
                    <a href="../../auth/logout/logout.php">
                        <i class='bx bx-log-out icon'></i>
                        <span class="text nav-text">Déconnexion</span>
                    </a>
                    <?php } ?>
                </li>
               
            </div>
        </div>
    </nav>
    <section class="home">
        <div></div>
    </section>
    <script src="/includes/sidebar/sidebar.js"></script>
    
</body>
</html>