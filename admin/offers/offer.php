<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/company/functions/offres/get_offres.php'; // Inclure la fonction pour récupérer les offres

// Pagination logic
$offerPerPage = 6; // Nombre d'offres par page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1; // Récupérer le numéro de la page actuelle
$start = ($page - 1) * $offerPerPage; // Calculer l'offset

// Récupérer le nombre total d'offres
$stmtCount = $conn->prepare("SELECT COUNT(*) AS total FROM offers");
$stmtCount->execute();
$totalOffers = $stmtCount->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalOffers / $offerPerPage); // Calculer le nombre total de pages

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
   <link rel="stylesheet" href="style.css">
</head>

<body class="bg-gray-50 font-poppins">
    <!-- Sidebar -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/sidebar/sidebar.php'; ?>

    <!-- Section des offres -->
    <section class="container mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Offres de stages disponibles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (!empty($offers)): ?>
                <?php foreach ($offers as $offer): ?>
                    <div class="offer-card bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($offer['title']); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($offer['description']); ?></p>
                        <div class="space-y-2 text-gray-700">
                            <p><strong>Entreprise:</strong> <?php echo htmlspecialchars($offer['company_name']); ?></p>
                            <p><strong>Secteur:</strong> <?php echo htmlspecialchars($offer['sector']); ?></p>
                            <p><strong>Lieu:</strong> <?php echo htmlspecialchars($offer['location']); ?></p>
                            <p><strong>Date de début:</strong> <?php echo htmlspecialchars($offer['start_date']); ?></p>
                            <p><strong>Date de fin:</strong> <?php echo htmlspecialchars($offer['end_date']); ?></p>
                            <p><strong>Dernier délai:</strong> <?php echo htmlspecialchars($offer['deadline']); ?></p>
                            <p><strong>Gratification:</strong> <?php echo $offer['compensation'] ? $offer['compensation'] . ' Dz/mois' : 'Non spécifiée'; ?></p>
                        </div>
                        <div class="mt-4 flex space-x-4">
                            <button class="delete-btn text-red-600 hover:text-red-800" data-id="<?php echo $offer['id']; ?>">Supprimer</button>
                            <button class="edit-btn text-blue-600 hover:text-blue-800" data-id="<?php echo $offer['id']; ?>">Modifier</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-gray-600 text-center col-span-full">Aucune offre disponible pour le moment.</p>
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
    </section>

    <!-- Footer -->

</body>

<script src="offer.js"></script>
</html>