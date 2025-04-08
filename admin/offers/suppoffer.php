<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Connexion à la base de données

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est autorisé à supprimer une offre (ajoutez votre propre logique d'authentification)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Accès refusé");
}

if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $offerId = (int) $_GET['delete_id'];
    
    try {
        $stmt = $conn->prepare("DELETE FROM offers WHERE id = ?");
        $stmt->bind_param("i", $offerId);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Offre supprimée avec succès.";
        } else {
            $_SESSION['message'] = "Erreur lors de la suppression de l'offre.";
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['message'] = "Erreur: " . $e->getMessage();
    }
} else {
    $_SESSION['message'] = "ID d'offre invalide.";
}

// Redirection vers la page des offres
header("Location: /admin/offers/offer.php");
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>