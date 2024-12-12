const express = require('express');
const router = express.Router();
const conn = require('./db');

router.get('/', (request, response) => {
    const productID = request.query.productID;
    console.log("Retrieved product: ", productID);

    const query = `SELECT * FROM products WHERE productID = ?`;

    conn.query(query, [productID], (err, result) => {
        if (err) {
            console.log('Error executing query:', err);  
            response.status(500).json({ error: 'Failed to retrieve products' });
            return;
        }

        if (result.length === 0) {
            response.status(404).json({ error: 'Product not found' });
            return;
        }

        const product = result[0];
        if (product.pImage) {
            const base64Image = product.pImage.toString('base64');
            product.pImage = `data:image/jpeg;base64,${base64Image}`; 
        }

        response.json(product);
    });
});

module.exports = router;