<?php
/**
 * Configuration de sécurité pour l'application
 * Ce fichier contient les paramètres et fonctions liés à la sécurité
 */

// Définir les constantes de sécurité
define('SECURE_SESSION', true);
define('SESSION_NAME', 'SECURE_SESSION_ID');
define('SESSION_LIFETIME', 3600); // 1 heure
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_EXPIRY', 3600); // 1 heure

/**
 * Fonction pour échapper les données avant de les utiliser dans une requête SQL
 * @param mixed $data Les données à échapper
 * @return mixed Les données échappées
 */
function escape_sql($data, $conn) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = escape_sql($value, $conn);
        }
        return $data;
    }
    
    if (is_string($data)) {
        return $conn->real_escape_string($data);
    }
    
    return $data;
}

/**
 * Fonction pour échapper les données avant de les afficher dans le HTML
 * @param mixed $data Les données à échapper
 * @return mixed Les données échappées
 */
function escape_html($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = escape_html($value);
        }
        return $data;
    }
    
    if (is_string($data)) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    return $data;
}

/**
 * Fonction pour générer un jeton CSRF
 * @return string Le jeton CSRF
 */
function generate_csrf_token() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();
    } else {
        // Vérifier si le jeton a expiré
        if (time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_EXPIRY) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
            $_SESSION[CSRF_TOKEN_NAME . '_time'] = time();
        }
    }
    
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Fonction pour vérifier un jeton CSRF
 * @param string $token Le jeton à vérifier
 * @return bool True si le jeton est valide, false sinon
 */
function verify_csrf_token($token) {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        return false;
    }
    
    // Vérifier si le jeton a expiré
    if (time() - $_SESSION[CSRF_TOKEN_NAME . '_time'] > CSRF_TOKEN_EXPIRY) {
        return false;
    }
    
    return hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}

/**
 * Fonction pour valider une adresse email
 * @param string $email L'adresse email à valider
 * @return bool True si l'adresse email est valide, false sinon
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Fonction pour valider un numéro de téléphone
 * @param string $phone Le numéro de téléphone à valider
 * @return bool True si le numéro de téléphone est valide, false sinon
 */
function validate_phone($phone) {
    // Format international: +XX XX XX XX XX
    // Format national: 0X XX XX XX XX
    return preg_match('/^(\+\d{1,3}[- ]?)?\d{9,10}$/', $phone) === 1;
}

/**
 * Fonction pour valider un mot de passe
 * @param string $password Le mot de passe à valider
 * @return array Un tableau contenant un booléen indiquant si le mot de passe est valide et un message d'erreur le cas échéant
 */
function validate_password($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins une lettre majuscule";
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins une lettre minuscule";
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins un chiffre";
    }
    
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = "Le mot de passe doit contenir au moins un caractère spécial";
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}
?>
