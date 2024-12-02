const express = require('express');
const router = express.Router();
const conn = require('./db');
            
router.get('/', (request, response) => {
            
    const orderID = request.query.orderID || request.params.orderID;
            
    if (!orderID) {
        response.status(400).json({ error: 'Order is required' });
        return;
    }
            
    const query = `
        SELECT od.orderID, p.productID, p.pName, p.pImage, od.quantity, p.price, p.pDesc
            FROM orderdetails od
            JOIN products p ON od.productID = p.productID
            WHERE od.orderID = ?
    `;
            
    conn.query(query, [orderID], (err, result) => {
        if (err) {
            console.error('Error executing query:', err);
            response.status(500).json({ error: 'Unable To Load Product Details' });
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