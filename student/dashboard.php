<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

authenticate();
checkRole(['student']);

// Récupérer les offres récentes
try {
    $stmt = $pdo->prepare("SELECT 
        o.*, 
        u.full_name as company_name,
        (SELECT COUNT(*) FROM applications a WHERE a.offer_id = o.id AND a.student_id = ?) as has_applied
        FROM offers o
        JOIN users u ON o.company_id = u.id
        WHERE o.end_date >= CURDATE()
        ORDER BY o.created_at DESC
        LIMIT 6");
    
    $stmt->execute([$_SESSION['user_id']]);
    $offers = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
<?php include '../includes/header.php'; ?>

<div class="dashboard-container">
    <h1>Bienvenue <?= escapeHtml($_SESSION['full_name']) ?></h1>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>Mes candidatures</h3>
            <p><?= getApplicationCount($_SESSION['user_id']) ?></p>
            <a href="applications.php" class="btn">Voir</a>
        </div>
        
        <div class="stat-card">
            <h3>Offres disponibles</h3>
            <p><?= getTotalActiveOffers() ?></p>
            <a href="/offers.php" class="btn">Explorer</a>
        </div>
    </div>

    <h2>Dernières offres</h2>
    
    <div class="offers-grid">
        <?php foreach ($offers as $offer): ?>
        <div class="offer-card">
            <h3><?= escapeHtml($offer['title']) ?></h3>
            <div class="meta">
                <span class="company"><?= escapeHtml($offer['company_name']) ?></span>
                <span class="location">📍 <?= escapeHtml($offer['location']) ?></span>
            </div>
            
            <div class="actions">
                <?php if ($offer['has_applied']): ?>
                    <span class="applied">Candidature envoyée</span>
                <?php else: ?>
                    <a href="apply.php?id=<?= $offer['id'] ?>" class="btn">Postuler</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>