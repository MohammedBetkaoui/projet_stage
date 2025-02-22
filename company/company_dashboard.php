<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] !== 'company') {
    header("Location: ../auth/login/login.php");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/company/functions/offres/get_offres.php'; // Inclure la fonction pour récupérer les offres

// Récupérer les offres de l'entreprise connectée
try {
    $offers = getOffers($conn, $_SESSION['user_id']); // Récupérer les offres de l'entreprise
    $numberOfOffers = count($offers); // Nombre d'offres publiées
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

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
                    <p>Gérez vos offres de stage et interagissez avec les étudiants.</p>
                </div>

                <!-- Statistiques rapides -->
                <div class="quick-stats">
                    <div class="stat-card">
                        <i class='bx bx-file'></i>
                        <h3>Offres publiées</h3>
                        <p><?php echo $numberOfOffers; ?></p> <!-- Nombre d'offres dynamique -->
                    </div>
                    <div class="stat-card">
                        <i class='bx bx-user-check'></i>
                        <h3>Candidatures reçues</h3>
                        <p>12</p> <!-- À remplacer par un nombre dynamique depuis la base de données -->
                    </div>
                    <div class="stat-card">
                        <i class='bx bx-calendar'></i>
                        <h3>Stages en cours</h3>
                        <p>3</p> <!-- À remplacer par un nombre dynamique depuis la base de données -->
                    </div>
                </div>

                <!-- Section des actions rapides -->
                <div class="quick-actions">
                    <h2>Actions rapides</h2>
                    <div class="action-buttons">
                        <a href="../../company/offre/ajouter_offre.php" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Publier une offre
                        </a>
                        <a href="../../company/get_offres/mes_offres.php" class="btn btn-secondary">
                            <i class='bx bx-list-ul'></i> Voir mes offres
                        </a>
                        <a href="/notifications.php" class="btn btn-tertiary">
                            <i class='bx bx-bell'></i> Voir les notifications
                        </a>
                    </div>
                </div>

                <!-- Section des dernières candidatures -->
                <div class="recent-applications">
                    <h2>Dernières candidatures</h2>
                    <div class="applications-list">
                        <div class="application-card">
                            <div class="application-info">
                                <h4>John Doe</h4>
                                <p>Stage Développeur Web</p>
                                <span class="status pending">En attente</span>
                            </div>
                            <a href="#" class="btn btn-small">Voir le CV</a>
                        </div>
                        <div class="application-card">
                            <div class="application-info">
                                <h4>Jane Smith</h4>
                                <p>Stage Marketing Digital</p>
                                <span class="status accepted">Acceptée</span>
                            </div>
                            <a href="#" class="btn btn-small">Voir le CV</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   
</body>
</html>