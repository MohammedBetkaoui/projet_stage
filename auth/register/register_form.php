<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./register.css">
    <title>Inscription</title>
    
</head>

<body>
<div class="container">
    <form id="registerForm" method="POST">
        <h1>Inscription</h1>
        <div class="input-group">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" required>
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="input-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <div class="input-group">
            <label>Rôle</label>
            <select name="role" required>
                <option value="student">Étudiant</option>
                <option value="company">Entreprise</option>
            </select>
        </div>
        <div class="input-group">
            <label>Nom complet (optionnel)</label>
            <input type="text" name="full_name">
        </div>
        <div class="input-group">
            <label>Téléphone (optionnel)</label>
            <input type="text" name="phone">
        </div>
        <div class="input-group">
            <label>Adresse (optionnel)</label>
            <input type="text" name="address">
        </div>
        <button type="submit">S'inscrire</button>
        <p id="message"></p>
    </form>
</div>
      <script src="./register.js"></script>
</body>
</html>
