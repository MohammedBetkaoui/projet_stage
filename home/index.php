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
        SELECT o.id, o.title, o.description, o.sector, o.location, o.start_date, o.end_date, o.deadline, o.compensation, c.full_name AS company_name
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Header -->
    <?php include '../includes/header/header.php'; ?>

    <!-- Section des offres -->
    <section class="offers-section">
        <h2>Dernières offres de stages</h2>
        <br>
        <?php 
            if (!isset($_SESSION['user_id'])){ ?>
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
                        <?php if($role == 'student'){?>
                        <a href="/studant/apply/apply.php?id=<?php echo $offer['id']; ?>" class="btn">Postuler</a>
                        <?php } ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-offers">Aucune offre disponible pour le moment.</p>
            <?php endif; ?>
        </div>
        <br><br>
        <a href="./offres.php" class="btn btn-see-more">Voir toutes les offres</a>
    </section>

    <!-- Section "Pourquoi nous choisir ?" -->
    <section class="why-choose-us">
        <h2>Pourquoi nous choisir ?</h2>
        <div class="reasons-container">
            <div class="reason-card">
                <i class='bx bx-check-shield'></i>
                <h3>Offres vérifiées</h3>
                <p>Nous garantissons la qualité et la fiabilité de chaque offre de stage.</p>
            </div>
            <div class="reason-card">
                <i class='bx bx-network-chart'></i>
                <h3>Large réseau</h3>
                <p>Accédez à des offres exclusives de grandes entreprises et startups.</p>
            </div>
            <div class="reason-card">
                <i class='bx bx-support'></i>
                <h3>Support personnalisé</h3>
                <p>Notre équipe est là pour vous accompagner à chaque étape.</p>
            </div>
        </div>
    </section>

    <!-- Section "Témoignages" -->
    <section class="testimonials-section">
<<<<<<< HEAD
        <h2>Témoignages</h2>
=======
        <h2>Témoignage</h2>
>>>>>>>         <div class="testimonials-container">
            <div class="testimonial-card">
                <p>"Grâce à StageFinder, j'ai trouvé un stage dans une entreprise innovante. Une expérience incroyable !"</p>
                <span>- Amine, étudiant en informatique</span>
            </div>
            <div class="testimonial-card">
                <p>"La plateforme est très intuitive et propose des offres de qualité. Je recommande !"</p>
                <span>- Sarah, étudiante en marketing</span>
            </div>
            <div class="testimonial-card">
                <p>"Nous avons trouvé des profils très motivés grâce à StageFinder. Un vrai gain de temps."</p>
                <span>- Entreprise Tech</span>
            </div>
        </div>
    </section>

    <!-- Section "Contact rapide" -->
   
    <section class="contact-section">
        <h2>Contactez-nous</h2>
        <p>Vous avez des questions ? Notre équipe est là pour vous aider.</p>
        <form action="/contact.php" method="POST" class="contact-form">
            <input type="text" name="name" placeholder="Votre nom" required>
            <input type="email" name="email" placeholder="Votre email" required>
            <textarea name="message" placeholder="Votre message" required></textarea>
            <button type="submit" class="btn">Envoyer</button>
        </form>
    </section>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>
</body>
</html>