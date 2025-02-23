<?php
session_start();
require '../../includes/db/db.php'; 

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
            header('Location: ../../studant/student_dashboard.php');
            break;
        default:
            // Déconnexion si le rôle est inconnu
            header('Location: ../../auth/logout/logout.php');
    }
    exit();
}
$skills = [];
$errors = []; // Tableau pour stocker les erreurs

try {
    $stmt = $conn->prepare("SELECT * FROM skills");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
} catch (Exception $e) {
    $errors[] = "Erreur lors de la récupération des compétences: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validation des champs
    if (empty($username)) {
        $errors[] = "Le nom d'utilisateur est requis.";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }
    if (empty($role)) {
        $errors[] = "Le rôle est requis.";
    }
    if (empty($full_name)) {
        $errors[] = "Le nom complet est requis.";
    }

    // Si aucune erreur, procéder à l'inscription
    if (empty($errors)) {
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Insertion des données dans la table `users`
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, full_name, phone, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $username, $password_hash, $email, $role, $full_name, $phone, $address);
        $stmt->execute();

        $user_id = $stmt->insert_id;

        // Si l'utilisateur est un étudiant, on enregistre ses compétences
        if ($role === 'student' && isset($_POST['skills'])) {
            foreach ($_POST['skills'] as $skill_id) {
                $stmt = $conn->prepare("INSERT INTO user_skills (user_id, skill_id) VALUES (?, ?)");
                $stmt->bind_param("ii", $user_id, $skill_id);
                $stmt->execute();
            }
        }
             
       


        $_SESSION['message'] = 'Inscription réussie!';
        header('Location: ../../auth/login/login.php');
        exit();
    }
}

include './register_form.php';
?>
