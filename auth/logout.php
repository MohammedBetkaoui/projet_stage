<?php
session_start();

// Détruire toutes les données de session
$_SESSION = [];

// Si vous voulez détruire complètement la session, supprimez également
// le cookie de session.
// Note : cela détruira la session et pas seulement les données de session !
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalement, on détruit la session.
session_destroy();

// Redirection vers la page d'accueil avec un message de succès
$_SESSION['success'] = "Vous avez été déconnecté avec succès.";
header('Location: /index.php');
exit;