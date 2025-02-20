
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
    <form id="loginForm" method="POST">
        <h1>Connexion</h1>
        <div class="input-group">
            <label>Email ou Téléphone</label>
            <input type="text" name="login" required>
        </div>
        <div class="input-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Se connecter</button>
        <p id="message"></p>
    </form>
</div>

<script src="./login.js"></script>
</body>
</html>
