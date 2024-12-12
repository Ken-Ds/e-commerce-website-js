const express = require('express');
const session = require('express-session')
const bodyParser = require('body-parser');
const fileUpload = require('express-fileupload');

let open;
(async () => {
    open = (await import('open')).default;
})();
var mysql = require('mysql');
const app = express();

app.use(express.static('public'));
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

app.use(session({
    secret: 'secret_key',
    resave: true,
    saveUninitialized: false,
    cookie: {secure: false}
}));


const sql = require('./routes/db');
const getProdsRoute = require('./routes/getProducts');
const getOrgsRoute = require('./routes/getOrgs');
const getOrgDetailsRoute = require('./routes/getOrgDetails');
const getOrders = require('./routes/getOrders');
const getOrderDetails = require('./routes/getOrderDetails');
const getProfile = require('./routes/getProfile');
const getCart = require('./routes/getCart');
const deleteFromCart = require('./routes/deleteFromCart');
const addOrder = require('./routes/addOrder');
const signup = require('./routes/signup');
const search = require('./routes/search');
const addToCart= require('./routes/addToCart');
const getProductDetails = require('./routes/getProductDetails');

app.use(express.json());

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
app.use('/search', search);
app.use('/getproductdetails', getProductDetails);
app.use('/add-to-cart', addToCart);
app.use('/addorder', addOrder);

const PORT = 3000;

var id = null;

app.get('/', (req, res) => {
app.get('/', (req, res) => {
    console.log(`Passing ID${id}`);
    req.session.userID = id;
    console.log(`Got ID${req.session.userID}`);
    res.redirect('/view-booths.html')
});

app.post('/set-session', (req, res) => {
app.post('/set-session', (req, res) => {
    const { userID } = req.body;
    console.log(req.body);
    if (userID) {
        id = userID;
        open('http://localhost:3000');
    } else {
        res.status(400).send('User ID is required');
    }
}); 
});

app.get('/view-cart', (req, res) => {
    console.log(`Cart ID ${req.session.userID}`)
    res.redirect(`/view-cart.html?userID=${req.session.userID}`)
});

app.get('/login-php', (req, res) => {
    res.redirect('http://localhost/ings-website/shared/pages/login.php');
});

app.get('/logout', (req, res) => {
    if (req.session.userID) {
        req.session.destroy(err => {
            if (err) {
                return res.status(500).send('Could not log out');
            }
            console.log(`User  Have Been Logged Out`);
            res.redirect('http://localhost/ings-website/shared/pages/login.php?logout=success');
        });
    } else {
        req.session.destroy(err => {
            if (err) {
                return res.status(400).send('Could not log out');
            }
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
