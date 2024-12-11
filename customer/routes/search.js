// Import required modules
const express = require('express');
const router = express.Router();
const conn = require('./db'); 


router.get('/', (request, response) => {
    const productName = request.query.productName;

    if (!productName) {
        response.status(400).json({ error: 'Product name is required' });
        return;
    }

    const query = `
        SELECT 
            org.oName, 
            org.oLogo, 
            prod.productID, 
            prod.pName, 
            prod.pDesc, 
            prod.pImage, 
            prod.price, 
            prod.stock 
        FROM 
            ORGANIZATIONS org
        JOIN 
            PRODUCTS prod 
        ON 
            org.orgID = prod.orgID
        WHERE 
            prod.pName = ?
    `;

    conn.query(query, [productName], (err, results) => {
        if (err) {
            console.error('Error executing query:', err.message);
            response.status(500).json({ error: 'Unable to fetch product details' });
            return;
        }

        if (results.length === 0) {
            // Product not found
            response.status(404).json({ error: 'Product not found' });
            return;
        }

        const responseData = {
            product: {
                productID: results[0].productID,
                name: results[0].pName,
                description: results[0].pDesc,
                image: results[0].pImage ? `data:image/jpeg;base64,${results[0].pImage.toString('base64')}` : null,
                price: results[0].price,
                stock: results[0].stock,
            },
            organization: {
                name: results[0].oName,
                logo: results[0].oLogo ? `data:image/jpeg;base64,${results[0].oLogo.toString('base64')}` : null,
            }
        };

        response.json(responseData);
    });
});



router.post('/', (request, response) => {
    const { userID, productID, quantity, totalAmount } = request.body;


    if (!userID || !productID || !quantity || !totalAmount) {
        return response.status(400).json({ error: 'Missing required fields' });
    }

    
    console.log('Creating order with data:', { userID, productID, quantity, totalAmount });

 
    const orderQuery = `
        INSERT INTO ORDERS (userID, totalAmount, dateOrdered, status)
        VALUES (?, ?, NOW(), 'Pending')
    `;
    
    conn.query(orderQuery, [userID, totalAmount], (err, result) => {
        if (err) {
            console.error('Error creating order:', err.message);
            return response.status(500).json({ error: 'Unable to create order' });
        }

     
        const orderID = result.insertId;

       
        const orderDetailsQuery = `
            INSERT INTO ORDERDETAILS (orderID, productID, quantity, price)
            VALUES (?, ?, ?, ?)
        `;

        conn.query(orderDetailsQuery, [orderID, productID, quantity, totalAmount], (err) => {
            if (err) {
                console.error('Error adding order details:', err.message); 
                return response.status(500).json({ error: 'Unable to add order details' });
            }

            response.status(200).json({ message: 'Product added to cart and order created successfully' });
        });
    });
});

module.exports = router;
