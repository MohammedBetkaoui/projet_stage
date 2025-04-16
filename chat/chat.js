// Connect to the Socket.IO server
const socket = io('http://localhost:5000'); // Replace with your backend URL

// DOM elements
const messageInput = document.getElementById('message-input');
const sendButton = document.getElementById('send-button');
const messagesContainer = document.getElementById('messages');
const notification = document.getElementById('notification');
const notificationCount = document.getElementById('notification-count');
const chatDrawer = document.getElementById('chat-drawer');
const chatToggle = document.getElementById('chat-toggle');
const closeBtn = document.getElementById('close-btn');
const userid = document.getElementById('user_id');

const userId = userid.value;
const currentChatUser = 38;
let unreadMessages = 0;

// Join user room
socket.emit('join_user_room', userId);

// Toggle chat drawer
chatToggle.addEventListener('click', function (event) {
    event.preventDefault();
    chatDrawer.classList.toggle('open');
    hideNotification();
});

// Close chat drawer
closeBtn.addEventListener('click', function () {
    chatDrawer.classList.remove('open');
});

// Send message when the "Send" button is clicked
sendButton.addEventListener('click', sendMessage);

// Send message on "Enter" key
messageInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage();
});

// Typing indicator
messageInput.addEventListener('input', () => {
    socket.emit('typing', { senderId: userId, receiverId: currentChatUser });
});

function sendMessage() {
    const message = messageInput.value.trim();
    if (message) {
        socket.emit('send_message', { senderId: userId, receiverId: currentChatUser, message });
        addMessageToChat(userId, message, true);
        messageInput.value = '';
    }
}

// Listen for incoming messages
socket.on('receive_message', (data) => {
    const { senderId, message } = data;
    addMessageToChat(senderId, message, false);
    showNotification();
});

// Show typing indicator
socket.on('user_typing', (data) => {
    const { senderId } = data;
    showTypingIndicator(senderId);
});

// Mark message as read when chat is open
function markMessagesAsRead(receiverId) {
    socket.emit('message_read', { senderId: userId, receiverId });
}

// Listen for read receipt acknowledgment
socket.on('message_read_ack', (data) => {
    console.log(`Message to ${data.receiverId} was read.`);
});

// Update notification count
function updateNotificationCount() {
    notificationCount.innerText = unreadMessages > 0 ? unreadMessages : '';
    notificationCount.style.display = unreadMessages > 0 ? 'block' : 'none';
}

// Show notification
function showNotification() {
    if (!chatDrawer.classList.contains('open')) {
        unreadMessages++;
        updateNotificationCount();
        notification.style.display = 'block';
        notification.innerText = `New message received! (${unreadMessages})`;

        // Play notification sound
        playNotificationSound();

        // Auto-hide notification
        setTimeout(() => notification.style.display = 'none', 3000);
    }
}

// Hide notification
function hideNotification() {
    notification.style.display = 'none';
    unreadMessages = 0;
    updateNotificationCount();
}

// Play notification sound
function playNotificationSound() {
    const audio = new Audio('notification.mp3');
    audio.play().catch(error => console.error('Error playing sound:', error));
}

// Display messages in chat
function addMessageToChat(senderId, message, isSender) {
    const messageElement = document.createElement('div');
    messageElement.classList.add('message', isSender ? 'sent' : 'received');
    messageElement.innerHTML = `<div class="message-content">${message}</div>`;

    messagesContainer.appendChild(messageElement);
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    if (!isSender) showNotification();
}

// Show typing indicator
function showTypingIndicator(senderId) {
    const typingIndicator = document.getElementById('typing-indicator');
    typingIndicator.innerText = `User ${senderId} is typing...`;
    setTimeout(() => typingIndicator.innerText = '', 2000);
}

// Initialize notification count
updateNotificationCount();
