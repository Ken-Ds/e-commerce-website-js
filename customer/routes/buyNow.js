/* AUTHORED BY: EVANGELISTA, KENDRICK, & LUMANGLAS, YENZY HYNNA */

const express = require('express');
const router = express.Router();
const conn = require('./db');

router.post('/', (request, response) => {
    const userID = request.session.userID 
    console.log("buy-now");
    console.log(request.session.userID);
    const {productID, quantity, orgID } = request.body;
    console.log("prod ID"+productID);
    

    // Validate input
    if (!userID || !productID || !quantity || !orgID || quantity <= 0) {
        return response.status(400).json({ error: 'Invalid input. Please check the product, quantity and organization.' });
    }

    // Check product stock
    const productQuery = 'SELECT * FROM products WHERE productID = ?';
    conn.query(productQuery, [productID], (err, productResults) => {
        if (err) {
            console.error('Database error:', err);
            return response.status(500).json({ error: 'Internal server error.' });
        }

        if (productResults.length === 0) {
            return response.status(404).json({ error: 'Product not found.' });
        }

        const product = productResults[0];

        if (product.stock < quantity) {
            return response.status(400).json({ error: `Only ${product.stock} units available.` });
        }

        // Update product stock
        const newStock = product.stock - quantity;
        const updateStockQuery = 'UPDATE products SET stock = ? WHERE productID = ?';

        conn.query(updateStockQuery, [newStock, productID], (updateErr) => {
            if (updateErr) {
                console.error('Error updating product stock:', updateErr);
                return response.status(500).json({ error: 'Internal server error while updating stock.' });
            }

            // Orders Table
            const totalAmount = product.price * quantity;
            const orderQuery = `
                INSERT INTO orders (userID, orgID, totalAmount, status, dateOrdered)
                VALUES (?, ?, ?, 'Pending', NOW())
            `;

            conn.query(orderQuery, [userID, orgID, totalAmount], (orderErr, orderResult) => {
                if (orderErr) {
                    console.error('Order placement error:', orderErr);
                    return response.status(500).json({ error: 'Internal server error while placing order.' });
                }

                const orderID = orderResult.insertId;

                // Order Details Table
                const orderDetailsQuery = `
                    INSERT INTO orderDetails (orderID, productID, quantity, price)
                    VALUES (?, ?, ?, ?)
                `;
                conn.query(orderDetailsQuery, [orderID, productID, quantity, product.price], (detailsErr) => {
                    if (detailsErr) {
                        console.error('Error inserting order details:', detailsErr);
                        return response.status(500).json({ error: 'Internal server error while adding order details.' });
                    }

                    console.log('Order placed successfully. Order ID:', orderID);
                    response.status(200).json({
                        message: 'Order placed successfully!',
                        orderID: orderID,
                        redirectURL: '/view-transactions.html'
                    });
                });
            });
        });
    });
});

module.exports = router;