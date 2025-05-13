<?php
/**
 * Configuration globale de l'application
 * Ce fichier contient les paramètres de configuration de l'application
 */

// Mode de l'application (development, testing, production)
define('APP_ENV', 'development');

// Activer ou désactiver les fonctionnalités de sécurité
define('ENABLE_SECURE_SESSION', false);  // Mettre à true en production
define('ENABLE_CSRF_PROTECTION', false); // Mettre à true en production
define('ENABLE_XSS_PROTECTION', false);  // Mettre à true en production
define('ENABLE_ERROR_DISPLAY', true);    // Mettre à false en production

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'projet_stages');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration des chemins
define('BASE_PATH', __DIR__);
define('LOGS_PATH', BASE_PATH . '/logs');
define('UPLOADS_PATH', BASE_PATH . '/uploads');

// Configuration des URLs
define('BASE_URL', 'http://localhost/projet_stages');

// Configuration de la session
define('SESSION_NAME', 'SECURE_SESSION_ID');
define('SESSION_LIFETIME', 3600); // 1 heure

// Configuration des emails
define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'user@example.com');
define('MAIL_PASSWORD', 'password');
define('MAIL_FROM', 'noreply@example.com');
define('MAIL_FROM_NAME', 'Projet Stages');

// Configuration des erreurs
define('LOG_ERRORS', true);
define('ERROR_REPORTING_LEVEL', E_ALL);

// Fonction pour vérifier si l'application est en mode développement
function is_development() {
    return APP_ENV === 'development';
}

// Fonction pour vérifier si l'application est en mode test
function is_testing() {
    return APP_ENV === 'testing';
}

// Fonction pour vérifier si l'application est en mode production
function is_production() {
    return APP_ENV === 'production';
}
?>
