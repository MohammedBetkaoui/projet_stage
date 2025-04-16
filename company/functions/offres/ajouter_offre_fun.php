<?php
session_start();
require_once '../../includes/db/db.php';

<<<<<<< HEAD
// Rediriger si l'utilisateur n'est pas connecté ou n'est pas une entreprise
=======
// Redirect if the user is not logged in or is not a company
>>>>>>> if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: ../auth/login/login.php');
    exit();
}

$error = '';
$success = '';

<<<<<<< HEAD
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
=======
// Fetch all branches
$branches = [];
$sql = "SELECT * FROM branch";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $branches[] = $row;
    }
>>>>>>> }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $sector = trim($_POST['sector']);
    $location = trim($_POST['location']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
<<<<<<< HEAD
    $deadline = $_POST['deadline']; // Nouveau champ
    $compensation = $_POST['compensation'];
    $selected_skills = $_POST['skills'] ?? [];

    // Validation des champs
    if (empty($title) || empty($description) || empty($sector) || empty($location) || empty($start_date) || empty($end_date) || empty($deadline)) {
=======
    $deadline = $_POST['deadline'];
    $compensation = $_POST['compensation'];
    $branch_id = $_POST['branch_id'] ?? null;

    // Validate fields
    if (empty($title) || empty($description) || empty($sector) || empty($location) || empty($start_date) || empty($end_date) || empty($deadline) || empty($branch_id)) {
>>>>>>>         $error = 'Tous les champs obligatoires doivent être remplis';
    } elseif ($end_date <= $start_date) {
        $error = 'La date de fin doit être postérieure à la date de début';
    } elseif ($deadline <= date('Y-m-d')) {
        $error = 'La date limite de candidature doit être postérieure à aujourd\'hui';
    } else {
        try {
<<<<<<< HEAD
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
=======
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
>>>>>>>                 $success = "Offre publiée avec succès!";
                $_SESSION['success_message'] = $success;
                header('Location: ../get_offres/mes_offres.php');
                exit();
<<<<<<< HEAD
=======
            } else {
                $error = $response;
>>>>>>>             }
        } catch (Exception $e) {
            $error = "Erreur lors de la publication: " . $e->getMessage();
        }
    }
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 