<?php
function escapeHtml($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function displayErrors($errors) {
    if (!empty($errors)) {
        echo '<div class="alert error">';
        foreach ($errors as $error) {
            echo '<p>' . escapeHtml($error) . '</p>';
        }
        echo '</div>';
    }
}

function cleanInput($data) {
    return trim(strip_tags($data));
}

function getSectors() {
    global $pdo;
    $stmt = $pdo->query("SELECT DISTINCT sector FROM offers ORDER BY sector");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function isActivePage($pageName) {
    return basename($_SERVER['PHP_SELF']) === $pageName ? 'active' : '';
}

function handleFileUpload($file, $allowedTypes = ['application/pdf'], $maxSize = 2097152) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Erreur lors du téléchargement du fichier");
    }

    if (!in_array($file['type'], $allowedTypes)) {
        throw new Exception("Seuls les fichiers PDF sont autorisés");
    }

    if ($file['size'] > $maxSize) {
        throw new Exception("Le fichier ne doit pas dépasser 2Mo");
    }

    $filename = uniqid() . '_' . basename($file['name']);
    $destination = __DIR__ . '/../assets/uploads/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Erreur lors de l'enregistrement du fichier");
    }

    return $filename;
}
function getApplicationCount($studentId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur count applications: " . $e->getMessage());
        return 0;
    }
}

function getTotalActiveOffers() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM offers WHERE end_date >= CURDATE()");
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur count offers: " . $e->getMessage());
        return 0;
    }
}