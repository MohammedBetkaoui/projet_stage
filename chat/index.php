<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>stage chat </title>
  <link rel="stylesheet" type="text/css" href="chat.css">
  <link rel="stylesheet" type="text/css" href="rest.css">
  <script src="https://cdn.socket.io/4.5.4/socket.io.min.js"></script>
  <script src="chat.js" defer></script>
</head>
<body>
  <!-- Chat Drawer -->
  <div class="chat-drawer" id="chat-drawer">
    <div class="chat-header">
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

  <!-- Chat Toggle Button -->
  <button class="chat-toggle-btn" id="chat-toggle-btn">Chat</button>
  
  <!-- Notification Banner -->
  <div id="notification" class="notification">New message received!</div>
</body>
</html>