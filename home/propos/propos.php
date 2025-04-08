<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php'; // Inclure la connexion à la base de données
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/navbar/navbar.php'; // Inclure la navbar
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - Plateforme de stages</title>
    <link rel="stylesheet" href="/assets/css/propos.css"> <!-- Lien vers le fichier CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Header -->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header/header.php'; ?>
    <!-- Section À propos -->
    <section class="about-section">
        <div class="about-container">
            <h1>À propos de nous</h1>
            <p class="subtitle">Notre mission est de connecter les étudiants algériens et les entreprises locales pour des opportunités de stage enrichissantes.</p>

            <div class="about-content">
                <div class="about-text">
                    <h2>Qui sommes-nous ?</h2>
                    <p>
                        Nous sommes une plateforme algérienne dédiée à la mise en relation des étudiants et des entreprises locales. Notre objectif est de simplifier la recherche de stages et de permettre aux entreprises de trouver les talents dont elles ont besoin.
                    </p>
                    <p>
                        Lancée en 2025, notre plateforme est conçue exclusivement pour les étudiants et les entreprises en Algérie. Nous croyons en la puissance des stages pour façonner les carrières de demain et contribuer au développement économique du pays.
                    </p>
                </div>
                <div class="about-image">
                    <img src="/images/stylish-black-couple-have-business-conversation.jpg" alt="À propos de nous">
                </div>
            </div>

            <div class="about-values">
                <h2>Nos valeurs</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <i class='bx bx-group'></i>
                        <h3>Collaboration</h3>
                        <p>Nous encourageons la collaboration entre les étudiants algériens et les entreprises locales pour un avenir meilleur.</p>
                    </div>
                    <div class="value-card">
                        <i class='bx bx-trending-up'></i>
                        <h3>Innovation</h3>
                        <p>Nous utilisons les dernières technologies pour offrir une expérience utilisateur optimale et moderne.</p>
                    </div>
                    <div class="value-card">
                        <i class='bx bx-heart'></i>
                        <h3>Engagement</h3>
                        <p>Nous nous engageons à soutenir les étudiants et les entreprises algériennes dans leur développement professionnel.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

    <!-- Scripts -->
</body>
</html>