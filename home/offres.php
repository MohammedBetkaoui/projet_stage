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
        SELECT o.id, o.title, o.description, o.sector, o.location, o.start_date, o.end_date, o.deadline, o.compensation, c.full_name AS company_name
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Plateforme de stages</title>
    <link rel="stylesheet" href="/assets/css/index.css">
     <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php include '../includes/header/header.php'; ?>

    <!-- Section des offres -->
    <section class="offers-section">
        <h2>Offres de stages disponibles</h2>
        <br>
        <?php if (!isset($_SESSION['user_id'])) { ?>
            <p>Remarque: <mark> Vous devez vous connecter pour postuler</mark></p>
        <?php } ?>
        <br>
        <div class="offers-container">
            <?php if (!empty($offers)): ?>
                <?php foreach ($offers as $offer): ?>
                    <div class="offer-card">
                        <h3><?php echo htmlspecialchars($offer['title']); ?></h3>
                        <p><?php echo htmlspecialchars($offer['description']); ?></p>
                        <div class="details">
                            <span><strong>Entreprise:</strong> <?php echo htmlspecialchars($offer['company_name']); ?></span>
                            <span><strong>Secteur:</strong> <?php echo htmlspecialchars($offer['sector']); ?></span>
                            <span><strong>Lieu:</strong> <?php echo htmlspecialchars($offer['location']); ?></span>
                            <span><strong>Date de début:</strong> <?php echo htmlspecialchars($offer['start_date']); ?></span>
                            <span><strong>Date de fin:</strong> <?php echo htmlspecialchars($offer['end_date']); ?></span>
                            <span><strong>Dernier délai:</strong> <?php echo htmlspecialchars($offer['deadline']); ?></span>
                            <span><strong>Gratification:</strong> <?php echo $offer['compensation'] ? $offer['compensation'] . ' Dz/mois' : 'Non spécifiée'; ?></span>
                        </div>
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'student'){ ?>
                            <a href="/studant/apply/apply.php?id=<?php echo $offer['id']; ?>" class="btn">Postuler</a>
                        <?php } else { ?>
                            <p class="auth-message">Seuls les étudiants peuvent postuler.</p>
                        <?php } ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-offers">Aucune offre disponible pour le moment.</p>
            <?php endif; ?>
        </div>

        
        <!-- Pagination -->
        <div class="flex justify-center items-center space-x-4 my-8">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Précédent</a>
            <?php endif; ?>

            <span class="text-gray-700">Page <?php echo $page; ?> sur <?php echo $totalPages; ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Suivant</a>
            <?php endif; ?>
        </div>
        <br><br>
    </section>
             
    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
