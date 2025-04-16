const express = require('express');
const http = require('http');
const cors = require('cors');
const bodyParser = require('body-parser');
const { initSocket } = require('./socket');
const offers = require('./routes/offers');

const app = express();
const server = http.createServer(app);

// Initialize Socket.io
initSocket(server);

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Routes
app.use('/api', offers);

// Start the server
const PORT = process.env.PORT || 5000;
server.listen(PORT, () => {
  console.log(`Server is running on http://localhost:${PORT}`);
});