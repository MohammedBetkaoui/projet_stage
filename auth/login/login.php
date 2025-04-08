<?php
session_start();

// Redirection si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: ../../admin/admin_dashboard.php');
            break;
        case 'company':
            header('Location: ../../company/company_dashboard.php');
            break;
        case 'student':
            header('Location: ../../studant/home_dashboard/student_dashboard.php');
            break;
        default:
            // Déconnexion si le rôle est inconnu
            header('Location: ../../auth/logout/logout.php');
    }
    exit();
}

include_once '../../includes/db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Veuillez remplir tous les champs.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        echo json_encode([
            'status' => 'success', 
            'message' => 'Connexion réussie.', 
            'role' => $user['role']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Identifiants incorrects.']);
    }
    
    $stmt->close();
    $conn->close();
    exit;
}

include './login_form.php';
?>