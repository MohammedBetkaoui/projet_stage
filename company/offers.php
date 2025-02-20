<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

authenticate();
checkRole(['company']);

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Récupération des offres
try {
    // Total d'offres
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM offers WHERE company_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalOffers = $stmt->fetchColumn();

    // Offres paginées
    $stmt = $pdo->prepare("SELECT 
        o.*, 
        COUNT(a.id) as applications_count
        FROM offers o
        LEFT JOIN applications a ON o.id = a.offer_id
        WHERE o.company_id = ?
        GROUP BY o.id
        ORDER BY o.created_at DESC
        LIMIT ? OFFSET ?");
    
    $stmt->execute([$_SESSION['user_id'], $perPage, $offset]);
    $offers = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des offres</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Gestion des offres</h1>

        <div class="filters">
            <form method="GET" class="search-form">
                <input type="text" name="search" placeholder="Rechercher...">
                <button type="submit" class="btn">🔍</button>
            </form>
        </div>

        <table class="offers-table">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Secteur</th>
                    <th>Localisation</th>
                    <th>Candidatures</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offers as $offer): ?>
                <tr>
                    <td><?= htmlspecialchars($offer['title']) ?></td>
                    <td><?= htmlspecialchars($offer['sector']) ?></td>
                    <td><?= htmlspecialchars($offer['location']) ?></td>
                    <td><?= $offer['applications_count'] ?></td>
                    <td>
                        <span class="status <?= $offer['end_date'] > date('Y-m-d') ? 'active' : 'inactive' ?>">
                            <?= $offer['end_date'] > date('Y-m-d') ? 'Active' : 'Expirée' ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="edit_offer.php?id=<?= $offer['id'] ?>" class="btn small">Éditer</a>
                        <a href="delete_offer.php?id=<?= $offer['id'] ?>" 
                           class="btn small danger"
                           onclick="return confirm('Supprimer définitivement cette offre ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= ceil($totalOffers / $perPage); $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>