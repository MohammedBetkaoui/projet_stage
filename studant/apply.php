<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données

// Vérifier si l'utilisateur est connecté et est un étudiant
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login/login.php');
    exit();
}
if ($_SESSION['role'] !== 'student') {
    header('Location: /home/index.php');
    exit();
}

// Récupérer l'ID de l'offre depuis l'URL
if (!isset($_GET['id'])) {
    header('Location: /home/index.php');
    exit();
}
$offer_id = intval($_GET['id']);

// Récupérer les détails de l'offre
$offer = [];
try {
    $stmt = $conn->prepare("SELECT * FROM offers WHERE id = ?");
    $stmt->bind_param("i", $offer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        header('Location: /home/index.php');
        exit();
    }
    $offer = $result->fetch_assoc();
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}

// Traitement du formulaire de candidature
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['user_id'];
    $cover_letter = trim($_POST['cover_letter']);
    $cv_path = '';

    // Validation des champs
    if (empty($cover_letter)) {
        $error = "Veuillez rédiger une lettre de motivation.";
    } elseif (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        $error = "Veuillez télécharger un CV.";
    } else {
        // Télécharger le CV
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/cvs/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $cv_name = basename($_FILES['cv']['name']);
        $cv_path = $upload_dir . $cv_name;
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path)) {
            $cv_path = '/uploads/cvs/' . $cv_name;
        } else {
            $error = "Erreur lors du téléchargement du CV.";
        }
    }

    // Enregistrer la candidature dans la base de données
    if (!isset($error)) {
        try {
            $stmt = $conn->prepare("INSERT INTO applications (student_id, offer_id, cv_path, cover_letter, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->bind_param("iiss", $student_id, $offer_id, $cv_path, $cover_letter);
            $stmt->execute();
            $success = "Votre candidature a été soumise avec succès !";
        } catch (Exception $e) {
            $error = "Erreur lors de la soumission de la candidature : " . $e->getMessage();
        }
        header('Location: /home/index.php');
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler à une offre</title>
    <link rel="stylesheet" href="./apply.css"> <!-- Lien vers le fichier CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/navbar/navbar.php'; ?>

    <!-- Contenu principal -->
    <section class="main-content">
        <div class="form-container">
            <h2>Postuler à l'offre : <?php echo htmlspecialchars($offer['title']); ?></h2>

            <!-- Alertes -->
            <?php if (isset($error)): ?>
                <div class="alert error">
                    <i class='bx bx-error-circle'></i>
                    <span><?php echo $error; ?></span>
                    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div class="alert success">
                    <i class='bx bx-check-circle'></i>
                    <span><?php echo $success; ?></span>
                    <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
                </div>
            <?php endif; ?>

            <!-- Formulaire de candidature -->
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="cv">Télécharger votre CV (PDF ou Word) *</label>
                    <div class="file-upload">
                        <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required onchange="previewFile()">
                        <label for="cv" class="file-upload-label">
                            <i class='bx bx-upload'></i> Choisir un fichier
                        </label>
                    </div>
                    <!-- Cadre pour afficher l'aperçu du CV -->
                    <div id="file-preview" class="file-preview">
                        <p>Aucun fichier sélectionné</p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cover_letter">Lettre de motivation *</label>
                    <textarea id="cover_letter" name="cover_letter" rows="10" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Soumettre ma candidature</button>
            </form>
        </div>
    </section>

    <!-- Script JavaScript pour l'aperçu du fichier -->
    <script>
        function previewFile() {
            const fileInput = document.getElementById('cv');
            const filePreview = document.getElementById('file-preview');

            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const reader = new FileReader();

                reader.onload = function (e) {
                    if (file.type === 'application/pdf') {
                        // Afficher un aperçu pour les fichiers PDF
                        filePreview.innerHTML = `
                            <embed src="${e.target.result}" type="application/pdf" width="100%" height="200px">
                            <p>${file.name}</p>
                        `;
                    } else if (file.type === 'application/msword' || file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
                        // Afficher un message pour les fichiers Word
                        filePreview.innerHTML = `
                            <p>Fichier Word sélectionné : ${file.name}</p>
                        `;
                    } else {
                        // Afficher un message pour les autres types de fichiers
                        filePreview.innerHTML = `
                            <p>Fichier sélectionné : ${file.name}</p>
                        `;
                    }
                };

                reader.readAsDataURL(file);
            } else {
                filePreview.innerHTML = '<p>Aucun fichier sélectionné</p>';
            }
        }
    </script>
</body>
</html>