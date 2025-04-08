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

// Récupérer l'ID de l'offre à modifier depuis l'URL
if (!isset($_GET['id'])) {
    header('Location: ../get_offres/mes_offres.php');
    exit();
}
$offer_id = intval($_GET['id']);

// Récupérer les données de l'offre existante
$offer = [];
try {
    $stmt = $conn->prepare("SELECT * FROM offers WHERE id = ? AND company_id = ?");
    $stmt->bind_param("ii", $offer_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        header('Location: ../get_offres/mes_offres.php');
        exit();
    }
    $offer = $result->fetch_assoc();
} catch (Exception $e) {
    $error = "Erreur lors de la récupération de l'offre: " . $e->getMessage();
}

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

// Récupérer les compétences sélectionnées pour cette offre
$selected_skills = [];
try {
    $stmt = $conn->prepare("SELECT skill_id FROM offer_skills WHERE offer_id = ?");
    $stmt->bind_param("i", $offer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $selected_skills[] = $row['skill_id'];
    }
} catch (Exception $e) {
    $error = "Erreur lors de la récupération des compétences sélectionnées: " . $e->getMessage();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $sector = trim($_POST['sector']);
    $location = trim($_POST['location']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $deadline = $_POST['deadline'];
    $compensation = $_POST['compensation'];
    $new_selected_skills = $_POST['skills'] ?? [];

    // Validation des champs
    if (empty($title) || empty($description) || empty($sector) || empty($location) || empty($start_date) || empty($end_date) || empty($deadline)) {
        $error = 'Tous les champs obligatoires doivent être remplis';
    } elseif ($end_date <= $start_date) {
        $error = 'La date de fin doit être postérieure à la date de début';
    } elseif ($deadline <= date('Y-m-d')) {
        $error = 'La date limite de candidature doit être postérieure à aujourd\'hui';
    } else {
        try {
            // Mettre à jour l'offre
            $stmt = $conn->prepare("UPDATE offers SET title = ?, description = ?, sector = ?, location = ?, start_date = ?, end_date = ?, deadline = ?, compensation = ? WHERE id = ?");
            $stmt->bind_param("sssssssii", $title, $description, $sector, $location, $start_date, $end_date, $deadline, $compensation, $offer_id);
            $stmt->execute();

            // Mettre à jour les compétences sélectionnées
            if (!empty($new_selected_skills)) {
                // Supprimer les anciennes compétences
                $stmt = $conn->prepare("DELETE FROM offer_skills WHERE offer_id = ?");
                $stmt->bind_param("i", $offer_id);
                $stmt->execute();

                // Ajouter les nouvelles compétences
                $stmt = $conn->prepare("INSERT INTO offer_skills (offer_id, skill_id) VALUES (?, ?)");
                foreach ($new_selected_skills as $skill_id) {
                    $stmt->bind_param("ii", $offer_id, $skill_id);
                    $stmt->execute();
                }
            }

            $success = "Offre modifiée avec succès!";
            $_SESSION['success_message'] = $success;
            header('Location: ../get_offres/mes_offres.php');
            exit();
        } catch (Exception $e) {
            $error = "Erreur lors de la modification: " . $e->getMessage();
        }
    }
}
?>