
const express = require('express');
const bodyParser = require('body-parser');
const fileUpload = require('express-fileupload');
var mysql = require('mysql');
const app = express();

app.use(express.static('public'));

const PORT = 3000;

const sql = require('./routes/db');
const getProdsRoute = require('./routes/getProducts');

app.use('/getproducts', getProdsRoute);

app.listen(PORT, 'localhost', (error) =>{
    if(!error)
        console.log("Server is Successfully Running, and App is listening on port "+ PORT)
    else 
        console.log("Error occurred, server can't start", error);
    }
)