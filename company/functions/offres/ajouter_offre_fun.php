<?php
session_start();
require_once '../../includes/db/db.php';

// Rediriger si l'utilisateur n'est pas connecté ou n'est pas une entreprise
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: ../auth/login/login.php');
    exit();
}

$error = '';
$success = '';

// Récupérer les compétences existantes
$skills = [];
try {
    $stmt = $conn->prepare("SELECT * FROM skills");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
} catch (Exception $e) {
    $error = "Erreur lors de la récupération des compétences: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $sector = trim($_POST['sector']);
    $location = trim($_POST['location']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $deadline = $_POST['deadline']; // Nouveau champ
    $compensation = $_POST['compensation'];
    $selected_skills = $_POST['skills'] ?? [];

    // Validation des champs
    if (empty($title) || empty($description) || empty($sector) || empty($location) || empty($start_date) || empty($end_date) || empty($deadline)) {
        $error = 'Tous les champs obligatoires doivent être remplis';
    } elseif ($end_date <= $start_date) {
        $error = 'La date de fin doit être postérieure à la date de début';
    } elseif ($deadline <= date('Y-m-d')) {
        $error = 'La date limite de candidature doit être postérieure à aujourd\'hui';
    } else {
        try {
            // Insérer l'offre avec la nouvelle colonne `deadline`
            $stmt = $conn->prepare("INSERT INTO offers (company_id, title, description, sector, location, start_date, end_date, deadline, compensation, created_at) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("isssssssi", $_SESSION['user_id'], $title, $description, $sector, $location, $start_date, $end_date, $deadline, $compensation);
            $stmt->execute();
            $offer_id = $stmt->insert_id;

            // Lier les compétences sélectionnées à l'offre
            if (!empty($selected_skills)) {
                $stmt = $conn->prepare("INSERT INTO offer_skills (offer_id, skill_id) VALUES (?, ?)");
                foreach ($selected_skills as $skill_id) {
                    $stmt->bind_param("ii", $offer_id, $skill_id);
                    $stmt->execute();
                }
                $success = "Offre publiée avec succès!";
                $_SESSION['success_message'] = $success;
                header('Location: ../get_offres/mes_offres.php');
                exit();
            }
        } catch (Exception $e) {
            $error = "Erreur lors de la publication: " . $e->getMessage();
        }
    }
}
?>