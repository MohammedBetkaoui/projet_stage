<?php
session_start();
require_once '../../includes/db/db.php';

// Redirect if the user is not logged in or is not a company
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: ../../auth/login/login.php');
    exit();
}

$error = '';
$success = '';

// Fetch all branches
$branches = [];
$sql = "SELECT * FROM branch";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $branches[] = $row;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $sector = trim($_POST['sector'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $compensation = isset($_POST['compensation']) && is_numeric($_POST['compensation']) ? (int)$_POST['compensation'] : 0;
    $branch_id = $_POST['branch_id'] ?? null;

    // Validation améliorée
    $errors = [];

    // Validation du titre
    if (empty($title)) {
        $errors[] = 'Le titre est obligatoire';
    } elseif (strlen($title) < 5) {
        $errors[] = 'Le titre doit contenir au moins 5 caractères';
    } elseif (strlen($title) > 100) {
        $errors[] = 'Le titre doit contenir au maximum 100 caractères';
    }

    // Validation de la description
    if (empty($description)) {
        $errors[] = 'La description est obligatoire';
    } elseif (strlen($description) < 20) {
        $errors[] = 'La description doit contenir au moins 20 caractères';
    }

    // Validation du secteur
    if (empty($sector)) {
        $errors[] = 'Le secteur est obligatoire';
    } elseif (strlen($sector) < 3) {
        $errors[] = 'Le secteur doit contenir au moins 3 caractères';
    }

    // Validation du lieu
    if (empty($location)) {
        $errors[] = 'Le lieu est obligatoire';
    } elseif (strlen($location) < 3) {
        $errors[] = 'Le lieu doit contenir au moins 3 caractères';
    }

    // Validation des dates
    if (empty($start_date)) {
        $errors[] = 'La date de début est obligatoire';
    } elseif (strtotime($start_date) < strtotime(date('Y-m-d'))) {
        $errors[] = 'La date de début doit être dans le futur';
    }

    if (empty($end_date)) {
        $errors[] = 'La date de fin est obligatoire';
    } elseif (!empty($start_date) && strtotime($end_date) <= strtotime($start_date)) {
        $errors[] = 'La date de fin doit être postérieure à la date de début';
    }

    if (empty($deadline)) {
        $errors[] = 'La date limite de candidature est obligatoire';
    } elseif (strtotime($deadline) <= strtotime(date('Y-m-d'))) {
        $errors[] = 'La date limite de candidature doit être dans le futur';
    }

    // Validation de la branche
    if (empty($branch_id)) {
        $errors[] = 'La branche est obligatoire';
    }

    // Validation de la gratification
    if ($compensation < 0) {
        $errors[] = 'La gratification doit être un nombre positif';
    }

    // S'il y a des erreurs, les afficher
    if (!empty($errors)) {
        $error = implode('<br>', $errors);
    } else {
        try {
            // Prepare data for the API
            $offerData = [
                'company_id' => $_SESSION['user_id'],
                'title' => $title,
                'description' => $description,
                'sector' => $sector,
                'location' => $location,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'deadline' => $deadline,
                'compensation' => $compensation,
                'branch_id' => $branch_id
            ];

            // Send POST request to the API
            $ch = curl_init('http://localhost:5000/api/add');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($offerData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'x-api-key: stage'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 201) {
                $success = "Offre publiée avec succès!";
                $_SESSION['success_message'] = $success;
                header('Location: ../get_offres/mes_offres.php');
                exit();
            } else {
                $error = $response;
            }
        } catch (Exception $e) {
            $error = "Erreur lors de la publication: " . $e->getMessage();
        }
    }
}
?>
