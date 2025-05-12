<?php
// Désactiver l'affichage des erreurs pour éviter de casser le JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Paramètres de connexion
$host = 'localhost';
$dbname = 'projet_stages';
$username = 'root';
$password = '';

// Créer la connexion avec gestion d'erreurs
try {
    $conn = new mysqli($host, $username, $password, $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        throw new Exception("Connexion échouée : " . $conn->connect_error);
    }

    // Définir le jeu de caractères
    $conn->set_charset("utf8mb4");

    // Note: L'initialisation complète de la base de données est maintenant gérée par init_database.php
    // Ce fichier est inclus séparément lorsque nécessaire
} catch (Exception $e) {
    // En cas d'erreur de connexion, enregistrer l'erreur et renvoyer une erreur
    error_log("Erreur de connexion à la base de données: " . $e->getMessage());

    // Si nous sommes dans un contexte d'API, renvoyer une erreur JSON
    if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Erreur de connexion à la base de données']);
        exit;
    }

    // Sinon, afficher un message d'erreur générique
    die("Une erreur est survenue lors de la connexion à la base de données. Veuillez réessayer plus tard.");
}
?>
