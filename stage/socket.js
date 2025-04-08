const socketIO = require('socket.io');

let io;
const users = {}; // Store online users with their socket IDs

const initSocket = (server) => {
  io = socketIO(server, {
    cors: {
      origin: '*', // Allow all origins (change this in production)
      methods: ['GET', 'POST'],
    },
  });

  io.on('connection', (socket) => {
    console.log('User connected:', socket.id);

    // User joins with their ID
    socket.on('join_user_room', (userId) => {
      if (userId) {
        users[userId] = socket.id; // Map userId to socket ID
        socket.join(`user_${userId}`);
        console.log(`User ${userId} joined room: user_${userId}`);
      }
    });

    // Send message to specific user
    socket.on('send_message', ({ senderId, receiverId, message }) => {
      console.log(`Message from ${senderId} to ${receiverId}: ${message}`);

      if (users[receiverId]) {
        io.to(`user_${receiverId}`).emit('receive_message', { senderId, message });
      }
    });

    // Notify when a user is typing
    socket.on('typing', ({ senderId, receiverId }) => {
      io.to(`user_${receiverId}`).emit('user_typing', { senderId });
    });

    // Notify when a message is read
    socket.on('message_read', ({ senderId, receiverId }) => {
      io.to(`user_${senderId}`).emit('message_read_ack', { receiverId });
    });

    socket.on('disconnect', () => {
      console.log('User disconnected:', socket.id);
      const userId = Object.keys(users).find((key) => users[key] === socket.id);
      if (userId) delete users[userId];
    });
  });

  return io;
};

const getIO = () => {
  if (!io) {
    throw new Error('Socket.io not initialized!');
  }
  return io;
};

module.exports = { initSocket, getIO };
