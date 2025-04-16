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
            <div class="step" id="step2" style="display: none;">
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
                <button type="button" class="next-btn">Suivant</button>
            </div>

            <!-- Étape 3: Sélection du Branch (uniquement pour les étudiants) -->
            <div class="step" id="step3" style="display: none;">
                <h2>Étape 3: Sélection du Branch</h2>
                <label for="branch">Branch:</label>
                <select id="branch" name="branch">
                    <option value="" disabled selected>Choisissez votre branche</option>
                    <?php foreach ($branches as $branch): ?>
                        <option value="<?php echo htmlspecialchars($branch['id']); ?>">
                            <?php echo htmlspecialchars($branch['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="button" class="prev-btn">Précédent</button>
                <button type="submit">S'inscrire</button>
            </div>

        </form>
        <p>Déjà inscrit? <a href="../login/login.php">Connectez-vous ici</a></p>
    </div>

    <script src="./register.js"></script>
</body>

</html>