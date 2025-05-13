<?php
/**
 * Sécurité des mots de passe
 * Ce fichier contient les fonctions pour gérer la sécurité des mots de passe
 */

// Inclure le fichier de configuration de sécurité
require_once __DIR__ . '/security_config.php';

/**
 * Fonction pour hacher un mot de passe
 * @param string $password Le mot de passe à hacher
 * @return string Le mot de passe haché
 */
function hash_password($password) {
    // Utiliser l'algorithme de hachage BCRYPT avec un coût de 12
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Fonction pour vérifier un mot de passe
 * @param string $password Le mot de passe à vérifier
 * @param string $hash Le hachage du mot de passe
 * @return bool True si le mot de passe est valide, false sinon
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Fonction pour vérifier si un hachage de mot de passe doit être rehashé
 * @param string $hash Le hachage du mot de passe
 * @return bool True si le hachage doit être rehashé, false sinon
 */
function check_password_rehash($hash) {
    return password_needs_rehash($hash, PASSWORD_BCRYPT, ['cost' => 12]);
}

/**
 * Fonction pour générer un mot de passe aléatoire
 * @param int $length La longueur du mot de passe
 * @return string Le mot de passe généré
 */
function generate_password($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+;:,.?';
    $password = '';

    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }

    return $password;
}

/**
 * Fonction pour évaluer la force d'un mot de passe
 * @param string $password Le mot de passe à évaluer
 * @return array Un tableau contenant le score et le niveau de force du mot de passe
 */
function evaluate_password_strength($password) {
    $score = 0;
    $length = strlen($password);

    // Longueur du mot de passe
    if ($length >= 8) {
        $score += 1;
    }
    if ($length >= 12) {
        $score += 1;
    }
    if ($length >= 16) {
        $score += 1;
    }

    // Présence de lettres minuscules
    if (preg_match('/[a-z]/', $password)) {
        $score += 1;
    }

    // Présence de lettres majuscules
    if (preg_match('/[A-Z]/', $password)) {
        $score += 1;
    }

    // Présence de chiffres
    if (preg_match('/[0-9]/', $password)) {
        $score += 1;
    }

    // Présence de caractères spéciaux
    if (preg_match('/[^A-Za-z0-9]/', $password)) {
        $score += 1;
    }

    // Déterminer le niveau de force
    $strength = 'faible';
    if ($score >= 3) {
        $strength = 'moyen';
    }
    if ($score >= 5) {
        $strength = 'fort';
    }
    if ($score >= 7) {
        $strength = 'très fort';
    }

    return [
        'score' => $score,
        'strength' => $strength
    ];
}
?>
