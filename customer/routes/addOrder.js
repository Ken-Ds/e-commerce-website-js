// AUTHORED BY: MODELO, ALEXX EVAN

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

    const productIDs = booths.flatMap(booth => booth.products.map(product => product.productID));
    console.log('Collected productIDs:', productIDs);

    const orgIDQuery = `
        SELECT DISTINCT orgID
        FROM products
        WHERE productID IN (?)
    `;

    conn.query(orgIDQuery, [productIDs], (err, orgResults) => {
        if (err) {
            console.error('Error fetching orgIDs:', err);
            return res.status(500).json({ success: false, error: 'Failed to validate product orgIDs.' });
        }

        console.log('Fetched orgResults:', orgResults);

        if (orgResults.length !== 1) {
            console.error('Products belong to different organizations:', orgResults);
            return res.status(400).json({ success: false, error: 'All products must belong to the same organization.' });
        }

        const orgID = orgResults[0].orgID;

        const latestOrderQuery = 'SELECT MAX(orderID) AS latestOrderID FROM orders';
        conn.query(latestOrderQuery, (err, result) => {
            if (err) {
                console.error('Error fetching latest orderID:', err);
                return res.status(500).json({ success: false, error: 'Failed to fetch latest orderID.' });
            }

            const latestOrderID = result[0].latestOrderID || 0;
            const newOrderID = latestOrderID + 1;

            const stockValidationPromises = [];

            booths.forEach(booth => {
                const { products } = booth;

                products.forEach(product => {
                    stockValidationPromises.push(new Promise((resolve, reject) => {
                        const productNameQuery = 'SELECT pName, stock FROM products WHERE productID = ?';
                        conn.query(productNameQuery, [product.productID], (err, stockResult) => {
                            if (err || stockResult.length === 0) {
                                reject(`Product ID ${product.productID} not found.`);
                            } else {
                                const availableStock = stockResult[0].stock;
                                const productName = stockResult[0].pName;

                                if (availableStock < product.quantity) {
                                    reject(`Insufficient stock for product '${productName}'. Available: ${availableStock}, Requested: ${product.quantity}`);
                                } else {
                                    const deductStockQuery = 'UPDATE products SET stock = stock - ? WHERE productID = ?';
                                    conn.query(deductStockQuery, [product.quantity, product.productID], (err) => {
                                        if (err) {
                                            reject(`Failed to update stock for product '${productName}'.`);
                                        } else {
                                            resolve();
                                        }
                                    });
                                }
                            }
                        });
                    }));
                });
            });

            Promise.all(stockValidationPromises)
                .then(() => {
                    const insertOrdersPromises = booths.map(booth => {
                        const { totalAmount, products } = booth;

                        return new Promise((resolve, reject) => {
                            const orderQuery = `
                                INSERT INTO orders (orderID, userID, orgID, totalAmount, dateOrdered, status)
                                VALUES (?, ?, ?, ?, NOW(), 'pending')
                            `;
                            conn.query(orderQuery, [newOrderID, userID, orgID, totalAmount], (err) => {
                                if (err) {
                                    reject('Failed to insert order.');
                                } else {
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
                                    conn.query(orderDetailsQuery, [orderDetails], (err) => {
                                        if (err) {
                                            reject('Failed to insert order details.');
                                        } else {
                                            resolve();
                                        }
                                    });
                                }
                            });
                        });
                    });

                    return Promise.all(insertOrdersPromises);
                })
                .then(() => {
                    res.status(200).json({ success: true, message: 'Order placed successfully' });
                })
                .catch(error => {
                    console.error('Error processing order:', error);
                    res.status(400).json({ success: false, error });
                });
        });
    });
});

module.exports = router;
