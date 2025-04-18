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

    <!-- Section "Pourquoi nous choisir ?" -->
    <div class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight text-dark sm:text-4xl">
                    Comment ça marche ?
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Trouvez le stage idéal en seulement quelques étapes simples
                </p>
            </div>
            
            <div class="mt-16">
                <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                    <div class="relative">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="mt-6 text-lg font-medium text-dark">1. Recherchez</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Utilisez nos filtres avancés pour trouver des offres correspondant à vos critères : secteur, localisation, durée, rémunération, etc.
                        </p>
                    </div>
                    
                    <div class="mt-10 lg:mt-0 relative">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h3 class="mt-6 text-lg font-medium text-dark">2. Postulez</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Envoyez votre candidature en quelques clics avec votre CV et lettre de motivation. Suivez l'état de vos candidatures en temps réel.
                        </p>
                    </div>
                    
                    <div class="mt-10 lg:mt-0 relative">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="mt-6 text-lg font-medium text-dark">3. Décrochez</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Recevez des réponses directement des entreprises et organisez vos entretiens. Prêt à commencer votre stage !
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section "Témoignages" -->
    <div class="bg-primary">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 lg:items-center">
                <div class="lg:col-span-1">
                    <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                        Ce que disent nos utilisateurs
                    </h2>
                    <p class="mt-3 max-w-3xl text-lg text-indigo-100">
                        Découvrez les témoignages d'étudiants et d'entreprises qui ont trouvé leur bonheur grâce à StageFinder.
                    </p>
                </div>
                <div class="mt-12 lg:mt-0 lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
                        <div class="px-6 py-8 sm:p-10">
                            <div class="relative">
                                <div class="absolute top-0 left-0 transform -translate-x-1 -translate-y-1">
                                    <svg class="h-8 w-8 text-indigo-200" fill="currentColor" viewBox="0 0 32 32">
                                        <path d="M9.352 4C4.456 7.456 1 13.12 1 19.36c0 5.088 3.072 8.064 6.624 8.064 3.36 0 5.856-2.688 5.856-5.856 0-3.168-2.208-5.472-5.088-5.472-.576 0-1.344.096-1.536.192.48-3.264 3.552-7.104 6.624-9.024L9.352 4zm16.512 0c-4.8 3.456-8.256 9.12-8.256 15.36 0 5.088 3.072 8.064 6.624 8.064 3.264 0 5.856-2.688 5.856-5.856 0-3.168-2.304-5.472-5.184-5.472-.576 0-1.248.096-1.44.192.48-3.264 3.456-7.104 6.528-9.024L25.864 4z" />
                                    </svg>
                                </div>
                                <blockquote class="relative">
                                    <p class="text-base text-gray-700">
                                        Grâce à StageFinder, j'ai trouvé un stage dans une entreprise qui correspondait parfaitement à mes attentes. L'interface est intuitive et le suivi des candidatures très pratique.
                                    </p>
                                    <footer class="mt-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">Sophie Martin</p>
                                                <p class="text-sm text-gray-500">Étudiante en Marketing Digital</p>
                                            </div>
                                        </div>
                                    </footer>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 lg:py-24">
            <div class="bg-primary rounded-lg shadow-xl overflow-hidden lg:grid lg:grid-cols-2 lg:gap-4">
                <div class="pt-10 pb-12 px-6 sm:pt-16 sm:px-16 lg:py-16 lg:pr-0 xl:py-20 xl:px-20">
                    <div class="lg:self-center">
                        <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                            <span class="block">Prêt à trouver votre stage idéal ?</span>
                        </h2>
                        <p class="mt-4 text-lg leading-6 text-indigo-100">
                            Créez votre compte en quelques minutes et accédez à des milliers d'offres de stage dans toute la France.
                        </p>
                        <div class="mt-8 flex">
                            <div class="inline-flex rounded-md shadow">
                                <a href="../auth/register/register_form.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-50">
                                    S'inscrire gratuitement
                                </a>
                            </div>
                            <div class="ml-3 inline-flex rounded-md shadow">
                                <a href="../auth/register/register_form.php" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 bg-opacity-60 hover:bg-opacity-70">
                                    Je suis une entreprise
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="-mt-6 aspect-w-5 aspect-h-3 md:aspect-w-2 md:aspect-h-1">
                    <img class="transform translate-x-6 translate-y-6 rounded-md object-cover object-left-top" src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1350&q=80" alt="Students working">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
   
    <?php include '../includes/footer.php'; ?>


  
</body>
</html>