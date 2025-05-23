<?php
session_start();
require_once __DIR__ . '/../../includes/db/db.php';
require_once __DIR__ . '/RegisterHandler.php';

// Redirection si déjà connecté
if (isset($_SESSION['user_id'])) {
    $redirectMap = [
        'admin' => '../../admin/admin_dashboard.php',
        'company' => '../../company/company_dashboard.php',
        'student' => '../../studant/student_dashboard.php'
    ];
    
    $redirectUrl = $redirectMap[$_SESSION['role']] ?? '../../auth/logout/logout.php';
    header("Location: $redirectUrl");
    exit();
}

$registerHandler = new RegisterHandler($conn);
$branches = $registerHandler->getBranches();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userData = [
        'username' => trim($_POST['username'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'email' => trim($_POST['email'] ?? ''),
        'role' => $_POST['role'] ?? '',
        'full_name' => trim($_POST['full_name'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'branch' => isset($_POST['branch']) ? intval($_POST['branch']) : null,
        'certificate_verified' => $_POST['certificate_verified'] ?? false
    ];

    // Si c'est un étudiant, vérifier que le certificat est validé
    if ($userData['role'] === 'student' && empty($_SESSION['certificate_verified'])) {
        $errors[] = "Veuillez valider votre certificat de scolarité avant de finaliser l'inscription.";
    } else {
        if ($registerHandler->registerUser($userData)) {
            $_SESSION['message'] = 'Inscription réussie!';
            unset($_SESSION['certificate_verified']); // Nettoyer la session
            header('Location: ../../auth/login/login.php');
            exit();
        } else {
            $errors = $registerHandler->getErrors();
        }
    }
}

include __DIR__ . '/register_form.php';
?>