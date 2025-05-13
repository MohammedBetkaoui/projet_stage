<?php
/**
 * Protection contre les attaques XSS (Cross-Site Scripting)
 * Ce fichier contient les fonctions pour protéger contre les attaques XSS
 */

/**
 * Fonction pour nettoyer les données d'entrée
 * @param mixed $data Les données à nettoyer
 * @return mixed Les données nettoyées
 */
function clean_input($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = clean_input($value);
        }
        return $data;
    }
    
    if (is_string($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    return $data;
}

/**
 * Fonction pour nettoyer les données de sortie
 * @param mixed $data Les données à nettoyer
 * @return mixed Les données nettoyées
 */
function clean_output($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = clean_output($value);
        }
        return $data;
    }
    
    if (is_string($data)) {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    return $data;
}

/**
 * Fonction pour nettoyer les données avant de les utiliser dans du JavaScript
 * @param mixed $data Les données à nettoyer
 * @return mixed Les données nettoyées
 */
function clean_js($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = clean_js($value);
        }
        return $data;
    }
    
    if (is_string($data)) {
        $data = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
    
    return $data;
}

/**
 * Fonction pour nettoyer les données avant de les utiliser dans une URL
 * @param mixed $data Les données à nettoyer
 * @return mixed Les données nettoyées
 */
function clean_url($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = clean_url($value);
        }
        return $data;
    }
    
    if (is_string($data)) {
        $data = urlencode($data);
    }
    
    return $data;
}

/**
 * Fonction pour nettoyer les données avant de les utiliser dans un attribut HTML
 * @param mixed $data Les données à nettoyer
 * @return mixed Les données nettoyées
 */
function clean_attr($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = clean_attr($value);
        }
        return $data;
    }
    
    if (is_string($data)) {
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    return $data;
}

/**
 * Fonction pour nettoyer les données avant de les utiliser dans du CSS
 * @param mixed $data Les données à nettoyer
 * @return mixed Les données nettoyées
 */
function clean_css($data) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = clean_css($value);
        }
        return $data;
    }
    
    if (is_string($data)) {
        // Supprimer les caractères potentiellement dangereux
        $data = preg_replace('/[^a-zA-Z0-9\s\.\-\,\:\;\#]/', '', $data);
    }
    
    return $data;
}
?>
