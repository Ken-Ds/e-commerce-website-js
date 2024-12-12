/* AUTHORED BY: CAGULADA, SHERYN ANN */

const express = require('express');
const router = express.Router();
const conn = require('./db');

router.get('/', (request, response) => {
    const orgID = request.query.orgID;
    console.log("Received orgID: ", orgID);

    const cat = request.query.cat;
    console.log("Received cat: ", cat);

    const alpha = request.query.alpha;
    console.log("Received alpha: ", alpha);

    const price = request.query.price;
    console.log("Received price: ", price);

    const search = request.query.search;
    console.log("Received search ", search)

    let query = `SELECT p.* FROM products p LEFT JOIN orderdetails od ON p.productID = od.productID`;
    let queryParams = [];

    if (search){
        query += ` WHERE p.pName LIKE ?`
        key = "%" + search + "%"
        queryParams.push(key);
    }

    if (orgID) {
        query += ` WHERE p.orgID = ?`;
        queryParams.push(orgID);
    }

    if (cat) {
        if (orgID) {
            query += ` AND p.pCategory = ?`;
        } else {
            query += ` WHERE p.pCategory = ?`;
        }
        queryParams.push(cat);
    }

    query += ` GROUP BY p.productID, p.orgID, p.pName, p.pDesc, p.pImage, p.pCategory, p.stock, p.price`;

    if (alpha) {
        query += alpha === "Ascending (A-Z)" ? ` ORDER BY p.pName ASC` : ` ORDER BY p.pName DESC`;
    } else if (price) {
        query += price === "Highest to Lowest" ? ` ORDER BY p.price DESC` : ` ORDER BY p.price ASC`;
    } else {
        query += ` ORDER BY IFNULL(SUM(od.quantity), 0) DESC`;
    }

    conn.query(query, queryParams, (err, result) => {
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
