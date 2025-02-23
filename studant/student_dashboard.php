<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($_SESSION['role'] !== 'student') {
    header("Location: ../auth/login/login.php");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; 

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
                    <h1>Bienvenue dans l'Espace Étudiant <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
                    <p>Cet espace est dédié aux étudiants pour accéder à leurs services et informations.</p>
                    
    
                </div>

        </div>
        </div>
    </section>

   
</body>
</html>