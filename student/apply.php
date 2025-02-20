<?php
require_once '../includes/auth.php';
require_once '../includes/functions.php';

authenticate();
checkRole(['student']);

// Validation de l'offre
$offerId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$offerId) {
    header('Location: dashboard.php');
    exit;
}

// Récupérer l'offre
try {
    $stmt = $pdo->prepare("SELECT 
        o.*,
        u.full_name as company_name
        FROM offers o
        JOIN users u ON o.company_id = u.id
        WHERE o.id = ?");
    
    $stmt->execute([$offerId]);
    $offer = $stmt->fetch();

    if (!$offer) {
        header('Location: dashboard.php');
        exit;
    }

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

// Traitement du formulaire
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validation CSRF
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Erreur de sécurité. Veuillez réessayer.");
    }

    // Validation des fichiers
    try {
        $cvPath = handleFileUpload($_FILES['cv'], ['application/pdf']);
        $coverLetter = cleanInput($_POST['cover_letter']);
        
        // Insertion de la candidature
        $stmt = $pdo->prepare("INSERT INTO applications 
            (student_id, offer_id, cv_path, cover_letter)
            VALUES (?, ?, ?, ?)");
        
        $stmt->execute([
            $_SESSION['user_id'],
            $offerId,
            $cvPath,
            $coverLetter
        ]);

        $_SESSION['success'] = "Candidature envoyée avec succès !";
        header('Location: applications.php');
        exit;

    } catch (Exception $e) {
        $errors[] = $e->getMessage();
    }
}
?>
<?php include '../includes/header.php'; ?>

<div class="container">
    <h1>Postuler à : <?= escapeHtml($offer['title']) ?></h1>
    <p class="company-name"><?= escapeHtml($offer['company_name']) ?></p>

    <?php displayErrors($errors); ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="form-group">
            <label>CV (PDF uniquement, max 2Mo) *</label>
            <input type="file" name="cv" accept="application/pdf" required>
        </div>

        <div class="form-group">
            <label>Lettre de motivation</label>
            <textarea name="cover_letter" rows="6"></textarea>
        </div>

        <button type="submit" class="btn">Envoyer la candidature</button>
        <a href="dashboard.php" class="btn cancel">Annuler</a>
    </form>
</div>

<?php include '../includes/footer.php'; ?>