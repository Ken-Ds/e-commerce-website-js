//AUTHOR: ALEXX EVAN O. MODELO
const express = require('express');
const router = express.Router();
const conn = require('./db');

router.get('/', async (req, res) => {
    const userID = req.query.userID;

    if (!userID) {
        return res.status(400).json({ error: 'UserID is required' });
    }

    const query = `
        SELECT c.productID, c.quantity, p.pName, p.pImage, p.price, p.pDesc, o.oName, o.oLogo 
        FROM cart c
        JOIN products p ON c.productID = p.productID
        JOIN organizations o ON p.orgID = o.orgID
        WHERE c.userID = ?
    `;

    conn.query(query, [userID], (err, results) => {
        if (err) {
            console.error('Error fetching cart items:', err);
            return res.status(500).json({ error: 'Unable to load cart items' });
        }

        const cartData = results.map((item) => {
            if (item.pImage) {
                item.pImage = `data:image/jpeg;base64,${item.pImage.toString('base64')}`;
            }
            if (item.oLogo) {
                item.oLogo = `data:image/jpeg;base64,${item.oLogo.toString('base64')}`;
            }
            return item;
        });

        res.json(cartData);
    });
});

module.exports = router;
