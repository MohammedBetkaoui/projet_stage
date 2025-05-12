<?php
/**
 * Script d'initialisation de l'application
 * Ce script est inclus au début de chaque page pour initialiser l'application
 */

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le fichier de journalisation des erreurs
require_once __DIR__ . '/includes/error_logger.php';

// Inclure le fichier de connexion à la base de données
require_once __DIR__ . '/includes/db/db.php';

// Initialiser la base de données si nécessaire
// Cette vérification permet d'éviter d'exécuter l'initialisation à chaque chargement de page
if (!isset($_SESSION['db_initialized']) || $_SESSION['db_initialized'] !== true) {
    require_once __DIR__ . '/includes/db/init_database.php';
    $_SESSION['db_initialized'] = true;
}

// Définir les constantes de l'application
define('APP_NAME', 'Projet Stages');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://' . $_SERVER['HTTP_HOST']);

// Fonction pour rediriger l'utilisateur
function redirect($url) {
    header("Location: $url");
    exit;
}

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur a un rôle spécifique
function hasRole($role) {
    return isLoggedIn() && $_SESSION['role'] === $role;
}

// Fonction pour obtenir l'ID de l'utilisateur connecté
function getUserId() {
    return isLoggedIn() ? $_SESSION['user_id'] : null;
}

// Fonction pour obtenir le nom d'utilisateur de l'utilisateur connecté
function getUsername() {
    return isLoggedIn() ? $_SESSION['username'] : null;
}

// Fonction pour obtenir le rôle de l'utilisateur connecté
function getUserRole() {
    return isLoggedIn() ? $_SESSION['role'] : null;
}
?>
