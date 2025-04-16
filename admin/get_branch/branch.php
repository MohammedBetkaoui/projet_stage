<?php

require_once '../../includes/db/db.php';

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = 5; // Adjust based on your needs

$query = "SELECT * FROM branch LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

$branches = mysqli_fetch_all($result, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($branches);
?>
