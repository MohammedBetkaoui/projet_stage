<?php include '../home/include/offre_get_fun.php' ?> 

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



<body>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header/header.php'; ?>

   <?php include '../home/include/domain.php' ?>
    <!-- Section des offres -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-dark">Toutes les offres de stages</h2>
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


<?php include '../home/include/choisier.php' ?>

<?php include '../home/include/temoignages.php' ?>

<?php include '../home/include/section.php' ?>
             
    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>
