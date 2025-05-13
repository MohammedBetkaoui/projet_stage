<?php
/**
 * Fichier pour configurer la journalisation des erreurs
 */

// Définir les constantes pour les niveaux de journalisation
define('LOG_LEVEL_DEBUG', 1);
define('LOG_LEVEL_INFO', 2);
define('LOG_LEVEL_WARNING', 3);
define('LOG_LEVEL_ERROR', 4);
define('LOG_LEVEL_CRITICAL', 5);

// Définir le niveau de journalisation actuel (ajuster selon l'environnement)
define('CURRENT_LOG_LEVEL', LOG_LEVEL_INFO); // En production, utiliser LOG_LEVEL_WARNING ou LOG_LEVEL_ERROR

// Définir les chemins des fichiers de log
$errorLogFile = __DIR__ . '/../logs/error.log';
$accessLogFile = __DIR__ . '/../logs/access.log';
$securityLogFile = __DIR__ . '/../logs/security.log';

// Créer le répertoire de logs s'il n'existe pas
if (!is_dir(dirname($errorLogFile))) {
    mkdir(dirname($errorLogFile), 0755, true);
}

// Configurer la journalisation des erreurs PHP
ini_set('log_errors', 1);
ini_set('error_log', $errorLogFile);
ini_set('display_errors', 0); // Désactiver l'affichage des erreurs en production

// Fonction pour journaliser une erreur personnalisée
function logError($message, $severity = 'ERROR', $context = []) {
    global $errorLogFile, $securityLogFile, $accessLogFile;

    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
    $user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Guest';
    $url = $_SERVER['REQUEST_URI'] ?? 'Unknown';

    // Convertir le niveau de sévérité en niveau numérique
    $numericLevel = getNumericLogLevel($severity);

    // Vérifier si le niveau de journalisation est suffisant
    if ($numericLevel < CURRENT_LOG_LEVEL) {
        return null;
    }

    // Formater le message de log
    $contextStr = !empty($context) ? ' ' . json_encode($context) : '';
    $logMessage = "[$timestamp] [$severity] [$ip] [User:$user] [$url] $message$contextStr" . PHP_EOL;

    // Ajouter au fichier de log approprié
    $logFile = $errorLogFile;
    if ($severity === 'SECURITY') {
        $logFile = $securityLogFile;
    } elseif ($severity === 'ACCESS') {
        $logFile = $accessLogFile;
    }

    // Écrire dans le fichier de log
    error_log($logMessage, 3, $logFile);

    // Retourner le message pour utilisation éventuelle
    return $logMessage;
}

// Fonction pour journaliser un accès
function logAccess($action, $status = 'SUCCESS', $context = []) {
    return logError($action, 'ACCESS', array_merge(['status' => $status], $context));
}

// Fonction pour journaliser un événement de sécurité
function logSecurity($action, $status = 'WARNING', $context = []) {
    return logError($action, 'SECURITY', array_merge(['status' => $status], $context));
}

// Fonction pour convertir un niveau de sévérité en niveau numérique
function getNumericLogLevel($severity) {
    $levels = [
        'DEBUG' => LOG_LEVEL_DEBUG,
        'INFO' => LOG_LEVEL_INFO,
        'WARNING' => LOG_LEVEL_WARNING,
        'ERROR' => LOG_LEVEL_ERROR,
        'CRITICAL' => LOG_LEVEL_CRITICAL,
        'ACCESS' => LOG_LEVEL_INFO,
        'SECURITY' => LOG_LEVEL_WARNING
    ];

    return $levels[$severity] ?? LOG_LEVEL_INFO;
}

// Gestionnaire d'erreurs personnalisé
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $severity = 'ERROR';

    switch ($errno) {
        case E_ERROR:
        case E_CORE_ERROR:
        case E_COMPILE_ERROR:
        case E_PARSE:
            $severity = 'CRITICAL';
            break;
        case E_WARNING:
        case E_CORE_WARNING:
        case E_COMPILE_WARNING:
        case E_USER_WARNING:
            $severity = 'WARNING';
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $severity = 'INFO';
            break;
        case E_DEPRECATED:
        case E_USER_DEPRECATED:
            $severity = 'DEBUG';
            break;
    }

    logError($errstr, $severity, [
        'file' => $errfile,
        'line' => $errline,
        'errno' => $errno
    ]);

    // Ne pas exécuter le gestionnaire d'erreurs interne de PHP
    return true;
}

// Gestionnaire d'exceptions personnalisé
function customExceptionHandler($exception) {
    logError($exception->getMessage(), 'CRITICAL', [
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);

    // Afficher une page d'erreur conviviale
    if (!headers_sent()) {
        header('HTTP/1.1 500 Internal Server Error');
        include __DIR__ . '/../error.php';
    } else {
        echo '<div style="color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px 0; border-radius: 5px;">Une erreur est survenue. Veuillez réessayer plus tard.</div>';
    }

    exit(1);
}

// Définir les gestionnaires d'erreurs et d'exceptions
set_error_handler('customErrorHandler');
set_exception_handler('customExceptionHandler');
?>
