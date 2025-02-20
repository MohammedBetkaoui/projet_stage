<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Vérifier l'authentification et le rôle admin
authenticate();
checkRole(['admin']);

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit('Méthode non autorisée');
}

// Vérifier le token CSRF
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['error'] = "Token de sécurité invalide";
    header('Location: dashboard.php');
    exit();
}

// Valider l'ID utilisateur
$user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
if (!$user_id) {
    $_SESSION['error'] = "ID utilisateur invalide";
    header('Location: dashboard.php');
    exit();
}

try {
    // Empêcher la suppression de soi-même
    if ($user_id === $_SESSION['user_id']) {
        $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte";
        header('Location: dashboard.php');
        exit();
    }

    // Vérifier que l'utilisateur existe
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        $_SESSION['error'] = "Utilisateur introuvable";
        header('Location: dashboard.php');
        exit();
    }

    // Supprimer l'utilisateur
    $deleteStmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $deleteStmt->execute([$user_id]);

    // Vérifier si la suppression a réussi
    if ($deleteStmt->rowCount() > 0) {
        $_SESSION['success'] = "Utilisateur supprimé avec succès";
        
        // Supprimer les données liées selon les contraintes de clé étrangère
        // (sera géré automatiquement si ON DELETE CASCADE est configuré en base)
    } else {
        $_SESSION['error'] = "Échec de la suppression de l'utilisateur";
    }

} catch (PDOException $e) {
    // Gérer les erreurs de contrainte d'intégrité
    if ($e->getCode() === '23000') {
        $_SESSION['error'] = "Impossible de supprimer l'utilisateur car il a des données associées";
    } else {
        $_SESSION['error'] = "Erreur technique : " . $e->getMessage();
    }
}

// Redirection vers le tableau de bord admin
header('Location: dashboard.php');
exit();
?>