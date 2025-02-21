<?php
session_start();
require_once '../../includes/db/db.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: ../auth/login/login.php');
    exit();
}

$error = '';
$success = '';

// Récupérer les compétences existantes
$skills = [];
try {
    $stmt = $conn->prepare("SELECT * FROM skills");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
} catch (Exception $e) {
    $error = "Erreur lors de la récupération des compétences: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $sector = trim($_POST['sector']);
    $location = trim($_POST['location']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $compensation = $_POST['compensation'];
    $selected_skills = $_POST['skills'] ?? [];

    if (empty($title) || empty($description) || empty($sector) || empty($location) || empty($start_date) || empty($end_date)) {
        $error = 'Tous les champs obligatoires doivent être remplis';
    } elseif ($end_date <= $start_date) {
        $error = 'La date de fin doit être postérieure à la date de début';
    } else {
        try {
            // Insérer l'offre
            $stmt = $conn->prepare("INSERT INTO offers (company_id, title, description, sector, location, start_date, end_date, compensation, created_at) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("issssssi", $_SESSION['user_id'], $title, $description, $sector, $location, $start_date, $end_date, $compensation);
            $stmt->execute();
            $offer_id = $stmt->insert_id;

            // Lier les compétences sélectionnées à l'offre
            if (!empty($selected_skills)) {
                $stmt = $conn->prepare("INSERT INTO offer_skills (offer_id, skill_id) VALUES (?, ?)");
                foreach ($selected_skills as $skill_id) {
                    $stmt->bind_param("ii", $offer_id, $skill_id);
                    $stmt->execute();
                }
            }

            $success = "Offre publiée avec succès!";
        } catch (Exception $e) {
            $error = "Erreur lors de la publication: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier une nouvelle offre</title>
    <link rel="stylesheet" href="./ajouter_offre.css">
</head>
<body>
    <div class="form-container">
        <h2>Publier une nouvelle offre de stage</h2>
        
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php elseif ($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form id="offerForm" method="POST">
            <div class="form-group">
                <label for="title">Titre de l'offre *</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" rows="5" required></textarea>
            </div>

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
                <label for="compensation">Gratification (Dz/mois)</label>
                <input type="number" id="compensation" name="compensation" min="0">
            </div>

            <div class="form-group">
                <label>Compétences requises</label>
                <div class="skills-container">
                    <?php foreach ($skills as $skill): ?>
                        <label class="skill-checkbox">
                            <input type="checkbox" name="skills[]" value="<?php echo $skill['id']; ?>">
                            <span><?php echo htmlspecialchars($skill['name']); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <button type="submit" class="submit-btn">Publier l'offre</button>
        </form>
    </div>

    <script src="./ajouter_offre.js"></script>
</body>
</html>