<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions d'utilisation | StageFinder</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .highlight-box {
            @apply bg-indigo-50 border-l-4 border-indigo-500 p-4 mb-6 rounded-r-lg;
        }
        li::marker {
            @apply text-indigo-500;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl md:text-4xl font-bold text-indigo-600 animate__animated animate__fadeInDown">
                Conditions d'utilisation
            </h1>
        </div>
    </header>

    <!-- Contenu principal -->
    <main class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="bg-white rounded-xl shadow-md overflow-hidden animate__animated animate__fadeIn">
            <div class="p-6 md:p-8">
                <!-- Section 1 -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        Acceptation des conditions
                    </h2>
                    <p class="text-gray-700 mb-4">
                        En utilisant notre plateforme, vous acceptez ces conditions dans leur intégralité. Si vous n'êtes pas d'accord, veuillez ne pas utiliser nos services.
                    </p>
                    <div class="highlight-box animate__animated animate__fadeInLeft">
                        <p class="font-medium text-indigo-700">Important :</p>
                        <p>Les utilisateurs doivent avoir au moins 16 ans pour créer un compte.</p>
                    </div>
                </section>

                <!-- Section 2 -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Comptes utilisateurs</h2>
                    <ul class="space-y-3 list-disc pl-5 text-gray-700">
                        <li>Vous êtes responsable de la confidentialité de votre mot de passe.</li>
                        <li>Les comptes sont personnels et non transférables.</li>
                        <li>Nous réservons le droit de suspendre les comptes inactifs pendant plus de 12 mois.</li>
                    </ul>
                </section>

                <!-- Section 3 (Responsive grid) -->
                <section class="mb-10">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Contenu et responsabilités</h2>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-lg text-gray-800 mb-2">Interdictions</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-2 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                    Contenu illégal ou diffamatoire
                                </li>
                                <li class="flex items-start">[...]</li>
                            </ul>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="font-semibold text-lg text-gray-800 mb-2">Licence</h3>
                            <p class="text-gray-700">
                                Vous nous accordez une licence mondiale pour utiliser le contenu que vous publiez.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- CTA -->
                <div class="text-center mt-8">
    <a href="../../auth/register/register_form.php?step=4" class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors duration-300">
        Revenir à l'inscription
    </a>
</div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 mt-12 py-6">
        <div class="container mx-auto px-4 text-center text-gray-600">
            <p>© 2025 StageFinder. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>