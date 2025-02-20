<?php
session_start();
include_once('../../includes/db/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validation des champs obligatoires
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        echo json_encode(['status' => 'error', 'message' => 'Veuillez remplir tous les champs obligatoires.']);
        exit;
    }

    if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
        echo json_encode(['status' => 'error', 'message' => 'Le nom d\'utilisateur doit contenir entre 3 et 20 caractères alphanumériques.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Adresse e-mail invalide.']);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'Le mot de passe doit comporter au moins 8 caractères.']);
        exit;
    }

    if (!in_array($role, ['student', 'company'])) {
        echo json_encode(['status' => 'error', 'message' => 'Le rôle sélectionné n\'est pas valide.']);
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $email, $hashedPassword, $role, $full_name, $phone, $address);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Inscription réussie.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'inscription.']);
    }

    $stmt->close();
    $conn->close();
}
include './register_form.php'
?>

