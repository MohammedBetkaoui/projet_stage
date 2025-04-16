const express = require('express');
const router = express.Router();
const db = require('../config/db'); // Database connection
const io = require('../socket'); // Socket.io instance
const apiKeyMiddleware = require('../middlewares/apiKeyMiddleware'); // API key middleware

// Protect route with API key authentication
router.post('/add', apiKeyMiddleware, async (req, res) => {
  const connection = await db.getConnection();
  await connection.beginTransaction();

  try {
    const {
      company_id,
      title,
      description,
      sector,
      location,
      start_date,
      end_date,
      deadline,
      compensation,
      branch_id
    } = req.body;

    // Insert offer into the database
    const offerSql = `INSERT INTO offers (company_id, title, description, sector, location, start_date, end_date, deadline, compensation, branch_id) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;
    const offerValues = [
      company_id,
      title,
      description,
      sector,
      location,
      start_date,
      end_date,
      deadline,
      compensation,
      branch_id ?? null
    ];

    const [offerResult] = await connection.execute(offerSql, offerValues);
    const offerId = offerResult.insertId;

    // Fetch users that belong to the same branch_id as the offer
    const [users] = await connection.execute('SELECT id FROM users WHERE id_branch = ?', [branch_id]);
    
    if (users.length > 0) {
      const notificationValues = users.map(user => [user.id, `New offer added: ${title}`]);
      const notificationSql = `INSERT INTO notifications (user_id, message) VALUES ?`;
      await connection.query(notificationSql, [notificationValues]);

      // Emit offer notification to each user in the same branch
      const ioInstance = io.getIO();
      users.forEach(user => {
        ioInstance.to(`user_${user.id}`).emit('new_offer', { 
          message: `New offer: ${title}`, 
          offerId 
        });
      });
    }

    await connection.commit();

    res.status(201).json({ message: 'Offer added successfully', offerId });

  } catch (error) {
    await connection.rollback();
    console.error('Error adding offer:', error);
    res.status(500).json({ error: 'Failed to add offer' });
  } finally {
    connection.release();
  }
});

module.exports = router;
