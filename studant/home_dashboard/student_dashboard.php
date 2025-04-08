<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté et est un étudiant
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login/login.php');
    exit();
}
if ($_SESSION['role'] !== 'student') {
    header('Location: /home/index.php');
    exit();
}

$student_id = $_SESSION['user_id'];

// Récupérer les candidatures de l'étudiant
$applications = [];
try {
    $stmt = $conn->prepare("
        SELECT a.id, a.status, a.applied_at, o.title, o.company_id, c.full_name AS company_name
        FROM applications a
        JOIN offers o ON a.offer_id = o.id
        JOIN users c ON o.company_id = c.id
        WHERE a.student_id = ?
        ORDER BY a.applied_at DESC
    ");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applications = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $error = "Erreur lors de la récupération des candidatures : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Étudiant</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css"> <!-- Lien vers le fichier CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Sidebar -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php'; ?>

    <!-- Contenu principal -->
    <section class="main-content">
        <div class="dashboard student">
            <div class="container">
                <!-- Section de bienvenue -->
                <div class="welcome-section">
                    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
                    <p>Gérez vos candidatures et suivez leur statut.</p>
                </div>

                <!-- Section des candidatures -->
                <div class="applications-section">
                    <h2>Mes candidatures</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert error">
                            <i class='bx bx-error-circle'></i>
                            <span><?php echo $error; ?></span>
                        </div>
                    <?php elseif (empty($applications)): ?>
                        <div class="alert info">
                            <i class='bx bx-info-circle'></i>
                            <span>Vous n'avez postulé à aucune offre pour le moment.</span>
                        </div>
                    <?php else: ?>
                        <div class="applications-list">
                            <?php foreach ($applications as $application): ?>
                                <div class="application-card">
                                    <div class="application-info">
                                        <h3><?php echo htmlspecialchars($application['title']); ?></h3>
                                        <p>Entreprise : <?php echo htmlspecialchars($application['company_name']); ?></p>
                                        <p>Date de candidature : <?php echo date('d/m/Y', strtotime($application['applied_at'])); ?></p>
                                    </div>
                                    <div class="application-status <?php echo strtolower($application['status']); ?>">
                                        <span><?php echo htmlspecialchars($application['status']); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</body>
</html>