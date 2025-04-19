<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    
}
$role = $_SESSION['role']?? 'guest' ;

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/navbar/navbar.php'; // Inclure la navbar
require_once $_SERVER['DOCUMENT_ROOT'] . '/company/functions/offres/get_offres.php'; // Inclure la fonction pour récupérer les offres

// Récupérer les 8 dernières offres disponibles (non dépassées)
$offers = [];
try {
    $stmt = $conn->prepare("
        SELECT o.id, o.title,o.created_at, o.description, o.sector, o.location, o.start_date, o.end_date, o.deadline, o.compensation, c.full_name AS company_name
        FROM offers o
        JOIN users c ON o.company_id = c.id
        WHERE o.deadline >= CURDATE() -- Filtrer les offres non dépassées
        ORDER BY o.created_at DESC
        LIMIT 8 -- Limiter à 8 offres
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    $offers = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
