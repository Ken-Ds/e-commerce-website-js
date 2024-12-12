/*  AUTHORED BY: BURGOS, MIGUEL JOSHUA */

const express = require('express');
const router = express.Router();
const conn = require('./db');

// Get user profile details
router.get('/', (request, response) => {

    const userID = request.session.userID;

    if (!userID) {
        response.status(400).json({ error: 'User ID is required' });
        return;
    }

    const query = `
        SELECT userID, uImg, username, type, email, firstname, lastname
        FROM USERS
        WHERE userID = ?
    `;

    conn.query(query, [userID], (err, result) => {
        if (err) {
            console.error('Error executing query:', err);
            response.status(500).json({ error: 'Unable to fetch user details' });
            return;
        }

        if (result.length === 0) {
            return response.status(404).json({ error: 'User not found' });
        }

      
        const userWithBase64Image = result.map(user => {
            if (user.uImg) {
                const base64Image = user.uImg.toString('base64');
                user.uImg = `data:image/jpeg;base64,${base64Image}`;
            }
            return user;
        });

        response.json(userWithBase64Image[0]); 
    });
});

module.exports = router;
