<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
include_once '../../includes/db/db.php';

// Vérifier si la table login_logs existe
$tableCheckQuery = "SHOW TABLES LIKE 'login_logs'";
$tableExists = $conn->query($tableCheckQuery)->num_rows > 0;
echo "Table login_logs existe: " . ($tableExists ? "Oui" : "Non") . "<br>";

// Vérifier la structure de la table users
$userTableQuery = "DESCRIBE users";
$userTableResult = $conn->query($userTableQuery);
if ($userTableResult) {
    echo "Structure de la table users:<br>";
    echo "<pre>";
    while ($row = $userTableResult->fetch_assoc()) {
        print_r($row);
    }
    echo "</pre>";
} else {
    echo "Erreur lors de la vérification de la table users: " . $conn->error . "<br>";
}

// Tester la création de la table login_logs
if (!$tableExists) {
    $createTableSQL = "
    CREATE TABLE IF NOT EXISTS `login_logs` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `ip_address` varchar(45) NOT NULL,
      `user_agent` text NOT NULL,
      `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $result = $conn->query($createTableSQL);
    echo "Création de la table login_logs: " . ($result ? "Réussie" : "Échec - " . $conn->error) . "<br>";
}

// Vérifier si la table a été créée
$tableCheckQuery = "SHOW TABLES LIKE 'login_logs'";
$tableExists = $conn->query($tableCheckQuery)->num_rows > 0;
echo "Table login_logs existe après tentative de création: " . ($tableExists ? "Oui" : "Non") . "<br>";

// Fermer la connexion
$conn->close();
?>
