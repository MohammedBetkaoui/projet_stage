<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="./register.css">
    <!-- Librairies pour la vérification -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <script>
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js"></script>
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        .error-field {
            @apply border-red-500 ring-1 ring-red-500;
        }
        .processing-message {
            @apply flex items-center justify-center gap-2 py-4;
        }
        .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center py-8">
    <div class="container mx-auto px-4 max-w-3xl">
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded">
                <?php foreach ($errors as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
            
            <form id="registerForm" action="register.php" method="POST" novalidate enctype="multipart/form-data" class="p-6 md:p-8">
                <!-- Étape 1: Informations de base -->
                <div class="step" id="step1">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Créer un compte</h2>
                    <p class="text-gray-600 mb-6">Étape 1/4 - Informations de base</p>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Nom d'utilisateur*</label>
                            <input type="text" id="username" name="username" required 
                                   value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                        
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe*</label>
                            <input type="password" id="password" name="password" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email*</label>
                            <input type="email" id="email" name="email" required 
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-end">
                        <button type="button" class="next-btn px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                            Suivant
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Étape 2: Rôle et informations supplémentaires -->
                <div class="step hidden" id="step2">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Votre profil</h2>
                    <p class="text-gray-600 mb-6">Étape 2/4 - Rôle et informations</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rôle*</label>
                            <select id="role" name="role" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                                <option value="" disabled selected>Choisissez votre rôle</option>
                                <option value="student" <?= ($_POST['role'] ?? '') === 'student' ? 'selected' : '' ?>>Étudiant</option>
                                <option value="company" <?= ($_POST['role'] ?? '') === 'company' ? 'selected' : '' ?>>Entreprise</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Nom complet*</label>
                            <input type="text" id="full_name" name="full_name" required 
                                   value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="text" id="phone" name="phone" 
                                   value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                            <input type="text" id="address" name="address" 
                                   value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-between">
                        <button type="button" class="prev-btn px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Précédent
                        </button>
                        
                        <div>
                            <button type="button" id="next-btn-step2" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                                Suivant
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                            
                            <button type="submit" id="submit-btn-step2" class="hidden px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                                S'inscrire
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                   
                <!-- Étape 3: Vérification du certificat de scolarité -->
                <div class="step hidden" id="step3">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Vérification étudiante</h2>
                    <p class="text-gray-600 mb-6">Étape 3/4 - Certificat de scolarité</p>
                    
                    <div id="certificate-verification-container">
                        <p class="text-gray-700 mb-4">Veuillez télécharger votre certificat de scolarité pour vérification :</p>
                        
                        <!-- Zone d'upload -->
                        <div class="flex flex-col items-center justify-center border-2 border-dashed border-indigo-200 rounded-xl p-8 transition-all duration-300 hover:border-indigo-400 hover:bg-indigo-50 h-64 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            
                            <div class="flex flex-col sm:flex-row gap-4 mb-4">
                                <label for="certificate-file" class="cursor-pointer">
                                    <span class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Choisir un fichier
                                    </span>
                                    <input type="file" id="certificate-file" accept="application/pdf,image/*" class="hidden" />
                                </label>
                                
                                <button id="camera-btn" type="button" class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Prendre une photo
                                </button>
                            </div>
                            
                            <p class="text-sm text-gray-500 text-center">Formats supportés: PDF, JPG, PNG, JPEG (max 5MB)</p>
                        </div>
                        
                        <button type="button" id="verify-certificate" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Vérifier le certificat
                        </button>
                        
                        <!-- Interface caméra -->
                        <div id="camera-container" class="hidden bg-white rounded-xl shadow-lg p-6 mt-6 animate__animated animate__fadeIn">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Prendre une photo</h3>
                                <button id="close-camera" class="text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="relative w-full bg-black rounded-lg overflow-hidden">
                                <video id="camera-preview" class="w-full h-auto max-h-96 object-contain" autoplay playsinline></video>
                                <div class="absolute inset-0 border-2 border-dashed border-yellow-400 pointer-events-none opacity-50"></div>
                            </div>
                            
                            <div class="flex justify-center mt-4">
                                <button id="capture-photo" type="button" class="px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Capturer la photo
                                </button>
                            </div>
                            
                            <canvas id="captured-canvas" class="hidden"></canvas>
                        </div>
                        
                        <!-- Résultat de vérification -->
                        <div id="verification-result" class="hidden mt-6 p-4 rounded-lg border border-gray-200 bg-gray-50">
                            <div id="verification-message" class="text-center"></div>
                            <input type="hidden" id="certificate_verified" name="certificate_verified" value="0">
                            <input type="hidden" id="extracted-text" name="extracted_text">
                        </div>
                    </div>
                    
                    <div class="mt-8 flex justify-between">
                        <button type="button" class="prev-btn px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Précédent
                        </button>
                        
                        <button type="button" id="next-after-verification" class="hidden px-6 py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                            Suivant
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Étape 4: Sélection du Branch -->
                <div class="step hidden" id="step4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Choix de la filière</h2>
                    <p class="text-gray-600 mb-6">Étape 4/4 - Sélectionnez votre branche</p>
                    
                    <div class="space-y-5">
                        <div>
                            <label for="branch" class="block text-sm font-medium text-gray-700 mb-1">Branche*</label>
                            <select id="branch" name="branch" class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                                <option value="" disabled selected>Choisissez votre branche</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= htmlspecialchars($branch['id']) ?>"
                                        <?= (isset($_POST['branch']) && $_POST['branch'] == $branch['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($branch['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                       
                    </div>
                    
                    <div class="mt-8 flex justify-between">
                        <button type="button" class="prev-btn px-6 py-3 bg-gray-500 text-white rounded-lg font-medium hover:bg-gray-600 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Précédent
                        </button>
                        
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors duration-300 shadow hover:shadow-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Finaliser l'inscription
                        </button>
                    </div>
                </div>
            </form>
            
            <div class="px-6 pb-6 md:px-8 md:pb-8 text-center">
                <p class="text-gray-600">Déjà inscrit? <a href="../login/login.php" class="text-indigo-600 hover:text-indigo-800 font-medium hover:underline">Connectez-vous ici</a></p>
            </div>
        </div>
    </div>

    <script src="./register.js"></script>
    <script src="./verification_register.js"></script>
</body>
</html>