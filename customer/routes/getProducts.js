const express = require('express');
const router = express.Router();
const conn = require('./db');

router.get('/', (request, response) => {
    const query = `SELECT * FROM products`;

    conn.query(query, (err, result) => {
        if (err) {
            console.log('Error executing query:', err);  
            response.status(500).json({ error: 'Failed to retrieve products' });
            return;
        }

        const productsWithBase64Images = result.map(product => {
            if (product.pImage) {
                const base64Image = product.pImage.toString('base64');
                product.pImage = `data:image/jpeg;base64,${base64Image}`; 
            }
            return product;
        });

        response.json(productsWithBase64Images);
    });
});

module.exports = router;
