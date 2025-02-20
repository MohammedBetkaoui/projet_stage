<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/auth.php';

authenticate();
$currentUserId = $_SESSION['user_id'];

try {
    $action = $_GET['action'] ?? '';

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            handleGetRequest($currentUserId, $action);
            break;
            
        case 'POST':
            handlePostRequest($currentUserId);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Méthode non autorisée']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function handleGetRequest($currentUserId, $action) {
    global $pdo;
    
    if ($action === 'conversation') {
        $otherUserId = filter_input(INPUT_GET, 'with', FILTER_VALIDATE_INT);
        
        if (!$otherUserId) {
            http_response_code(400);
            exit(json_encode(['success' => false, 'error' => 'ID utilisateur invalide']));
        }

        $stmt = $pdo->prepare("SELECT 
                m.id, 
                m.sender_id, 
                m.receiver_id, 
                m.message, 
                m.sent_at,
                u1.full_name as sender_name,
                u2.full_name as receiver_name
            FROM messages m
            JOIN users u1 ON m.sender_id = u1.id
            JOIN users u2 ON m.receiver_id = u2.id
            WHERE (m.sender_id = ? AND m.receiver_id = ?)
            OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.sent_at DESC
            LIMIT 100");
        $stmt->execute([$currentUserId, $otherUserId, $otherUserId, $currentUserId]);
        
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'messages' => array_map('escapeMessage', $messages)
        ]);
        
    } elseif ($action === 'contacts') {
        $stmt = $pdo->prepare("SELECT 
                DISTINCT u.id,
                u.full_name,
                u.role,
                MAX(m.sent_at) as last_message_date
            FROM users u
            JOIN messages m ON u.id = CASE 
                WHEN m.sender_id = ? THEN m.receiver_id 
                ELSE m.sender_id 
            END
            WHERE ? IN (m.sender_id, m.receiver_id)
            GROUP BY u.id
            ORDER BY last_message_date DESC
            LIMIT 20");
        $stmt->execute([$currentUserId, $currentUserId]);
        
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'contacts' => array_map('escapeContact', $contacts)
        ]);
        
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Action non reconnue']);
    }
}

function handlePostRequest($currentUserId) {
    global $pdo;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validation CSRF
    if (!isset($data['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $data['csrf_token'])) {
        http_response_code(403);
        exit(json_encode(['success' => false, 'error' => 'Token CSRF invalide']));
    }
    
    // Validation des données
    $required = ['receiver_id', 'message'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            http_response_code(400);
            exit(json_encode(['success' => false, 'error' => "Le champ $field est requis"]));
        }
    }
    
    $receiverId = filter_var($data['receiver_id'], FILTER_VALIDATE_INT);
    $message = trim($data['message']);
    
    if (!$receiverId || $receiverId === $currentUserId) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Destinataire invalide']));
    }
    
    if (strlen($message) < 2 || strlen($message) > 1000) {
        http_response_code(400);
        exit(json_encode(['success' => false, 'error' => 'Le message doit contenir entre 2 et 1000 caractères']));
    }
    
    // Vérification de l'existence du destinataire
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$receiverId]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        exit(json_encode(['success' => false, 'error' => 'Destinataire introuvable']));
    }
    
    // Insertion du message
    $stmt = $pdo->prepare("INSERT INTO messages 
        (sender_id, receiver_id, message)
        VALUES (?, ?, ?)");
    $stmt->execute([
        $currentUserId,
        $receiverId,
        htmlspecialchars($message, ENT_QUOTES, 'UTF-8')
    ]);
    
    // Création de la notification
    $messageId = $pdo->lastInsertId();
    createMessageNotification($receiverId, $currentUserId, $messageId);
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message_id' => $messageId
    ]);
}

function createMessageNotification($receiverId, $senderId, $messageId) {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO notifications 
        (user_id, message, link)
        VALUES (?, ?, ?)");
    $stmt->execute([
        $receiverId,
        'Nouveau message reçu',
        "/messages.php?with=$senderId"
    ]);
}

function escapeMessage($message) {
    return [
        'id' => (int)$message['id'],
        'sender_id' => (int)$message['sender_id'],
        'receiver_id' => (int)$message['receiver_id'],
        'message' => htmlspecialchars_decode($message['message']),
        'sent_at' => $message['sent_at'],
        'sender_name' => htmlspecialchars($message['sender_name']),
        'receiver_name' => htmlspecialchars($message['receiver_name']),
        'is_own' => ((int)$message['sender_id'] === $_SESSION['user_id'])
    ];
}

function escapeContact($contact) {
    return [
        'id' => (int)$contact['id'],
        'full_name' => htmlspecialchars($contact['full_name']),
        'role' => htmlspecialchars($contact['role']),
        'last_message_date' => $contact['last_message_date']
    ];
}