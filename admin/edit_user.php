<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

authenticate();
checkRole(['admin']);

$errors = [];
$success = '';
$userData = [];

// Récupérer l'ID utilisateur
$user_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$user_id) {
    header('Location: dashboard.php');
    exit();
}

try {
    // Récupérer les données actuelles
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $stmt->execute([$user_id]);
    $userData = $stmt->fetch();

    if (!$userData) {
        $_SESSION['error'] = "Utilisateur introuvable";
        header('Location: dashboard.php');
        exit();
    }

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Token CSRF invalide");
    }

    // Nettoyage des données
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $full_name = trim($_POST['full_name']);
    $password = $_POST['password'];

    // Validation
    if (empty($username) || empty($email) || empty($role)) {
        $errors[] = "Tous les champs obligatoires doivent être remplis";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format d'email invalide";
    }

    if (!in_array($role, ['student', 'company', 'admin'])) {
        $errors[] = "Rôle invalide";
    }

    // Vérifier l'unicité des informations
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users 
        WHERE (username = ? OR email = ?) 
        AND id != ?');
    $stmt->execute([$username, $email, $user_id]);
    if ($stmt->fetchColumn() > 0) {
        $errors[] = "Le nom d'utilisateur ou l'email est déjà utilisé";
    }

    // Si pas d'erreurs, mise à jour
    if (empty($errors)) {
        try {
            $updateData = [
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'full_name' => $full_name,
                'id' => $user_id
            ];

            $sql = "UPDATE users SET
                username = :username,
                email = :email,
                role = :role,
                full_name = :full_name";

            // Gestion du mot de passe
            if (!empty($password)) {
                if (strlen($password) < 8) {
                    $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
                } else {
                    $updateData['password'] = password_hash($password, PASSWORD_ARGON2ID);
                    $sql .= ", password = :password";
                }
            }

            $sql .= " WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($updateData);

            $_SESSION['success'] = "Utilisateur mis à jour avec succès";
            header("Location: edit_user.php?id=$user_id");
            exit();

        } catch (PDOException $e) {
            $errors[] = "Erreur de mise à jour : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier l'utilisateur</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Modifier l'utilisateur : <?= escape($userData['full_name']) ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="alert error">
                <?php foreach ($errors as $error): ?>
                    <p><?= escape($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <p><?= escape($_SESSION['success']) ?></p>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-group">
                <label>Nom d'utilisateur *</label>
                <input type="text" name="username" 
                    value="<?= escape($userData['username']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" 
                    value="<?= escape($userData['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>Rôle *</label>
                <select name="role" required>
                    <option value="student" <?= $userData['role'] === 'student' ? 'selected' : '' ?>>Étudiant</option>
                    <option value="company" <?= $userData['role'] === 'company' ? 'selected' : '' ?>>Entreprise</option>
                    <option value="admin" <?= $userData['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="full_name" 
                    value="<?= escape($userData['full_name']) ?>">
            </div>

            <div class="form-group">
                <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                <input type="password" name="password">
            </div>

            <button type="submit" class="btn">Mettre à jour</button>
            <a href="dashboard.php" class="btn cancel">Retour</a>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>