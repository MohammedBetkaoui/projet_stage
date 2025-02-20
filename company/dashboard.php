<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

authenticate();
checkRole(['company']);

// Récupérer les offres de l'entreprise
try {
    $stmt = $pdo->prepare("SELECT 
        o.*, 
        COUNT(a.id) as applications_count
        FROM offers o
        LEFT JOIN applications a ON o.id = a.offer_id
        WHERE o.company_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC");
    
    $stmt->execute([$_SESSION['user_id']]);
    $offers = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord Entreprise</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Bienvenue <?= htmlspecialchars($_SESSION['full_name']) ?></h1>

        <div class="dashboard-actions">
            <a href="create_offer.php" class="btn">+ Nouvelle offre</a>
            <a href="offers.php" class="btn">Voir toutes les offres</a>
        </div>

        <h2>Vos dernières offres</h2>
        
        <div class="offers-grid">
            <?php foreach ($offers as $offer): ?>
            <div class="offer-card">
                <h3><?= htmlspecialchars($offer['title']) ?></h3>
                <div class="meta">
                    <span class="sector"><?= htmlspecialchars($offer['sector']) ?></span>
                    <span class="location">📍 <?= htmlspecialchars($offer['location']) ?></span>
                </div>
                <div class="stats">
                    <div class="stat">
                        <span class="number"><?= $offer['applications_count'] ?></span>
                        <span class="label">Candidatures</span>
                    </div>
                    <div class="actions">
                        <a href="offer_applications.php?id=<?= $offer['id'] ?>" class="btn small">Voir</a>
                        <a href="edit_offer.php?id=<?= $offer['id'] ?>" class="btn small">Éditer</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>