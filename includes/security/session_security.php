<?php
/**
 * Gestion de la sécurité des sessions
 * Ce fichier contient les fonctions pour sécuriser les sessions
 */

// Inclure le fichier de configuration de sécurité
require_once __DIR__ . '/security_config.php';

/**
 * Fonction pour initialiser une session sécurisée
 */
function secure_session_start() {
    // Définir les paramètres de cookie de session
    $session_name = SESSION_NAME;
    $secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    $httponly = true;
    
    // Définir le nom de la session
    session_name($session_name);
    
    // Définir les paramètres de cookie
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => $secure,
        'httponly' => $httponly,
        'samesite' => 'Lax'
    ]);
    
    // Démarrer la session
    session_start();
    
    // Régénérer l'ID de session pour éviter la fixation de session
    if (!isset($_SESSION['created'])) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) {
        // Régénérer l'ID de session toutes les 30 minutes
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

/**
 * Fonction pour détruire une session
 */
function secure_session_destroy() {
    // Récupérer les paramètres de session
    $params = session_get_cookie_params();
    
    // Vider le cookie de session
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
    
    // Détruire la session
    session_destroy();
}

/**
 * Fonction pour vérifier si l'utilisateur est connecté
 * @return bool True si l'utilisateur est connecté, false sinon
 */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Fonction pour vérifier si l'utilisateur a un rôle spécifique
 * @param string $role Le rôle à vérifier
 * @return bool True si l'utilisateur a le rôle spécifié, false sinon
 */
function has_role($role) {
    return is_logged_in() && $_SESSION['role'] === $role;
}

/**
 * Fonction pour vérifier si l'utilisateur a l'un des rôles spécifiés
 * @param array $roles Les rôles à vérifier
 * @return bool True si l'utilisateur a l'un des rôles spécifiés, false sinon
 */
function has_any_role($roles) {
    if (!is_logged_in()) {
        return false;
    }
    
    return in_array($_SESSION['role'], $roles);
}

/**
 * Fonction pour rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: /auth/login/login.php');
        exit();
    }
}

/**
 * Fonction pour rediriger l'utilisateur vers la page d'accueil s'il n'a pas le rôle spécifié
 * @param string $role Le rôle requis
 */
function require_role($role) {
    require_login();
    
    if (!has_role($role)) {
        header('Location: /home/index.php');
        exit();
    }
}

/**
 * Fonction pour rediriger l'utilisateur vers la page d'accueil s'il n'a pas l'un des rôles spécifiés
 * @param array $roles Les rôles requis
 */
function require_any_role($roles) {
    require_login();
    
    if (!has_any_role($roles)) {
        header('Location: /home/index.php');
        exit();
    }
}
?>
