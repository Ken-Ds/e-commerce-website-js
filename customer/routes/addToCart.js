const express = require('express');
const router = express.Router();
const conn = require('./db');

router.post('/', (request, response) => {
    console.log("IN ADD TO CART");
    const { userID, productID, quantity } = request.body;

    // Validate input
    if (!userID || !productID || !quantity || quantity <= 0) {
        return response.status(400).json({ error: 'Invalid input' });
    }

    // Check if the item already exists in the cart for the user
    const checkQuery = `SELECT quantity FROM cart WHERE userID = ? AND productID = ?`;
    
    conn.query(checkQuery, [userID, productID], (err, result) => {
        if (err) {
            console.log('Error executing query:', err);
            return response.status(500).json({ error: 'Database error' });
        }

        if (result.length > 0) {
            // Item exists in the cart, check current stock
            const existingQuantity = result[0].quantity;
            const newQuantity = parseInt(existingQuantity) + parseInt(quantity);

            // Check stock for the product
            const stockQuery = `SELECT stock FROM products WHERE productID = ?`;
            conn.query(stockQuery, [productID], (err, stockResult) => {
                if (err) {
                    console.log('Error executing query:', err);
                    return response.status(500).json({ error: 'Database error' });
                }

                if (stockResult.length === 0) {
                    return response.status(404).json({ error: 'Product not found' });
                }

                const currentStock = stockResult[0].stock;

                // Check if the updated quantity exceeds the current stock
                if (newQuantity > currentStock) {
                    return response.json({ error: 'Insufficient stock' });
                }

                // Update the quantity in the cart
                const updateQuery = `UPDATE cart SET quantity = ? WHERE userID = ? AND productID = ?`;
                conn.query(updateQuery, [newQuantity, userID, productID], (err) => {
                    if (err) {
                        console.log('Error updating quantity:', err);
                        return response.status(500).json({ error: 'Failed to update quantity' });
                    }
                    return response.json({ message: 'Quantity updated successfully', newQuantity });
                });
            });
        } else {
            console.log("NO CART ENTRY");
            // Item does not exist in the cart, check stock for the product
            const stockQuery = `SELECT stock FROM products WHERE productID = ?`;
            conn.query(stockQuery, [productID], (err, stockResult) => {
                if (err) {
                    console.log('Error executing query:', err);
                    return response.status(500).json({ error: 'Database error' });
                }

                if (stockResult.length === 0) {
                    return response.status(404).json({ error: 'Product not found' });
                }

                const currentStock = stockResult[0].stock;

                // Check if the requested quantity is available
                if (quantity > currentStock) {
                    return response.json({ error: 'Insufficient stock' });
                }

                // Insert a new record in the cart
                const insertQuery = `INSERT INTO cart (userID, productID, quantity) VALUES (?, ?, ?)`;
                conn.query(insertQuery, [userID, productID, quantity], (err) => {
                    if (err) {
                        console.log('Error inserting into cart:', err);
                        return response.status(500).json({ error: 'Failed to add item to cart' });
                    }
                    return response.json({ message: 'Item added to cart successfully' });
                });
            });
        }
    });
});

module.exports = router;