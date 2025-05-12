<?php
/**
 * Fichier pour configurer la journalisation des erreurs
 */

// Définir le chemin du fichier de log
$logFile = __DIR__ . '/../logs/error.log';

// Créer le répertoire de logs s'il n'existe pas
if (!is_dir(dirname($logFile))) {
    mkdir(dirname($logFile), 0755, true);
}

// Configurer la journalisation des erreurs
ini_set('log_errors', 1);
ini_set('error_log', $logFile);

// Fonction pour journaliser une erreur personnalisée
function logError($message, $severity = 'ERROR') {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$severity] $message" . PHP_EOL;
    
    // Ajouter au fichier de log
    error_log($logMessage, 3, __DIR__ . '/../logs/error.log');
    
    // Retourner le message pour utilisation éventuelle
    return $logMessage;
}
?>
