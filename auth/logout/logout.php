<?php
session_start();
session_unset();  // Supprime toutes les variables de session
session_destroy();  // Détruit la session en cours

// Redirection vers la page de connexion
header("Location: ../login/login.php");
exit;
?>
