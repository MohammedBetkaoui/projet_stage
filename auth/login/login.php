<?php
// Désactiver l'affichage des erreurs pour éviter de casser le JSON
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Démarrer la capture de sortie pour éviter que des erreurs PHP ne soient envoyées avant les en-têtes
ob_start();

// Désactiver temporairement les nouvelles fonctionnalités de sécurité pour le débogage
// Démarrer la session de manière traditionnelle
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le fichier de journalisation des erreurs
require_once '../../includes/error_logger.php';

// Inclure le fichier de connexion à la base de données
require_once '../../includes/db/db.php';

// Journaliser le début de la requête
logError("Début de la requête de connexion - " . $_SERVER['REMOTE_ADDR'], 'INFO');

// Fonction pour renvoyer une réponse JSON et terminer le script
function sendJsonResponse($status, $message, $data = []) {
    // Nettoyer toute sortie précédente
    ob_clean();

    // Journaliser la réponse
    $logLevel = ($status === 'success') ? 'INFO' : 'ERROR';
    logError("Réponse: [$status] $message", $logLevel);

    // Définir l'en-tête Content-Type
    header('Content-Type: application/json');

    // Préparer la réponse
    $response = [
        'status' => $status,
        'message' => $message
    ];

    // Ajouter des données supplémentaires si fournies
    if (!empty($data)) {
        $response = array_merge($response, $data);
    }

    // Envoyer la réponse JSON
    $jsonResponse = json_encode($response);

    // Vérifier si l'encodage JSON a réussi
    if ($jsonResponse === false) {
        // Journaliser l'erreur d'encodage JSON
        logError("Erreur d'encodage JSON: " . json_last_error_msg(), 'ERROR');

        // Envoyer une réponse d'erreur simple
        echo '{"status":"error","message":"Erreur interne du serveur"}';
    } else {
        echo $jsonResponse;
    }

    exit;
}

// Redirection si l'utilisateur est déjà connecté
if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header('Location: ../../admin/admin_dashboard.php');
            break;
        case 'company':
            header('Location: ../../company/company_dashboard.php');
            break;
        case 'student':
            header('Location: ../../studant/home_dashboard/student_dashboard.php');
            break;
        default:
            // Déconnexion si le rôle est inconnu
            header('Location: ../../auth/logout/logout.php');
    }
    exit();
}

// La connexion à la base de données est déjà incluse dans le script d'initialisation

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $errors = [];

    // Validation côté serveur
    if (empty($login)) {
        $errors[] = 'Le champ email ou téléphone est requis.';
    }

    if (empty($password)) {
        $errors[] = 'Le champ mot de passe est requis.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
    }

    // Si des erreurs sont présentes, renvoyer un message d'erreur
    if (!empty($errors)) {
        sendJsonResponse('error', implode(' ', $errors));
    }

    try {
        // Vérification des identifiants
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
        if (!$stmt) {
            sendJsonResponse('error', 'Erreur de préparation de la requête: ' . $conn->error);
        }

        $stmt->bind_param("ss", $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Vérification du mot de passe et création de la session
        if ($user && password_verify($password, $user['password'])) {
            try {
                // Vérifier si la colonne last_login existe
                $checkColumnQuery = "SHOW COLUMNS FROM `users` LIKE 'last_login'";
                $columnExists = $conn->query($checkColumnQuery)->num_rows > 0;

                if ($columnExists) {
                    // Mise à jour de la dernière connexion
                    $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                    if ($updateStmt) {
                        $updateStmt->bind_param("i", $user['id']);
                        $updateStmt->execute();
                        $updateStmt->close();
                    }
                } else {
                    // Tenter d'ajouter la colonne last_login
                    try {
                        $alterTableQuery = "ALTER TABLE `users` ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL";
                        $conn->query($alterTableQuery);
                        logError("Colonne 'last_login' ajoutée à la table 'users'", 'INFO');

                        // Maintenant, mettre à jour la dernière connexion
                        $updateStmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                        if ($updateStmt) {
                            $updateStmt->bind_param("i", $user['id']);
                            $updateStmt->execute();
                            $updateStmt->close();
                        }
                    } catch (Exception $e) {
                        // Ignorer l'erreur, car ce n'est pas critique pour la connexion
                        logError("Impossible d'ajouter/mettre à jour la colonne 'last_login': " . $e->getMessage(), 'WARNING');
                    }
                }

                // Création de la session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Journalisation de la connexion (optionnel)
                try {
                    // Vérifier si la table login_logs existe
                    $tableCheckQuery = "SHOW TABLES LIKE 'login_logs'";
                    $tableExists = $conn->query($tableCheckQuery)->num_rows > 0;

                    // Si la table n'existe pas, la créer
                    if (!$tableExists) {
                        $createTableSQL = "
                        CREATE TABLE IF NOT EXISTS `login_logs` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `user_id` int(11) NOT NULL,
                          `ip_address` varchar(45) NOT NULL,
                          `user_agent` text NOT NULL,
                          `login_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          PRIMARY KEY (`id`),
                          KEY `user_id` (`user_id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

                        $conn->query($createTableSQL);
                        $tableExists = true;
                    }

                    if ($tableExists) {
                        $ip = $_SERVER['REMOTE_ADDR'];
                        $userAgent = $_SERVER['HTTP_USER_AGENT'];
                        $logStmt = $conn->prepare("INSERT INTO login_logs (user_id, ip_address, user_agent) VALUES (?, ?, ?)");
                        if ($logStmt) {
                            $logStmt->bind_param("iss", $user['id'], $ip, $userAgent);
                            $logStmt->execute();
                            $logStmt->close();
                        }
                    }
                } catch (Exception $e) {
                    // Ignorer les erreurs de journalisation, car ce n'est pas critique
                    // Mais les enregistrer dans un fichier de log
                    error_log('Erreur de journalisation: ' . $e->getMessage());
                }

                // Envoyer la réponse de succès
                sendJsonResponse('success', 'Connexion réussie.', ['role' => $user['role']]);
            } catch (Exception $e) {
                sendJsonResponse('error', 'Erreur lors de la connexion: ' . $e->getMessage());
            }
        } else {
            // Attendre un peu pour prévenir les attaques par force brute
            sleep(1);
            sendJsonResponse('error', 'Identifiants incorrects. Veuillez vérifier votre email/téléphone et mot de passe.');
        }

        $stmt->close();
    } catch (Exception $e) {
        sendJsonResponse('error', 'Erreur lors de la vérification des identifiants: ' . $e->getMessage());
    }
}

include './login_form.php';
?>