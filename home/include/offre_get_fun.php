<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/navbar/navbar.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/company/functions/offres/get_offres.php'; 

$offerPerPage = 12; // Nombre d'offres par page

// Récupérer le numéro de la page actuelle
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $offerPerPage;

// Récupérer le nombre total d'offres
$stmtCount = $conn->prepare("SELECT COUNT(*) AS total FROM offers");
$stmtCount->execute();
$totalOffers = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalOffers / $offerPerPage);

// Récupérer les offres paginées
$offers = [];
try {
    $stmt = $conn->prepare("
        SELECT o.id, o.title,o.created_at, o.description, o.sector, o.location, o.start_date, o.end_date, o.deadline, o.compensation, c.full_name AS company_name
        FROM offers o
        JOIN users c ON o.company_id = c.id
        ORDER BY o.created_at DESC
        LIMIT ?, ?
    ");
    $stmt->bind_param("ii", $start, $offerPerPage);
    $stmt->execute();
    $result = $stmt->get_result();
    $offers = $result->fetch_all(MYSQLI_ASSOC);
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>