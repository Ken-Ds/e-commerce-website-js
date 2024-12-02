const express = require('express');
const router = express.Router();
const conn = require('./db');

router.get('/', (request, response) => {
    const query = `SELECT * FROM organizations`;

    conn.query(query, (err, result) => {
        if (err) {
            console.log('Error executing query:', err);  
            response.status(500).json({ error: 'Failed to retrieve organizations' });
            return;
        }

        const orgsWithBase64Images = result.map(org => {
            if (org.oLogo) {
                const base64Image = org.oLogo.toString('base64');
                org.oLogo = `data:image/jpeg;base64,${base64Image}`; 
            }
            return org;
        });

        response.json(orgsWithBase64Images);
    });
});

module.exports = router;
