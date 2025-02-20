<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

authenticate();
header('Content-Type: application/json');

try {
    // Notifications non lues
    $unreadStmt = $pdo->prepare('SELECT COUNT(*) FROM notifications 
        WHERE user_id = ? AND is_read = 0');
    $unreadStmt->execute([$_SESSION['user_id']]);
    $unread = $unreadStmt->fetchColumn();

    // Dernières notifications
    $notifStmt = $pdo->prepare('SELECT * FROM notifications 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 5');
    $notifStmt->execute([$_SESSION['user_id']]);
    
    echo json_encode([
        'success' => true,
        'unread' => $unread,
        'notifications' => $notifStmt->fetchAll()
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur serveur']);
}
?>