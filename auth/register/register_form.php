<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="./register.css">
    <!-- Ajout des librairies nécessaires pour la vérification -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
</head>
<body>
    <div class="container">
        <?php if (!empty($errors)): ?>
            <div class="error-container">
                <?php foreach ($errors as $error): ?>
                    <p class="error-message"><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form id="registerForm" action="register.php" method="POST" novalidate enctype="multipart/form-data">
            <!-- Étape 1: Informations de base -->
            <div class="step" id="step1">
                <h2>Étape 1: Informations de base</h2>
                <div class="form-group">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" id="username" name="username" required 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <button type="button" class="next-btn">Suivant</button>
            </div>

            <!-- Étape 2: Rôle et informations supplémentaires -->
            <div class="step" id="step2" style="display: none;">
                <h2>Étape 2: Rôle et informations supplémentaires</h2>
                <div class="form-group">
                    <label for="role">Rôle:</label>
                    <select id="role" name="role" required>
                        <option value="" disabled selected>Choisissez votre rôle</option>
                        <option value="student" <?= ($_POST['role'] ?? '') === 'student' ? 'selected' : '' ?>>Étudiant</option>
                        <option value="company" <?= ($_POST['role'] ?? '') === 'company' ? 'selected' : '' ?>>Entreprise</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="full_name">Nom complet:</label>
                    <input type="text" id="full_name" name="full_name" required 
                           value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Téléphone:</label>
                    <input type="text" id="phone" name="phone" 
                           value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="address">Adresse:</label>
                    <input type="text" id="address" name="address" 
                           value="<?= htmlspecialchars($_POST['address'] ?? '') ?>">
                </div>
                <button type="button" class="prev-btn">Précédent</button>
                <button type="button" class="next-btn" id="next-btn-step2">Suivant</button>
                <button type="submit" class="submit-btn" id="submit-btn-step2" style="display: none;">S'inscrire</button>
            </div>
               
            <!-- Étape 3: Vérification du certificat de scolarité (uniquement pour les étudiants) -->
            <div class="step" id="step3" style="display: none;">
                <h2>Étape 3: Vérification du certificat de scolarité</h2>
                <div id="certificate-verification-container">
                    <div class="upload-container">
                        <p>Pour les étudiants, veuillez télécharger votre certificat de scolarité pour vérification :</p>
                        <input type="file" id="certificate-file" accept="application/pdf,image/*">
                        <button type="button" id="verify-certificate">Vérifier le certificat</button>
                    </div>
                    <div id="verification-result" style="display: none; margin-top: 20px;">
                        <div id="verification-message"></div>
                        <input type="hidden" id="certificate_verified" name="certificate_verified" value="0">
                    </div>
                </div>
                <button type="button" class="prev-btn">Précédent</button>
                <button type="button" class="next-btn" id="next-after-verification" style="display: none;">Suivant</button>
            </div>

            <!-- Étape 4: Sélection du Branch (uniquement pour les étudiants) -->
            <div class="step" id="step4" style="display: none;">
                <h2>Étape 4: Sélection du Branch</h2>
                <div class="form-group">
                    <label for="branch">Branch:</label>
                    <select id="branch" name="branch">
                        <option value="" disabled selected>Choisissez votre branche</option>
                        <?php foreach ($branches as $branch): ?>
                            <option value="<?= htmlspecialchars($branch['id']) ?>"
                                <?= (isset($_POST['branch']) && $_POST['branch'] == $branch['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($branch['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="button" class="prev-btn">Précédent</button>
                <button type="submit" class="submit-btn">S'inscrire</button>
            </div>
        </form>
        
        <p class="login-link">Déjà inscrit? <a href="../login/login.php">Connectez-vous ici</a></p>
    </div>

    <script src="./register.js"></script>
    <script src="./verification_register.js"></script>
</body>
</html>