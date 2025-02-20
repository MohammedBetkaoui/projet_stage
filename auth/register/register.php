<?php
session_start();

if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: ../../admin/admin_dashboard.php');
            break;
        case 'company':
            header('Location: ../../company/company_dashboard.php');
            break;
        case 'student':
            header('Location: ../../studant/student_dashboard.php');
            break;
        default:
            // Déconnexion si le rôle est inconnu
            header('Location: ../../auth/logout/logout.php');
    }
    exit();
}

require_once '../../includes/db/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputs = array_map('trim', $_POST);
    $username = htmlspecialchars($inputs['username']);
    $email = filter_var($inputs['email'], FILTER_SANITIZE_EMAIL);
    $password = $inputs['password'];
    $role = $inputs['role'];
    $full_name = htmlspecialchars($inputs['full_name'] ?? '');
    $phone = preg_replace('/[^0-9+]/', '', $inputs['phone'] ?? '');
    $address = htmlspecialchars($inputs['address'] ?? '');

    if (empty($username)) array_push($errors, ['field' => 'username', 'message' => 'Nom d\'utilisateur requis']);
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) array_push($errors, ['field' => 'username', 'message' => 'Format invalide']);
    
    if (empty($email)) array_push($errors, ['field' => 'email', 'message' => 'Email requis']);
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) array_push($errors, ['field' => 'email', 'message' => 'Email invalide']);
    
    if (empty($password)) array_push($errors, ['field' => 'password', 'message' => 'Mot de passe requis']);
    elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        array_push($errors, ['field' => 'password', 'message' => '8 caractères minimum avec majuscule et chiffre']);
    }

    if (empty($role) || !in_array($role, ['student', 'company'])) {
        array_push($errors, ['field' => 'role', 'message' => 'Rôle invalide']);
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            array_push($errors, ['field' => 'email', 'message' => 'Identifiants déjà utilisés']);
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $email, $hashedPassword, $role, $full_name, $phone, $address);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;
            
            echo json_encode([
                'status' => 'success',
                'redirect' => "/index.php"
            ]);
            exit;
            
           
        } else {
            array_push($errors, ['field' => 'global', 'message' => 'Erreur serveur']);
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Veuillez corriger les erreurs',
            'errors' => $errors
        ]);
        exit;
    }
}

include './register_form.php';
?>