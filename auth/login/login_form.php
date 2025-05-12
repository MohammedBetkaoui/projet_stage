
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./login.css">
    <title>Connexion</title>
</head>
<body>
<div class="container">
    <form id="loginForm" method="POST" novalidate>
        <h1>Connexion</h1>
        <div class="input-group">
            <label for="login">Email ou Téléphone</label>
            <input type="text" id="login" name="login" required data-validation="required">
            <div class="error-message" id="login-error"></div>
        </div>
        <div class="input-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required data-validation="required" data-min-length="6">
            <div class="error-message" id="password-error"></div>
        </div>
        <button type="submit" id="submit-btn">Se connecter</button>
        <p id="message"></p>
    </form>
    <p>Pas encore inscrit? <a href="../register/register.php">Inscrivez-vous ici</a></p>
</div>

<script src="./login.js"></script>
</body>
</html>
