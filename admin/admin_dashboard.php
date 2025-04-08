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




<link rel="stylesheet" href="../assets//css/dashboard.css">
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Entreprise</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css"> <!-- Lien vers le fichier CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <!-- Sidebar -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php'; ?>

    <!-- Contenu principal -->
    <section class="main-content">
        <div class="dashboard company">
            <div class="container">
                <!-- Section de bienvenue -->
                <div class="welcome-section">
                    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
                    <p>Gérez la platforme tenque admin</p>
                </div>

                <!-- Statistiques rapides -->

                <div class="quick-stats">
                    <div class="quick-stats">
                        <div class="stat-card" onclick="window.location.href='offers/offer.php'"> <!-- Lien vers la page des offres -->
                            <i class='bx bx-file'></i>
                            <h3>Offres publiées</h3>
                            <p>nombre offres : <?php 
                            $nbroffer = "SELECT count(*)FROM offers ";
                            $nbroffer = mysqli_query($conn,$nbroffer);
                            $nbrusers = mysqli_fetch_assoc($nbroffer);
                            echo $nbrusers['count(*)']; ;
                            ?></p>
                        </div>
                    </div>

                    <a href="/admin/users/users.php">
                        <div class="stat-card">
                            <i class='bx bx-user-check'></i>
                            <h3>utilisateur</h3>
                            <p >Nombre les utilisateur : 
                                <?php $nbrusers = "SELECT count(*) FROM users ";
                                $nbrusers = mysqli_query($conn, $nbrusers);
                                $nbrusers = mysqli_fetch_assoc($nbrusers);
                                echo $nbrusers['count(*)'];
                                ?></p> <!-- À remplacer par un nombre dynamique depuis la base de données -->

                        </div>
                    </a>
                    
                </div>

                <!-- Section des actions rapides -->
                <div class="quick-actions">
                    <h2>Actions rapides</h2>
                    <div class="action-buttons">
                        <a href="/admin/branch_function/ajoutebranch.php" class="btn btn-primary">
                            <i class='bx bx-plus'></i> ajoute_branch
                        </a>
                        <a href="/admin/branch_function/ajoutebranch.php" class="btn btn-secondary">
                            <i class='bx bx-list-ul'></i> Voir mes branch
                        </a>
                        <a href="#" class="btn btn-tertiary">
                            <i class='bx bx-bell'></i> Voir les notifications
                        </a>

                    </div>
                </div>


            </div>
        </div>
    </section>

    <script src="admin_dashbord.js"></script>
</body>

</html>