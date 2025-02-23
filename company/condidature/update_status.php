<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté et est une entreprise
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: /auth/login/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = intval($_POST['application_id']);
    $status = trim($_POST['status']);

    // Mettre à jour le statut de la candidature
    try {
        $stmt = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $application_id);
        $stmt->execute();
        $_SESSION['success_message'] = "Statut de la candidature mis à jour avec succès.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur lors de la mise à jour du statut : " . $e->getMessage();
    }

    // Rediriger vers le tableau de bord
    header('Location: /company/condidature/condidature.php');
    exit();
}
?>