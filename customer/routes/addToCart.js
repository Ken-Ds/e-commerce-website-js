//AUTHOR: ALEXX EVAN O. MODELO
const express = require('express');
const router = express.Router();
const conn = require('./db');

router.use(express.json());

router.delete('/', (req, res) => {
    const { userID, productID } = req.body;
    if (!userID || !productID) {
        return res.status(400).json({ error: 'userID and productID are required' });
    }

    const query = `
        DELETE FROM cart WHERE userID = ? AND productID = ?
    `;

    conn.query(query, [userID, productID], (err, result) => {
        if (err) {
            console.error('Error deleting product from cart:', err);
            return res.status(500).json({ error: 'Unable to delete product from cart' });
        }

        if (result.affectedRows === 0) {
            return res.status(404).json({ error: 'Product not found in cart' });
        }

        res.json({ success: 'Product has been removed from the cart.' });
    });
});

module.exports = router;
