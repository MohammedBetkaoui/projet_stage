<?php
// Include the database connection
require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/db/db.php';

// Fetch users from the database
$sql = "SELECT * FROM users"; // Adjust the query as needed
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/chat/chat.css">
    <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>


</head>

<body>


    <!-- Drawer (Sidebar) -->

    <!-- Drawer (Sidebar) -->
    <div class="drawer">
        <h2>Users</h2>
       
        <ul>
            <?php foreach ($users as $user): ?>
                <li><a href="#" id="chat-toggle" class="user-link" data-user-id="<?php echo htmlspecialchars($user['id']); ?>"><?php echo htmlspecialchars($user['full_name']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="chat-drawer" id="chat-drawer">
                <div class="chat-header">


                    <?php echo $auth; ?>
                    <input value="<?php echo $auth; ?>" id="user_id" hidden>

                    <span class="name">stage chat </span>

                    <button class="close-btn" id="close-btn">×</button>
                </div>
                <div class="chat" id="messages">
                    <!-- Chat messages will be appended here -->
                </div>
                <div class="write">
                    <input type="text" id="message-input" class="input" placeholder="Type your message..." />
                    <button id="send-button">Send</button>

                </div>
            </div>
            <div id="notification" class="notification">New message received!</div>


                


</body>
<script src="/chat/chat.js"></script>
</html>