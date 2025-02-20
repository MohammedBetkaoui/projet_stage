<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: register.php');
    exit;
}

// Validation CSRF
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['error'] = "Erreur de sécurité. Veuillez réessayer.";
    header('Location: register.php');
    exit;
}

// Récupération des données
$inputs = [
    'role' => trim($_POST['role'] ?? ''),
    'full_name' => trim($_POST['full_name'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'username' => trim($_POST['username'] ?? ''),
    'password' => $_POST['password'] ?? '',
    'password_confirm' => $_POST['password_confirm'] ?? ''
];

// Stockage des anciennes valeurs en cas d'erreur
$_SESSION['old_username'] = $inputs['username'];
$_SESSION['old_email'] = $inputs['email'];
$_SESSION['old_full_name'] = $inputs['full_name'];

// Validation
$errors = [];

// Validation du rôle
if (!in_array($inputs['role'], ['student', 'company'])) {
    $errors[] = "Type de profil invalide";
}

// Validation de l'email
if (!filter_var($inputs['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format d'email invalide";
}

// Validation du mot de passe
if ($inputs['password'] !== $inputs['password_confirm']) {
    $errors[] = "Les mots de passe ne correspondent pas";
}

if (strlen($inputs['password']) < 8) {
    $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
}

// Vérification des doublons
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ? OR username = ?");
$stmt->execute([$inputs['email'], $inputs['username']]);

if ($stmt->fetchColumn() > 0) {
    $errors[] = "L'email ou le nom d'utilisateur existe déjà";
}

// Si erreurs, redirection
if (!empty($errors)) {
    $_SESSION['error'] = implode('<br>', $errors);
    header('Location: register.php');
    exit;
}

// Hashage du mot de passe
$passwordHash = password_hash($inputs['password'], PASSWORD_ARGON2ID);

// Insertion en base
try {
    $stmt = $pdo->prepare("INSERT INTO users 
        (username, password, email, role, full_name) 
        VALUES (?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $inputs['username'],
        $passwordHash,
        $inputs['email'],
        $inputs['role'],
        $inputs['full_name']
    ]);

    // Connexion automatique
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['role'] = $inputs['role'];
    $_SESSION['full_name'] = $inputs['full_name'];

    // Nettoyage des anciennes valeurs
    unset($_SESSION['old_username'], $_SESSION['old_email'], $_SESSION['old_full_name']);

    // Redirection
    header("Location: ../{$inputs['role']}/dashboard.php");
    exit;

} catch (PDOException $e) {
    error_log("Erreur d'inscription : " . $e->getMessage());
    $_SESSION['error'] = "Une erreur est survenue lors de l'inscription";
    header('Location: register.php');
    exit;
}