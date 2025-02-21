<?php
function getOffers($conn, $company_id = null) {
    $offers = [];
    try {
        if ($company_id) {
            // Récupérer les offres d'une entreprise spécifique
            $stmt = $conn->prepare("
                SELECT o.id, o.title, o.description, o.sector, o.location, o.start_date, o.end_date, o.compensation, o.created_at
                FROM offers o
                WHERE o.company_id = ?
                ORDER BY o.created_at DESC
            ");
            $stmt->bind_param("i", $company_id);
        } else {
            // Récupérer toutes les offres avec le nom de l'entreprise
            $stmt = $conn->prepare("
                SELECT o.id, o.title, o.description, o.sector, o.location, o.start_date, o.end_date, o.compensation, c.full_name AS company_name
                FROM offers o
                JOIN users c ON o.company_id = c.id
                ORDER BY o.created_at DESC
            ");
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $offers = $result->fetch_all(MYSQLI_ASSOC);
    } catch (Exception $e) {
        throw new Exception("Erreur lors de la récupération des offres: " . $e->getMessage());
    }
    return $offers;
}
?>