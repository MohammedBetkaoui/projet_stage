<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        
        .error-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .error-icon {
            font-size: 60px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #dc3545;
            margin-bottom: 15px;
        }
        
        p {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .btn {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #0056b3;
        }
        
        .error-code {
            font-size: 14px;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php
    // Récupérer le code d'erreur
    $errorCode = isset($_GET['code']) ? intval($_GET['code']) : 500;
    
    // Définir les messages d'erreur
    $errorMessages = [
        400 => 'Requête incorrecte',
        401 => 'Non autorisé',
        403 => 'Accès interdit',
        404 => 'Page non trouvée',
        500 => 'Erreur interne du serveur',
        503 => 'Service indisponible'
    ];
    
    // Récupérer le message d'erreur
    $errorMessage = $errorMessages[$errorCode] ?? 'Une erreur est survenue';
    ?>
    
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1>Oups! Une erreur est survenue</h1>
        <p><?php echo $errorMessage; ?></p>
        <p>Nous sommes désolés pour ce désagrément. Veuillez réessayer plus tard ou contacter l'administrateur si le problème persiste.</p>
        <a href="/" class="btn">Retour à l'accueil</a>
        <div class="error-code">Code d'erreur: <?php echo $errorCode; ?></div>
    </div>
</body>
</html>
