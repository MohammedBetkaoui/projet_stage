<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

authenticate();
checkRole(['company']);

$errors = [];
$oldInput = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur de sécurité. Veuillez réessayer.");
    }

    // Récupération et validation des données
    $title = cleanInput($_POST['title']);
    $description = cleanInput($_POST['description']);
    $sector = cleanInput($_POST['sector']);
    $location = cleanInput($_POST['location']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Validation
    if (empty($title) || strlen($title) < 5) {
        $errors[] = "Le titre doit contenir au moins 5 caractères";
    }

    if (empty($description) || strlen($description) < 50) {
        $errors[] = "La description doit contenir au moins 50 caractères";
    }

    if (empty($sector)) {
        $errors[] = "Le secteur d'activité est requis";
    }

    if (!validateDate($start_date) || !validateDate($end_date)) {
        $errors[] = "Format de date invalide (AAAA-MM-JJ)";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO offers 
                (company_id, title, description, sector, location, start_date, end_date)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $_SESSION['user_id'],
                $title,
                $description,
                $sector,
                $location,
                $start_date,
                $end_date
            ]);

            $_SESSION['success'] = "Offre publiée avec succès !";
            header('Location: dashboard.php');
            exit;

        } catch (PDOException $e) {
            $errors[] = "Erreur lors de la publication : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Publier une offre</title>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container">
        <h1>Publier une nouvelle offre de stage</h1>

        <?php displayErrors($errors); ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-group">
                <label>Titre de l'offre *</label>
                <input type="text" name="title" required 
                    value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label>Description détaillée *</label>
                <textarea name="description" rows="6" required><?= 
                    htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label>Secteur d'activité *</label>
                <select name="sector" required>
                    <option value="">Choisir un secteur</option>
                    <option value="IT">Informatique</option>
                    <option value="Marketing">Marketing</option>
                    <option value="Finance">Finance</option>
                    <option value="Engineering">Ingénierie</option>
                </select>
            </div>

            <div class="form-group">
                <label>Localisation *</label>
                <input type="text" name="location" required 
                    value="<?= htmlspecialchars($_POST['location'] ?? '') ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Date de début *</label>
                    <input type="date" name="start_date" required 
                        value="<?= htmlspecialchars($_POST['start_date'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Date de fin *</label>
                    <input type="date" name="end_date" required 
                        value="<?= htmlspecialchars($_POST['end_date'] ?? '') ?>">
                </div>
            </div>

            <button type="submit" class="btn">Publier l'offre</button>
            <a href="dashboard.php" class="btn cancel">Annuler</a>
        </form>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>