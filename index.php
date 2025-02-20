<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Récupérer les offres récentes
try {
    $stmt = $pdo->query("SELECT 
        o.*,
        u.full_name as company_name
        FROM offers o
        JOIN users u ON o.company_id = u.id
        WHERE o.end_date >= CURDATE()
        ORDER BY o.created_at DESC
        LIMIT 6");
    
    $offers = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
<?php include 'includes/header.php'; ?>

<div class="hero">
    <div class="hero-content">
        <h1>Trouvez le stage de vos rêves</h1>
        <p>Connectez-vous avec les meilleures entreprises</p>
        
        <div class="cta-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="<?= $_SESSION['role'] === 'student' ? 'student/dashboard.php' : 'company/dashboard.php' ?>" class="btn primary">
                    Tableau de bord
                </a>
            <?php else: ?>
                <a href="auth/register.php" class="btn primary">Inscription</a>
                <a href="auth/login.php" class="btn secondary">Connexion</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">
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
                <a href="offer_details.php?id=<?= $offer['id'] ?>" class="btn">Voir l'offre</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>