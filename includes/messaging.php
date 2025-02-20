<?php
function sendMessage($senderId, $receiverId, $message) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO messages 
            (sender_id, receiver_id, message)
            VALUES (?, ?, ?)");
        
        $stmt->execute([
            $senderId,
            $receiverId,
            htmlspecialchars(trim($message))
        ]);
        
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Erreur d'envoi du message : " . $e->getMessage());
    }
}

function getConversation($userId1, $userId2, $limit = 100) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT 
                m.*,
                u1.full_name as sender_name,
                u2.full_name as receiver_name
            FROM messages m
            JOIN users u1 ON m.sender_id = u1.id
            JOIN users u2 ON m.receiver_id = u2.id
            WHERE (m.sender_id = ? AND m.receiver_id = ?)
            OR (m.sender_id = ? AND m.receiver_id = ?)
            ORDER BY m.sent_at DESC
            LIMIT ?");
        
        $stmt->execute([$userId1, $userId2, $userId2, $userId1, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        throw new Exception("Erreur de récupération des messages : " . $e->getMessage());
    }
}

function getRecentContacts($userId) {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT 
                u.id,
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
            LIMIT 15");
        
        $stmt->execute([$userId, $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        throw new Exception("Erreur de récupération des contacts : " . $e->getMessage());
    }
}