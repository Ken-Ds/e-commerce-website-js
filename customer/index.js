
const express = require('express');
const bodyParser = require('body-parser');
const fileUpload = require('express-fileupload');
var mysql = require('mysql');
const app = express();

app.use(express.static('public'));

const PORT = 3000;

const sql = require('./routes/db');
const getProdsRoute = require('./routes/getProducts');
const getOrgsRoute = require('./routes/getOrgs');
const getOrgDetailsRoute = require('./routes/getOrgDetails');
const getOrders = require('./routes/getOrders.js');
const getOrderDetails = require('./routes/getOrderDetails.js')

app.use('/getproducts', getProdsRoute);
app.use('/getorgs', getOrgsRoute);
app.use('/getorgdetails', getOrgDetailsRoute);
app.use('/getorders', getOrders);
app.use('/getorderdetails', getOrderDetails);

app.listen(PORT, 'localhost', (error) =>{
    if(!error)
        console.log("Server is Successfully Running, and App is listening on port "+ PORT)
    else 
        console.log("Error occurred, server can't start", error);
    }
)