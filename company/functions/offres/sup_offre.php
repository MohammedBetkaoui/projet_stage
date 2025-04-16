<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données

// Rediriger si l'utilisateur n'est pas connecté ou n'est pas une entreprise
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: ../../../../../auth/login/login.php');
    exit();
}

// Vérifier si l'ID de l'offre est passé en paramètre
if (!isset($_GET['id'])) {
    header('Location: ../../get_offres/mes_offres.php');
    exit();
}

$offer_id = intval($_GET['id']); // Récupérer l'ID de l'offre

try {
   

    // Supprimer l'offre de la table `offers`
    $stmt = $conn->prepare("DELETE FROM offers WHERE id = ? AND company_id = ?");
    $stmt->bind_param("ii", $offer_id, $_SESSION['user_id']);
    $stmt->execute();

    // Vérifier si l'offre a été supprimée
    if ($stmt->affected_rows > 0) {
        // Valider la transaction
        $conn->commit();
        $_SESSION['success_message'] = "L'offre a été supprimée avec succès.";
    } else {
        // Annuler la transaction si aucune ligne n'a été affectée
        $conn->rollback();
        $_SESSION['error_message'] = "Erreur : L'offre n'a pas été trouvée ou vous n'avez pas les droits pour la supprimer.";
    }

    // Rediriger vers la page des offres
    header('Location: ../../get_offres/mes_offres.php');
    exit();
} catch (Exception $e) {
    // En cas d'erreur, annuler la transaction et afficher un message d'erreur
    $conn->rollback();
    $_SESSION['error_message'] = "Erreur lors de la suppression de l'offre : " . $e->getMessage();
    header('Location: ../../mes_offres.php');
    exit();
}
?>