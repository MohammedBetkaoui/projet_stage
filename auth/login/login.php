<?php
session_start();
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

        echo json_encode(['status' => 'success', 'message' => 'Connexion réussie.', 'role' => $user['role']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Identifiants incorrects.']);
    }

    $stmt->close();
    $conn->close();
}
include './login_form.php'
?>
