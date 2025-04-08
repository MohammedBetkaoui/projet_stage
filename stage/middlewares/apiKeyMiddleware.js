require('dotenv').config(); // Load environment variables

const apiKeyMiddleware = (req, res, next) => {
  const apiKey = req.headers['x-api-key']; // Get API key from request headers
  const validApiKey = process.env.API_KEY; // Get the valid API key from environment variables

  if (!apiKey || apiKey !== validApiKey) {
    return res.status(403).json({ error: 'Forbidden: Invalid API Key' });
  }

  next(); // Proceed if the API key is valid
};

module.exports = apiKeyMiddleware;
