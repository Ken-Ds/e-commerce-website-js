const express = require('express');
const session = require('express-session')
const bodyParser = require('body-parser');
const fileUpload = require('express-fileupload');
var mysql = require('mysql');
const app = express();

app.use(session({
    secret: 'secret_key',
    resave: true,
    saveUninitialized: false,
}));

app.use(express.static('public'));
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

const activeSessions = {};

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

app.get('/login-php', (req, res) => {
    res.redirect('http://localhost/ings-website/shared/pages/login.php');
});

app.post('/set-session', (req, res) => {
    const { userID } = req.body;
    if (userID) {
        req.session.userID = userID;
        console.log(`User  ID ${userID} has been set in the session.`);
        res.status(200).send('Session set successfully');
    } else {
        res.status(400).send('User  ID is required');
    }
});

app.get('/logout', (req, res) => {
    if (req.session.userID) {
        req.session.destroy(err => {
            if (err) {
                return res.status(500).send('Could not log out');
            }
            delete activeSessions[req.session.userID];
            console.log(`User  ID ${req.session.userID} has logged out.`);
            res.redirect('http://localhost/ings-website/shared/pages/login.php?logout=success');
        });
    } else {
        req.session.destroy(err => {
            if (err) {
                return res.status(400).send('Could not log out');
            }
            delete activeSessions[req.session.userID];
            console.log(`No User To log Out`);
            res.redirect('http://localhost/ings-website/shared/pages/login.php?logout=noUser');
        });     
    }
});

app.listen(PORT, 'localhost', (error) => {
    if (!error)
        console.log("Server is Successfully Running, and App is listening on port " + PORT);
    else 
        console.log("Error occurred, server can't start", error);
});
