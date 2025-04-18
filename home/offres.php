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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Plateforme de stages</title>
    <link rel="stylesheet" href="/assets/css/index.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#10B981',
                        dark: '#1F2937',
                        light: '#F9FAFB',
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4F46E5 0%, #10B981 100%);
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .transition-all {
            transition: all 0.3s ease;
        }
        .timeline-step {
            position: relative;
            padding-left: 2rem;
        }
        .timeline-step:before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 50%;
            background: #E5E7EB;
        }
        .timeline-step.active:before {
            background: #10B981;
        }
        .timeline-step.completed:before {
            background: #10B981;
            content: '✓';
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }
        .timeline-step.rejected:before {
            background: #EF4444;
            content: '✕';
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }
    </style>
<body>
<div class="gradient-bg">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 lg:py-24">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight text-white md:text-5xl lg:text-6xl">
                        Trouvez le stage parfait pour votre avenir
                    </h1>
                    <p class="mt-3 max-w-3xl text-lg text-indigo-100">
                        StageFinder connecte les étudiants avec les meilleures opportunités de stage. Explorez des milliers d'offres et lancez votre carrière dès aujourd'hui.
                    </p>
                    <div class="mt-8 sm:flex">
                        <div class="rounded-md shadow">
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10">
                                Rechercher des stages
                            </a>
                        </div>
                        <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 bg-opacity-60 hover:bg-opacity-70 md:py-4 md:text-lg md:px-10">
                                Je suis une entreprise
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>


    <!-- Section des offres -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Explorez par domaine
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Trouvez des stages dans les domaines qui vous passionnent.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-6">
                <a href="#" class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-all card-hover">
                    <div class="bg-indigo-50 p-3 rounded-full mb-3">
                        <i class="fas fa-code text-indigo-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Informatique</h3>
                </a>
                <a href="#" class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-all card-hover">
                    <div class="bg-green-50 p-3 rounded-full mb-3">
                        <i class="fas fa-chart-line text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Marketing</h3>
                </a>
                <a href="#" class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-all card-hover">
                    <div class="bg-blue-50 p-3 rounded-full mb-3">
                        <i class="fas fa-paint-brush text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Design</h3>
                </a>
                <a href="#" class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-all card-hover">
                    <div class="bg-purple-50 p-3 rounded-full mb-3">
                        <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Finance</h3>
                </a>
                <a href="#" class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-all card-hover">
                    <div class="bg-red-50 p-3 rounded-full mb-3">
                        <i class="fas fa-heartbeat text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Santé</h3>
                </a>
                <a href="#" class="bg-white rounded-lg shadow p-6 flex flex-col items-center hover:shadow-lg transition-all card-hover">
                    <div class="bg-yellow-50 p-3 rounded-full mb-3">
                        <i class="fas fa-gavel text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Droit</h3>
                </a>
            </div>
        </div>
    <!-- Section des offres -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-dark">Dernières offres de stages</h2>
        <a href="./offres.php" class="text-primary hover:text-indigo-700 font-medium">Voir toutes les offres →</a>
    </div>

    <?php if (!isset($_SESSION['user_id'])): ?>
        <div class="mb-6">
            <p class="text-gray-600">Remarque: <mark class="bg-yellow-100 px-2 py-1 rounded">Vous devez vous connecter pour postuler</mark></p>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <?php if (!empty($offers)): ?>
            <?php foreach ($offers as $offer): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transition-all hover:shadow-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-dark">
                                    <?php echo htmlspecialchars($offer['title']); ?>
                                </h3>
                                <p class="text-gray-500">
                                    <?php echo htmlspecialchars($offer['company_name']); ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-2"></i>
                                <?php echo htmlspecialchars($offer['location']); ?>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <?php 
                                    echo htmlspecialchars($offer['start_date']) . ' - ' . 
                                    htmlspecialchars($offer['end_date']);
                                ?>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-clock mr-2"></i>
                                Dernier délai: <?php echo htmlspecialchars($offer['deadline']); ?>
                            </div>
                            
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-coins mr-2"></i>
                                <?php echo $offer['compensation'] ? 
                                    htmlspecialchars($offer['compensation']) . ' Dz/mois' : 
                                    'Gratification non spécifiée'; ?>
                            </div>
                        </div>

                        <div class="mt-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo htmlspecialchars($offer['sector']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                        <span class="text-sm text-gray-500">
                            Publiée le <?php echo htmlspecialchars($offer['created_at']); ?>
                        </span>
                        
                        <?php if($role == 'student'): ?>
                            <a 
                                href="/studant/apply/apply.php?id=<?php echo $offer['id']; ?>" 
                                class="px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-indigo-700 transition-colors"
                            >
                                Postuler
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-span-full text-center py-8">
                <p class="text-gray-500 text-lg">Aucune offre disponible pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

             
    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
