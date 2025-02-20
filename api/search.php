<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/auth.php';

authenticate();

try {
    // Récupération et validation des paramètres
    $params = [
        'keywords' => filter_input(INPUT_GET, 'keywords', FILTER_SANITIZE_STRING),
        'sector' => filter_input(INPUT_GET, 'sector', FILTER_SANITIZE_STRING),
        'location' => filter_input(INPUT_GET, 'location', FILTER_SANITIZE_STRING),
        'start_date' => filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_STRING),
        'end_date' => filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_STRING),
        'page' => filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]),
        'per_page' => filter_input(INPUT_GET, 'per_page', FILTER_VALIDATE_INT, ['options' => ['default' => 10, 'min_range' => 1, 'max_range' => 100]])
    ];

    // Construction de la requête SQL
    $query = "SELECT 
                o.id,
                o.title,
                o.description,
                o.sector,
                o.location,
                o.start_date,
                o.end_date,
                o.created_at,
                u.full_name as company_name
            FROM offers o
            JOIN users u ON o.company_id = u.id
            WHERE 1=1";

    $conditions = [];
    $bindings = [];

    // Filtre par mots-clés
    if (!empty($params['keywords'])) {
        $keywords = "%{$params['keywords']}%";
        $query .= " AND (o.title LIKE ? OR o.description LIKE ?)";
        array_push($conditions, $keywords, $keywords);
    }

    // Filtre par secteur
    if (!empty($params['sector'])) {
        $query .= " AND o.sector = ?";
        $conditions[] = $params['sector'];
    }

    // Filtre par localisation
    if (!empty($params['location'])) {
        $location = "%{$params['location']}%";
        $query .= " AND o.location LIKE ?";
        $conditions[] = $location;
    }

    // Filtre par dates
    if (!empty($params['start_date']) && validateDate($params['start_date'])) {
        $query .= " AND o.start_date >= ?";
        $conditions[] = $params['start_date'];
    }
    
    if (!empty($params['end_date']) && validateDate($params['end_date'])) {
        $query .= " AND o.end_date <= ?";
        $conditions[] = $params['end_date'];
    }

    // Comptage total des résultats
    $countQuery = "SELECT COUNT(*) as total FROM ($query) as subquery";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($conditions);
    $total = $countStmt->fetchColumn();

    // Pagination
    $offset = ($params['page'] - 1) * $params['per_page'];
    $query .= " ORDER BY o.created_at DESC LIMIT ? OFFSET ?";
    $conditions[] = $params['per_page'];
    $conditions[] = $offset;

    // Exécution de la requête
    $stmt = $pdo->prepare($query);
    $stmt->execute($conditions);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatage des résultats
    $response = [
        'success' => true,
        'total' => (int)$total,
        'page' => $params['page'],
        'per_page' => $params['per_page'],
        'results' => array_map('escapeOffer', $results)
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Erreur de base de données : ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

// Fonction de validation de date
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

// Fonction d'échappement des résultats
function escapeOffer($offer) {
    return [
        'id' => (int)$offer['id'],
        'title' => htmlspecialchars($offer['title']),
        'description' => htmlspecialchars($offer['description']),
        'sector' => htmlspecialchars($offer['sector']),
        'location' => htmlspecialchars($offer['location']),
        'start_date' => $offer['start_date'],
        'end_date' => $offer['end_date'],
        'created_at' => $offer['created_at'],
        'company_name' => htmlspecialchars($offer['company_name'])
    ];
}