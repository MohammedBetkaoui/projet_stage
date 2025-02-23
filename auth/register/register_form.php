<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="./register.css">
</head>
<body>
    <div class="container">
        <!-- Affichage des erreurs -->
        <?php if (!empty($errors)): ?>
            <div class="error-container">
                <?php foreach ($errors as $error): ?>
                    <p class="error-message"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form id="registerForm" action="register.php" method="POST">
            <!-- Étape 1: Informations de base -->
            <div class="step" id="step1">
                <h2>Étape 1: Informations de base</h2>
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" id="username" name="username" required>
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <button type="button" class="next-btn">Suivant</button>
            </div>

            <!-- Étape 2: Rôle et informations supplémentaires -->
            <div class="step" id="step2">
                <h2>Étape 2: Rôle et informations supplémentaires</h2>
                <label for="role">Rôle:</label>
                <select id="role" name="role" required>
                    <option value="" disabled selected>Choisissez votre rôle</option>
                    <option value="student">Étudiant</option>
                    <option value="company">Entreprise</option>
                </select>
                <label for="full_name">Nom complet:</label>
                <input type="text" id="full_name" name="full_name" required>
                <label for="phone">Téléphone:</label>
                <input type="text" id="phone" name="phone">
                <label for="address">Adresse:</label>
                <input type="text" id="address" name="address">
                <button type="button" class="prev-btn">Précédent</button>
                <button type="button" class="next-btn" id="nextButton">Suivant</button>
                <button type="submit" id="submitButton" style="display: none;">S'inscrire</button>
            </div>

            <!-- Étape 3: Compétences (uniquement pour les étudiants) -->
            <div class="step" id="step3">
                <h2>Étape 3: Compétences</h2>
                <div class="skills-container">
                    <?php foreach ($skills as $skill): ?>
                        <label class="skill-checkbox">
                            <input type="checkbox" name="skills[]" value="<?php echo $skill['id']; ?>">
                            <span><?php echo htmlspecialchars($skill['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="prev-btn">Précédent</button>
                <button type="submit">S'inscrire</button>
            </div>
        </form>
        <p>test? <a href="../login/login.php">connecte-vous ici</a></p>
        </div>
    
    <script src="./register.js"></script>
</body>
</html>