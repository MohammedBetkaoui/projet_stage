<?php
require_once '../includes/auth.php';
authenticate();
checkRole(['admin']);

// Statistiques
$stats = $pdo->query('
    SELECT 
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM offers) AS total_offers,
        (SELECT COUNT(*) FROM applications) AS total_applications
')->fetch();

// Liste utilisateurs
$users = $pdo->query('SELECT * FROM users ORDER BY created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Administration</h1>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Utilisateurs</h3>
                <p><?= escape($stats['total_users']) ?></p>
            </div>
            <div class="stat-card">
                <h3>Offres</h3>
                <p><?= escape($stats['total_offers']) ?></p>
            </div>
            <div class="stat-card">
                <h3>Candidatures</h3>
                <p><?= escape($stats['total_applications']) ?></p>
            </div>
        </div>

        <h2>Gestion des utilisateurs</h2>
        <table class="users-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= escape($user['full_name']) ?></td>
                    <td><?= escape($user['email']) ?></td>
                    <td><?= escape($user['role']) ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-edit">Éditer</a>
                        <form action="delete_user.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" class="btn-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php include '../includes/footer.php'; ?>
</body>
</html>