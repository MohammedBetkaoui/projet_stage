<?php
/**
 * Script pour créer les tables nécessaires si elles n'existent pas
 */

require_once __DIR__ . '/db.php';

// Fonction pour exécuter un fichier SQL
function executeSQLFile($conn, $filePath) {
    if (!file_exists($filePath)) {
        echo "Le fichier $filePath n'existe pas.";
        return false;
    }

    $sql = file_get_contents($filePath);
    
    if ($conn->multi_query($sql)) {
        // Consommer tous les résultats
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        
        return true;
    } else {
        echo "Erreur lors de l'exécution du script SQL: " . $conn->error;
        return false;
    }
}

// Vérifier si la table login_logs existe
$tableCheckQuery = "SHOW TABLES LIKE 'login_logs'";
$tableExists = $conn->query($tableCheckQuery)->num_rows > 0;

// Si la table n'existe pas, la créer
if (!$tableExists) {
    $sqlFilePath = $_SERVER['DOCUMENT_ROOT'] . '/sql/create_login_logs.sql';
    executeSQLFile($conn, $sqlFilePath);
    echo "Table login_logs créée avec succès.";
}

// Fermer la connexion
$conn->close();
?>
