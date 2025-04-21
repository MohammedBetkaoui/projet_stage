<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de confidentialité | StageFinder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .privacy-badge {
            @apply inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium mb-4;
        }
        table {
            @apply min-w-full bg-white rounded-lg overflow-hidden;
        }
        th {
            @apply bg-indigo-50 text-left px-6 py-3 text-indigo-600 uppercase tracking-wider;
        }
        td {
            @apply px-6 py-4 border-b border-gray-200 text-gray-700;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <span class="privacy-badge animate__animated animate__bounceIn">StageFinder</span>
            <h1 class="text-3xl md:text-4xl font-bold text-indigo-600 animate__animated animate__fadeInDown">
                Politique de confidentialité
            </h1>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-xl shadow-md overflow-hidden animate__animated animate__fadeIn">
            <div class="p-6 md:p-8">
                <!-- Introduction -->
                <section class="mb-10">
                    <p class="text-gray-700 mb-4">
                        Nous nous engageons à protéger vos données personnelles. Cette politique explique comment nous collectons, utilisons et sécurisons vos informations.
                    </p>
                </section>

                <!-- Données collectées -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Données que nous collectons
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="animate__animated animate__fadeIn">
                            <thead>
                                <tr>
                                    <th>Type de données</th>
                                    <th>Finalité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nom, email</td>
                                    <td>Création de compte</td>
                                </tr>
                                <tr>
                                    <td>Certificat de scolarité</td>
                                    <td>Vérification du statut étudiant</td>
                                </tr>
                                [...]
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- Cookies -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Cookies</h2>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="font-medium text-blue-800">Nous utilisons des cookies essentiels :</p>
                                <p class="text-blue-700">Authentification, préférences utilisateur, sécurité.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Vos droits -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Vos droits RGPD</h2>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <h3 class="font-semibold text-green-800">Accès et rectification</h3>
                            <p class="text-green-700 mt-1">Vous pouvez demander une copie de vos données.</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                            <h3 class="font-semibold text-purple-800">Suppression</h3>
                            <p class="text-purple-700 mt-1">Demandez l'effacement de vos données.</p>
                        </div>
                    </div>
                </section>

                <!-- Contact -->
                <section class="bg-gray-50 p-6 rounded-lg">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">Contact DPO</h2>
                    <p class="text-gray-700 mb-4">
                        Pour toute question : <a href="mailto:mohammed.betkaoui@univ-bba.dz" class="text-indigo-600 hover:underline">mohammed.betkaoui@univ-bba.dz</a>
                    </p>
                    <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                        Exporter mes données
                    </button>
                </section>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-gray-600">
            <p>© 2025 StageFinder </p>
        </div>
    </footer>
</body>
</html>