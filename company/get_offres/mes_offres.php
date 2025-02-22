<?php
session_start();
require_once '../../includes/db/db.php'; // Inclure la connexion à la base de données
require_once '../functions/offres/get_offres.php'; // Inclure la fonction pour récupérer les offres
// Rediriger si l'utilisateur n'est pas connecté ou n'est pas une entreprise
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: ../auth/login/login.php');
    exit();
}

$error = '';
$offers = [];

try {
    // Récupérer les offres de l'entreprise connectée
    $offers = getOffers($conn, $_SESSION['user_id']); // Récupérer les offres de l'entreprise
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes offres</title>
    <link rel="stylesheet" href="./mes_offre.css"> <!-- Lien vers le fichier CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 pour les alertes modernes -->
</head>


<body>
    <!-- Sidebar -->
    <?php include '../../includes/sidebar/sidebar.php'; ?>

    <!-- Contenu principal -->
    <section class="main-content">
        <h1>Mes offres publiées</h1>
        <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert success">
        <i class='bx bx-check-circle'></i>
        <span><?php echo $_SESSION['success_message']; ?></span>
        <button class="close-btn" onclick="this.parentElement.remove()">&times;</button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
             
        <?php if ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php elseif (empty($offers)): ?>
            <div class="alert info">Vous n'avez publié aucune offre pour le moment.</div>
        <?php else: ?>
            <!-- Statistiques rapides -->
            
            <div class="quick-stats">
                <div class="stat-card">
                    <i class='bx bx-file'></i>
                    <h3>Offres publiées</h3>
                    <p><?php echo count($offers); ?></p>
                </div>
                <div class="stat-card">
                    <i class='bx bx-user-check'></i>
                    <h3>Candidatures reçues</h3>
                    <p>12</p> 
                </div>
                <div class="stat-card">
                    <i class='bx bx-calendar'></i>
                    <h3>Stages en cours</h3>
                    <p>3</p> 
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="quick-actions">
                <h2>Actions rapides</h2>
                <div class="action-buttons">
                    <a href="../../company/offre/ajouter_offre.php" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Publier une offre
                    </a>
                    <a href="/notifications.php" class="btn btn-secondary">
                        <i class='bx bx-bell'></i> Voir les notifications
                    </a>
                </div>
            </div>

            <!-- Liste des offres -->
            <div class="offers-container">
                <?php foreach ($offers as $offer): ?>
                    <div class="offer-card">
                        <h2><?php echo htmlspecialchars($offer['title']); ?></h2>
                        <p class="description"><?php echo htmlspecialchars($offer['description']); ?></p>
                        <div class="details">
                            <span><strong>Secteur:</strong> <?php echo htmlspecialchars($offer['sector']); ?></span>
                            <span><strong>Lieu:</strong> <?php echo htmlspecialchars($offer['location']); ?></span>
                            <span><strong>Date de début:</strong> <?php echo htmlspecialchars($offer['start_date']); ?></span>
                            <span><strong>Date de fin:</strong> <?php echo htmlspecialchars($offer['end_date']); ?></span>
                            <span><strong>Gratification:</strong> <?php echo $offer['compensation'] ? $offer['compensation'] . ' Dz/mois' : 'Non spécifiée'; ?></span>
                            <span><strong>Dernier Délai:</strong> <?php echo $offer['deadline']  ?></span>
                        </div>
                        <div class="actions">
                            <a href="../offre/edit_offre.php?id=<?php echo $offer['id']; ?>" class="btn edit">Modifier</a>
                            <a href="#" class="btn delete" onclick="confirmDelete(<?php echo $offer['id']; ?>)">Supprimer</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Scripts -->
    <script>
        // Fonction pour confirmer la suppression avec SweetAlert2
        function confirmDelete(offerId) {
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: "Vous ne pourrez pas revenir en arrière !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Oui, supprimer !',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Rediriger vers sup_offre.php pour supprimer l'offre
                    window.location.href = `../functions/offres/sup_offre.php?id=${offerId}`;
                }
            });
        }

        // Afficher un message de succès ou d'erreur après la suppression
        <?php if (isset($_SESSION['success_message'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: '<?php echo $_SESSION['success_message']; ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: '<?php echo $_SESSION['error_message']; ?>',
                confirmButtonText: 'OK'
            });
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
    </script>
</body>
</html>