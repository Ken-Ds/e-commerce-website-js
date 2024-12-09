const express = require('express');
const bodyParser = require('body-parser');
const fileUpload = require('express-fileupload');
var mysql = require('mysql');
const app = express();

app.use(express.static('public'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

const PORT = 3000;

const sql = require('./routes/db');
const getProdsRoute = require('./routes/getProducts');
const getOrgsRoute = require('./routes/getOrgs');
const getOrgDetailsRoute = require('./routes/getOrgDetails');
const getOrders = require('./routes/getOrders.js');
const getOrderDetails = require('./routes/getOrderDetails.js');
const getProfile = require('./routes/getProfile.js');
const getCart = require('./routes/getCart.js');
const deleteFromCart = require('./routes/deleteFromCart.js');
const addOrder = require('./routes/addOrder.js');
const signup = require('./routes/signup.js');

app.use('/getproducts', getProdsRoute);
app.use('/getorgs', getOrgsRoute);
app.use('/getorgdetails', getOrgDetailsRoute);
app.use('/getorders', getOrders);
app.use('/getorderdetails', getOrderDetails);
app.use('/getprofile', getProfile);
app.use('/getcart', getCart);
app.use('/delete-from-cart', deleteFromCart);
app.use('/add-order', addOrder)
app.use('/signup', signup);

app.listen(PORT, 'localhost', (error) => {
    if (!error)
        console.log("Server is Successfully Running, and App is listening on port " + PORT);
    else 
        console.log("Error occurred, server can't start", error);
});
