<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/company/functions/offres/get_offres.php'; // Inclure la fonction pour récupérer les offres

// Récupérer les offres depuis la base de données
$offers = [];
try {
    $offers = getOffers($conn); // Récupérer toutes les offres
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
 
<section class="offers-section">
        <h2>Offres de stages disponibles</h2>
        <div class="offers-container">
            <?php if (!empty($offers)): ?>
                <?php foreach ($offers as $offer): ?>
                    <div class="offer-card">
                        <h3><?php echo htmlspecialchars($offer['title']); ?></h3>
                        <p><?php echo htmlspecialchars($offer['description']); ?></p>
                        <div class="details">
                            <span><strong>Entreprise:</strong> <?php echo htmlspecialchars($offer['company_name']); ?></span>
                            <span><strong>Secteur:</strong> <?php echo htmlspecialchars($offer['sector']); ?></span>
                            <span><strong>Lieu:</strong> <?php echo htmlspecialchars($offer['location']); ?></span>
                            <span><strong>Date de début:</strong> <?php echo htmlspecialchars($offer['start_date']); ?></span>
                            <span><strong>Date de fin:</strong> <?php echo htmlspecialchars($offer['end_date']); ?></span>
                            <span><strong>Gratification:</strong> <?php echo $offer['compensation'] ? $offer['compensation'] . ' Dz/mois' : 'Non spécifiée'; ?></span>
                        </div>
                        <a href="/apply.php?id=<?php echo $offer['id']; ?>" class="btn">Postuler</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-offers">Aucune offre disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>