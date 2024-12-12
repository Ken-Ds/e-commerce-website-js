/* AUTHORED BY: EVANGELISTA, KENDRICK */
const express = require('express');
const multer = require('multer'); // Add this line
const router = express.Router();
const conn = require('./db');

const upload = multer({ dest: 'uploads/' });

router.post('/', upload.single('profileImage'), (request, response) => {
    let firstName = request.body.firstName;
    let lastName = request.body.lastName;
    let username = request.body.username;
    let email = request.body.email;
    let password = request.body.password;
    let userType = request.body.userType;
    let orgName = request.body.orgName || null;
    let profileImagePath = request.file?.path;

    console.log(`Path = ${profileImagePath}`)

    let profileImage = null;
    if (profileImagePath) {
        const fs = require('fs');
        const buffer = fs.readFileSync(profileImagePath);
        profileImage = buffer.toString('binary');
    }

    const renderError = (errorMessage) => {
        response.status(400).send(`
            <script>
                alert("${errorMessage}");
                window.history.back();
            </script>
        `);
    };

    let checkUsernameQuery = `SELECT * FROM users WHERE username = ?`;
    conn.query(checkUsernameQuery, [username], (err, results) => {
        if (err) {
            console.error('Error checking username:', err);
            return response.status(500).json({ message: 'Database error', error: err });
        }

        if (results.length > 0) {
            return renderError('Username already exists. Please choose a different username.');
        }

        let checkEmailQuery = `SELECT * FROM users WHERE email = ?`;
        conn.query(checkEmailQuery, [email], (err, results) => {
            if (err) {
                console.error('Error checking email:', err);
                return response.status(500).json({ message: 'Database error', error: err });
            }

            if (results.length > 0) {
                return renderError('Email already exists. Please use a different email address.');
            }
            if (userType == 'customer') {
                orgName = null;
    
                let sql = `
                    INSERT INTO users 
                    (uImg, username, type, email, firstname, lastname, password, orgID)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                `;
                conn.query(sql, [profileImage, username, userType, email, firstName, lastName, password, null], (err, results) => {
                    if (err) {
                        console.error('Error inserting data:', err);
                        renderError('Error creating customer. Please try again.');
                    } else {
                        response.status(201).send(`
                            <script>
                                alert("Customer created successfully!");
                                window.location.href = "../public/signup.html"; // Redirect to login page
                            </script>
                        `);
                    }
                });
            } else if (userType == 'vendor') {
                let orgQuery = `SELECT orgID FROM organizations WHERE oName = ?`;
                conn.query(orgQuery, [orgName], (err, orgResults) => {
                    if (err) {
                        console.error('Error querying organizations:', err);
                        response.status(500).json({ message: 'Database error', error: err });
                    } else if (orgResults.length === 0) {
                        response.status(400).json({ message: `Organization '${orgName}' not found` });
                    } else {
                        let orgId = orgResults[0].orgID;
    
                        let sql = `
                            INSERT INTO users 
                            (uImg, username, type, email, firstname, lastname, password, orgID)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                        `;
                        conn.query(sql, [profileImage, username, userType, email, firstName, lastName, password, orgId], (err, results) => {
                            if (err) {
                                console.error('Error inserting data:', err);
                                renderError('Error creating vendor. Please try again.');
                            } else {
                                response.status(201).send(`
                                    <script>
                                        alert("Vendor created successfully!");
                                        window.location.href = "../public/signup.html"; // Redirect to login page
                                    </script>
                                `);
                            }
                        });
                    }
                });
            } else {
                renderError('Invalid user type. Please select a valid user type.');
            }
        });
    });
});

module.exports = router;