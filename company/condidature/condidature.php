<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté et est une entreprise
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login/login.php');
    exit();
}
if ($_SESSION['role'] !== 'company') {
    header('Location: /home/index.php');
    exit();
}

$company_id = $_SESSION['user_id'];

// Récupérer les candidatures pour les offres de l'entreprise
$applications = [];
try {
    $stmt = $conn->prepare("
        SELECT a.id AS application_id, a.status, a.applied_at, a.cv_path, a.cover_letter,
               o.title AS offer_title, o.id AS offer_id,
               u.full_name AS student_name, u.email AS student_email
        FROM applications a
        JOIN offers o ON a.offer_id = o.id
        JOIN users u ON a.student_id = u.id
        WHERE o.company_id = ?
        ORDER BY a.applied_at DESC
    ");
    $stmt->bind_param("i", $company_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $applications = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $error = "Erreur lors de la récupération des candidatures : " . $e->getMessage();
}
?><!DOCTYPE html>
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
                    <p>Gérez les candidatures reçues pour vos offres de stage.</p>
                </div>

                <!-- Section des candidatures -->
                <div class="applications-section">
                    <h2>Candidatures reçues</h2>
                    <?php if (isset($error)): ?>
                        <div class="alert error">
                            <i class='bx bx-error-circle'></i>
                            <span><?php echo $error; ?></span>
                            <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                        </div>
                    <?php elseif (empty($applications)): ?>
                        <div class="alert info">
                            <i class='bx bx-info-circle'></i>
                            <span>Aucune candidature reçue pour le moment.</span>
                        </div>
                    <?php else: ?>
                        <div class="applications-list">
                            <?php foreach ($applications as $application): ?>
                                <div class="application-card">
                                    <div class="application-info">
                                        <h3><?php echo htmlspecialchars($application['offer_title']); ?></h3>
                                        <p><strong>Candidat:</strong> <?php echo htmlspecialchars($application['student_name']); ?></p>
                                        <p><strong>Email:</strong> <?php echo htmlspecialchars($application['student_email']); ?></p>
                                        <p><strong>Date de candidature:</strong> <?php echo date('d/m/Y', strtotime($application['applied_at'])); ?></p>
                                        <p><strong>Lettre de motivation:</strong> <?php echo htmlspecialchars($application['cover_letter']); ?></p>
                                    </div>
                                    <div class="application-actions">
                                        <div class="application-status <?php echo strtolower($application['status']); ?>">
                                            <span><?php echo htmlspecialchars($application['status']); ?></span>
                                        </div>
                                        <a href="<?php echo $application['cv_path']; ?>" class="btn download-btn" download>
                                            <i class='bx bx-download'></i> Télécharger le CV
                                        </a>
                                        <form method="POST" action="./update_status.php" class="status-form">
                                            <input type="hidden" name="application_id" value="<?php echo $application['application_id']; ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="pending" <?php echo $application['status'] === 'pending' ? 'selected' : ''; ?>>En attente</option>
                                                <option value="accepted" <?php echo $application['status'] === 'accepted' ? 'selected' : ''; ?>>Acceptée</option>
                                                <option value="rejected" <?php echo $application['status'] === 'rejected' ? 'selected' : ''; ?>>Rejetée</option>
                                            </select>
                                        </form>
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