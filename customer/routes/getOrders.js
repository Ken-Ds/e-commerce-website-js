/* AUTHORED BY: EVANGELISTA, KENDRICK */

const express = require('express');
const router = express.Router();
const conn = require('./db');

// Route to get orders for the user from the session
router.get('/', (req, res) => {
    const userID = req.session.userID; // Access userID from session
    console.log(req.session);
    console.log(`User ID: ${userID}`);

    // Check if userID is available in the session
    if (!userID) {
        return res.status(401).json({ error: 'User  ID is not available in session. Please log in.' });
    }

    // SQL query to fetch orders for the specified user
    const query = `
        SELECT 
            orders.orderID, 
            orders.totalAmount, 
            orders.dateOrdered, 
            orders.dateServed,
            orders.status, 
            users.username, 
            organizations.oName AS orgName
        FROM orders
        JOIN users ON orders.userID = users.userID
        JOIN organizations ON orders.orgID = organizations.orgID
        WHERE orders.userID = ?
    `;

    // Execute the query
    conn.query(query, [userID], (err, result) => {
        if (err) {
            console.error('Error executing query:', err);
            return res.status(500).json({ error: 'Unable to load transactions' });
        }

        // Send the result as a JSON response
        res.json(result);
    });
});

module.exports = router;