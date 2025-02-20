<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

authenticate();
checkRole(['student']);

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Récupérer les candidatures
try {
    // Total
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $total = $stmt->fetchColumn();

    // Candidatures
    $stmt = $pdo->prepare("SELECT 
        a.*,
        o.title as offer_title,
        u.full_name as company_name
        FROM applications a
        JOIN offers o ON a.offer_id = o.id
        JOIN users u ON o.company_id = u.id
        WHERE a.student_id = ?
        ORDER BY a.applied_at DESC
        LIMIT ? OFFSET ?");
    
    $stmt->execute([$_SESSION['user_id'], $perPage, $offset]);
    $applications = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Mes candidatures</h1>

    <table class="applications-table">
        <thead>
            <tr>
                <th>Offre</th>
                <th>Entreprise</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $app): ?>
            <tr>
                <td><?= escapeHtml($app['offer_title']) ?></td>
                <td><?= escapeHtml($app['company_name']) ?></td>
                <td><?= formatDate($app['applied_at']) ?></td>
                <td>
                    <span class="status <?= $app['status'] ?>">
                        <?= ucfirst($app['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="application_details.php?id=<?= $app['id'] ?>" class="btn small">Détails</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= ceil($total / $perPage); $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>