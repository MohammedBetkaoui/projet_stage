<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

$errors = [];
$old_input = [
    'username' => $_SESSION['old_username'] ?? '',
    'email' => $_SESSION['old_email'] ?? '',
    'full_name' => $_SESSION['old_full_name'] ?? ''
];

unset($_SESSION['old_username'], $_SESSION['old_email'], $_SESSION['old_full_name']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Plateforme de Stages</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="registration-box">
            <h2>Créer un compte</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="register_process.php" method="POST" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="form-group">
                    <label for="role">Je suis :</label>
                    <select name="role" id="role" required>
                        <option value="">-- Sélectionnez votre profil --</option>
                        <option value="student" <?= ($old_input['role'] ?? '') === 'student' ? 'selected' : '' ?>>Étudiant</option>
                        <option value="company" <?= ($old_input['role'] ?? '') === 'company' ? 'selected' : '' ?>>Entreprise</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="full_name">Nom complet :</label>
                    <input type="text" id="full_name" name="full_name" 
                           value="<?= htmlspecialchars($old_input['full_name']) ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="email">Adresse email :</label>
                    <input type="email" id="email" name="email" 
                           value="<?= htmlspecialchars($old_input['email']) ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" 
                           value="<?= htmlspecialchars($old_input['username']) ?>" 
                           required>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" 
                           minlength="8" required>
                    <small class="hint">Minimum 8 caractères</small>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmer le mot de passe :</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>

                <button type="submit" class="btn">S'inscrire</button>
            </form>

            <div class="login-link">
                Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>
            </div>
        </div>
    </div>
</body>
</html>