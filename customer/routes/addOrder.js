//AUTHOR: ALEXX EVAN O. MODELO

const express = require('express');
const router = express.Router();
const conn = require('./db');

router.post('/', (req, res) => {
    const { userID, booths } = req.body;

    console.log('Received userID:', userID);
    console.log('Received booths:', booths);

    if (!userID || !booths || booths.length === 0) {
        return res.status(400).json({ success: false, error: 'Invalid input' });
    }

    const latestOrderQuery = 'SELECT MAX(orderID) AS latestOrderID FROM orders';
    conn.query(latestOrderQuery, (err, result) => {
        if (err) {
            console.error('Error fetching latest orderID:', err);
            return res.status(500).json({ success: false, error: 'Failed to fetch latest orderID' });
        }

        const latestOrderID = result[0].latestOrderID || 0;
        const newOrderID = latestOrderID + 1;
        booths.forEach(booth => {
            const { totalAmount, products } = booth;
            const productOrgQueries = products.map(product => {
                return new Promise((resolve, reject) => {
                    const orgIDQuery = 'SELECT orgID FROM products WHERE productID = ?';
                    conn.query(orgIDQuery, [product.productID], (err, result) => {
                        if (err || result.length === 0) {
                            reject(`Failed to find orgID for productID: ${product.productID}`);
                        } else {
                            product.orgID = result[0].orgID;
                            resolve();
                        }
                    });
                });
            });
            Promise.all(productOrgQueries)
                .then(() => {
                    const orgID = products[0].orgID; 
                    const orderQuery = `
                        INSERT INTO orders (orderID, userID, orgID, totalAmount, dateOrdered, status)
                        VALUES (?, ?, ?, ?, NOW(), 'pending')
                    `;
                    conn.query(orderQuery, [newOrderID, userID, orgID, totalAmount], (err, result) => {
                        if (err) {
                            console.error('Error inserting order:', err);
                            return res.status(500).json({ success: false, error: 'Failed to insert order' });
                        }

                        const orderDetails = products.map(product => [
                            newOrderID,
                            product.productID,
                            product.quantity,
                            product.price
                        ]);

                        const orderDetailsQuery = `
                            INSERT INTO orderdetails (orderID, productID, quantity, price)
                            VALUES ?
                        `;
                        conn.query(orderDetailsQuery, [orderDetails], (err, result) => {
                            if (err) {
                                console.error('Error inserting order details:', err);
                                return res.status(500).json({ success: false, error: 'Failed to insert order details' });
                            }

                            res.status(200).json({ success: true, message: 'Order placed successfully' });
                        });
                    });
                })
                .catch(error => {
                    console.error('Error fetching orgID for products:', error);
                    res.status(500).json({ success: false, error: 'Failed to fetch orgID for products' });
                });
        });
    });
});

module.exports = router;
