<?php
/**
 * Script d'initialisation de la base de données
 * Ce script vérifie et crée toutes les tables et colonnes nécessaires
 */

// Inclure le fichier de journalisation des erreurs
require_once __DIR__ . '/../error_logger.php';

// Inclure le fichier de connexion à la base de données
require_once __DIR__ . '/db.php';

/**
 * Vérifie si une table existe
 * @param mysqli $conn Connexion à la base de données
 * @param string $tableName Nom de la table
 * @return bool True si la table existe, false sinon
 */
function tableExists($conn, $tableName) {
    $result = $conn->query("SHOW TABLES LIKE '$tableName'");
    return $result->num_rows > 0;
}

/**
 * Vérifie si une colonne existe dans une table
 * @param mysqli $conn Connexion à la base de données
 * @param string $tableName Nom de la table
 * @param string $columnName Nom de la colonne
 * @return bool True si la colonne existe, false sinon
 */
function columnExists($conn, $tableName, $columnName) {
    $result = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE '$columnName'");
    return $result->num_rows > 0;
}

/**
 * Crée la table login_logs si elle n'existe pas
 * @param mysqli $conn Connexion à la base de données
 */
function createLoginLogsTable($conn) {
    if (!tableExists($conn, 'login_logs')) {
        $sql = "
        CREATE TABLE IF NOT EXISTS `login_logs` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `ip_address` varchar(45) NOT NULL,
          `user_agent` text NOT NULL,
          `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        if ($conn->query($sql)) {
            logError("Table 'login_logs' créée avec succès", 'INFO');
        } else {
            logError("Erreur lors de la création de la table 'login_logs': " . $conn->error, 'ERROR');
        }
    }
}

/**
 * Ajoute la colonne last_login à la table users si elle n'existe pas
 * @param mysqli $conn Connexion à la base de données
 */
function addLastLoginColumn($conn) {
    if (tableExists($conn, 'users') && !columnExists($conn, 'users', 'last_login')) {
        $sql = "ALTER TABLE `users` ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL";
        
        if ($conn->query($sql)) {
            logError("Colonne 'last_login' ajoutée à la table 'users'", 'INFO');
        } else {
            logError("Erreur lors de l'ajout de la colonne 'last_login': " . $conn->error, 'ERROR');
        }
    }
}

// Exécuter les fonctions d'initialisation
try {
    createLoginLogsTable($conn);
    addLastLoginColumn($conn);
    logError("Initialisation de la base de données terminée", 'INFO');
} catch (Exception $e) {
    logError("Erreur lors de l'initialisation de la base de données: " . $e->getMessage(), 'ERROR');
}

// Fermer la connexion
$conn->close();
?>
