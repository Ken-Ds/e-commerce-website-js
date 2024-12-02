const express = require('express');
const router = express.Router();
const conn = require('./db');

router.get('/', (request, response) => {

    const userID = request.query.userID || request.params.userID;

    if (!userID) {
        response.status(400).json({ error: 'User ID is required' });
        return;
    }

    const query = `
        SELECT orders.orderID, orders.totalAmount, orders.dateOrdered, orders.dateServed,
               orders.status, users.username, organizations.oName AS orgName
        FROM orders
        JOIN users ON orders.userID = users.userID
        JOIN organizations ON orders.orgID = organizations.orgID
        WHERE orders.userID = ?
    `;

    conn.query(query, [userID], (err, result) => {
        if (err) {
            console.error('Error executing query:', err);
            response.status(500).json({ error: 'Unable To Load Transactions' });
            return;
        }

        response.json(result);
    });
});

module.exports = router;