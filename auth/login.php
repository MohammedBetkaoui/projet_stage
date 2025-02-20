<?php include '../includes/config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Connexion</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?= $_GET['error'] ?></div>
        <?php endif; ?>
        <form action="login_process.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="form-group">
                <label>Email :</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Mot de passe :</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
        <p>Pas de compte? <a href="register.php">Inscrivez-vous ici</a></p>
    </div>
</body>
</html>