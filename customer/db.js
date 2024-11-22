const mysql = require("mysql");

var connection = mysql.createConnection(
   { host: 'localhost',
    port: 3000,
    user: 'root',
    password: '',
    database: 'ings'
   });
   connection.connect(err => {
    if (err) throw err;
   });
   module.exports = connection;