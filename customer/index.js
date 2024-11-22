
const express = require('express');
const bodyParser = require('body-parser');
const fileUpload = require('express-fileupload');
var mysql = require('mysql');

const app = express();

app.use(express.static('public'));

const sq = require("db");


const PORT = 3000;

const router = express.Router();

router.post('/'), (request, response) => {
    let custname = request.body.custname;
}

app.listen(PORT, (error) =>{
    if(!error)
        console.log("Server is Successfully Running, and App is listening on port "+ PORT)
    else 
        console.log("Error occurred, server can't start", error);
    }
)