<?php
/**
 * Script d'initialisation de l'application
 * Ce script est inclus au début de chaque page pour initialiser l'application
 */

// Inclure le fichier de configuration
require_once __DIR__ . '/config.php';

// Inclure le fichier de journalisation des erreurs
require_once __DIR__ . '/includes/error_logger.php';

// Configuration de l'affichage des erreurs
if (ENABLE_ERROR_DISPLAY) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(ERROR_REPORTING_LEVEL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}

// Inclure les fichiers de sécurité
require_once __DIR__ . '/includes/security/security_config.php';

// Activer les fonctionnalités de sécurité selon la configuration
if (ENABLE_SECURE_SESSION) {
    require_once __DIR__ . '/includes/security/session_security.php';
    secure_session_start();
} else {
    // Démarrer la session de manière traditionnelle
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Inclure les autres fichiers de sécurité si activés
if (ENABLE_XSS_PROTECTION) {
    require_once __DIR__ . '/includes/security/xss_protection.php';
}

if (ENABLE_CSRF_PROTECTION) {
    require_once __DIR__ . '/includes/security/csrf_protection.php';
}

// Toujours inclure la sécurité des mots de passe
require_once __DIR__ . '/includes/security/password_security.php';

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
define('APP_URL', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://' . $_SERVER['HTTP_HOST']);
define('APP_ROOT', __DIR__);

// Journaliser l'accès à la page
logAccess('Page accessed: ' . $_SERVER['REQUEST_URI']);

// Fonction pour rediriger l'utilisateur
function redirect($url) {
    header("Location: $url");
    exit;
}

// Fonction pour nettoyer les données de sortie
function clean_output_buffer() {
    $buffer = ob_get_clean();
    if (ENABLE_XSS_PROTECTION && function_exists('clean_output')) {
        echo clean_output($buffer);
    } else {
        echo $buffer;
    }
}

// Démarrer la mise en mémoire tampon de sortie
ob_start('clean_output_buffer');

// Fonction pour obtenir l'URL actuelle
function current_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

// Fonction pour obtenir l'URL de base
function base_url($path = '') {
    return APP_URL . '/' . ltrim($path, '/');
}

// Fonction pour obtenir l'URL d'un asset
function asset_url($path) {
    return base_url('assets/' . ltrim($path, '/'));
}

// Fonction pour inclure un fichier de vue
function view($path, $data = []) {
    extract($data);
    include APP_ROOT . '/views/' . $path . '.php';
}

// Fonction pour générer un jeton CSRF pour les formulaires
function csrf_input() {
    if (ENABLE_CSRF_PROTECTION && function_exists('csrf_field')) {
        return csrf_field();
    }
    return '';
}

// Fonction pour vérifier si une requête est une requête AJAX
function is_ajax_request() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

// Fonction pour renvoyer une réponse JSON
function json_response($data, $status = 200) {
    header('Content-Type: application/json');
    http_response_code($status);
    echo json_encode($data);
    exit;
}
?>
