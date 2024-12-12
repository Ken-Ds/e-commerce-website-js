/* AUTHORED BY: BURGOS, MIGUEL JOSHUA */

const mysql = require("mysql");

var connection = mysql.createConnection(
   { host: 'localhost',
    port: 3306,
    user: 'root',
    password: '',
    database: 'ings'
   });
   connection.connect(err => {
    if (err) throw err;
   });
   module.exports = connection;