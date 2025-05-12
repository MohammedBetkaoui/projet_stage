<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
require_once '../includes/db/db.php';

// Vérifier si la colonne last_login existe déjà
$checkColumnQuery = "SHOW COLUMNS FROM `users` LIKE 'last_login'";
$columnExists = $conn->query($checkColumnQuery)->num_rows > 0;

if (!$columnExists) {
    // Ajouter la colonne last_login
    $alterTableQuery = "ALTER TABLE `users` ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL";
    
    if ($conn->query($alterTableQuery)) {
        echo "La colonne 'last_login' a été ajoutée avec succès à la table 'users'.";
    } else {
        echo "Erreur lors de l'ajout de la colonne 'last_login': " . $conn->error;
    }
} else {
    echo "La colonne 'last_login' existe déjà dans la table 'users'.";
}

// Fermer la connexion
$conn->close();
?>
