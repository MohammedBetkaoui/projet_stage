
<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../includes/db/db.php';

$skills = [];
try {
    $stmt = $conn->prepare("SELECT * FROM skills");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $skills[] = $row;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de la récupération des compétences']);
    exit;
}

echo json_encode($skills);
?>