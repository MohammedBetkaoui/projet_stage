<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de Stages pour Étudiants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/includes/header/header.css">
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
<body class="font-sans">
    <div class="gradient-bg relative">
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
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-50 md:py-4 md:text-lg md:px-10 transition duration-300">
                                Rechercher des stages
                            </a>
                        </div>
                        <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-500 bg-opacity-60 hover:bg-opacity-70 md:py-4 md:text-lg md:px-10 transition duration-300">
                                Je suis une entreprise
                            </a>
                        </div>
                    </div>
                </div>
                <div class="mt-10 lg:mt-0 hidden lg:block">
                    <img src="../../assets/images/Offre_de_stage_dans_un_bureau.png" alt="Illustration" class="mx-auto" onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </div>
</body>
<script src="/includes/header/header.js"></script>
</html>

