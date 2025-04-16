<?php include '../functions/offres/ajouter_offre_fun.php' ?>
<!DOCTYPE html>
<html lang="fr">
<<<<<<< HEAD
=======

>>>>>>> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier une nouvelle offre</title>
    <link rel="stylesheet" href="../../assets/css/ajouter_offre.css"> <!-- Lien vers le fichier CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<<<<<<< HEAD
=======

>>>>>>> <body>
    <!-- Sidebar -->
    <?php include '../../includes/sidebar/sidebar.php'; ?>

    <!-- Contenu principal -->
    <section class="main-content">
        <div class="form-container">
            <h2>Publier une nouvelle offre de stage</h2>
<<<<<<< HEAD
            
=======

>>>>>>>             <!-- Alertes modernes -->
            <?php if ($error): ?>
                <div class="alert error">
                    <i class='bx bx-error-circle'></i>
                    <span><?php echo $error; ?></span>
                    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert success">
                    <i class='bx bx-check-circle'></i>
                    <span><?php echo $success; ?></span>
                    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Div pour afficher les erreurs de validation -->
            <div id="error-messages" class="modern-errors"></div>

            <!-- Formulaire en étapes -->
            <form id="offerForm" method="POST">
                <!-- Étape 1 : Informations de base -->
                <div class="form-step active" data-step="1">
                    <div class="form-group">
                        <label for="title">Titre de l'offre *</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="5" required></textarea>
                    </div>
                    <button type="button" class="next-btn">Suivant</button>
                </div>

                <!-- Étape 2 : Détails supplémentaires -->
                <div class="form-step" data-step="2">
                    <div class="form-group">
                        <label for="sector">Secteur d'activité *</label>
                        <input type="text" id="sector" name="sector" required>
                    </div>
                    <div class="form-group">
                        <label for="location">Lieu *</label>
                        <input type="text" id="location" name="location" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date">Date de début *</label>
                            <input type="date" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Date de fin *</label>
                            <input type="date" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deadline">Date limite de candidature *</label>
                        <input type="date" id="deadline" name="deadline" required>
                    </div>
                    <button type="button" class="prev-btn">Précédent</button>
                    <button type="button" class="next-btn">Suivant</button>
                </div>

                <!-- Étape 3 : Compétences et gratification -->
                <div class="form-step" data-step="3">
                    <div class="form-group">
                        <label for="compensation">Gratification (Dz/mois)</label>
                        <input type="number" id="compensation" name="compensation" min="0">
                    </div>
                    <div class="form-group">
<<<<<<< HEAD
                        <label>Compétences requises</label>
                        <div class="skills-container">
                            <?php foreach ($skills as $skill): ?>
                                <label class="skill-checkbox">
                                    <input type="checkbox" name="skills[]" value="<?php echo $skill['id']; ?>">
                                    <span><?php echo htmlspecialchars($skill['name']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
=======
                        <label>selectionne une branche</label>
                        <select id="branch" name="branch_id" required>
                            <option value="" disabled selected>Choisissez votre branche</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?php echo $branch['id']; ?>">
                                    <?php echo htmlspecialchars($branch['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

>>>>>>>                     </div>
                    <button type="button" class="prev-btn">Précédent</button>
                    <button type="submit" class="submit-btn">Publier l'offre</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Scripts -->
    <script src="../../assets/js/ajouter_offre.js"></script>
</body>
<<<<<<< HEAD
=======

>>>>>>> </html>