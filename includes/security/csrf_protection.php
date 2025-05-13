<?php
/**
 * Protection contre les attaques CSRF (Cross-Site Request Forgery)
 * Ce fichier contient les fonctions pour protéger contre les attaques CSRF
 */

// Inclure le fichier de configuration de sécurité
require_once __DIR__ . '/security_config.php';

/**
 * Fonction pour générer un champ de formulaire contenant un jeton CSRF
 * @return string Le champ de formulaire HTML
 */
function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $token . '">';
}

/**
 * Fonction pour générer un jeton CSRF à utiliser dans les en-têtes AJAX
 * @return string Le jeton CSRF
 */
function csrf_token() {
    return generate_csrf_token();
}

/**
 * Fonction pour vérifier un jeton CSRF dans une requête POST
 * @return bool True si le jeton est valide, false sinon
 */
function csrf_verify() {
    if (!isset($_POST[CSRF_TOKEN_NAME])) {
        return false;
    }
    
    return verify_csrf_token($_POST[CSRF_TOKEN_NAME]);
}

/**
 * Fonction pour vérifier un jeton CSRF dans une requête AJAX
 * @return bool True si le jeton est valide, false sinon
 */
function csrf_verify_ajax() {
    $headers = getallheaders();
    
    if (!isset($headers['X-CSRF-TOKEN'])) {
        return false;
    }
    
    return verify_csrf_token($headers['X-CSRF-TOKEN']);
}

/**
 * Fonction pour protéger une requête POST contre les attaques CSRF
 * Redirige vers une page d'erreur si le jeton CSRF est invalide
 */
function csrf_protect() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!csrf_verify()) {
            // Journaliser la tentative
            error_log('Tentative CSRF détectée: ' . $_SERVER['REMOTE_ADDR']);
            
            // Rediriger vers une page d'erreur
            header('Location: /error.php?code=403');
            exit();
        }
    }
}

/**
 * Fonction pour protéger une requête AJAX contre les attaques CSRF
 * Renvoie une erreur JSON si le jeton CSRF est invalide
 */
function csrf_protect_ajax() {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        if (!csrf_verify_ajax()) {
            // Journaliser la tentative
            error_log('Tentative CSRF AJAX détectée: ' . $_SERVER['REMOTE_ADDR']);
            
            // Renvoyer une erreur JSON
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Jeton CSRF invalide']);
            exit();
        }
    }
}

/**
 * Fonction pour générer un script JavaScript qui ajoute automatiquement le jeton CSRF aux requêtes AJAX
 * @return string Le script JavaScript
 */
function csrf_ajax_script() {
    $token = csrf_token();
    
    return <<<HTML
<script>
    // Ajouter le jeton CSRF à toutes les requêtes AJAX
    (function() {
        // Stocker la fonction open d'origine
        var originalOpen = XMLHttpRequest.prototype.open;
        
        // Remplacer la fonction open
        XMLHttpRequest.prototype.open = function() {
            // Appeler la fonction open d'origine
            originalOpen.apply(this, arguments);
            
            // Ajouter l'en-tête CSRF
            this.setRequestHeader('X-CSRF-TOKEN', '$token');
        };
        
        // Si jQuery est utilisé
        if (typeof jQuery !== 'undefined') {
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '$token'
                }
            });
        }
    })();
</script>
HTML;
}
?>
