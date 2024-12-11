<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In Genius Orders</title>
    <link rel="stylesheet" href="../style/css/login.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="../style/img/logo.png" alt="Logo">
            <h1>In Genius Orders</h1>
        </div>
        <div class="right-section">
            <form class="login-form" action="../db/log-in.php" method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Log In</button>
                <p class="signup-text">Don't have an account? <a href="http://localhost:3000/signup.html">Sign Up</a></p>
            </form> 
        </div>
    </div>
</body>

<script>
    window.onload = function() {
        const params = getQueryParams();
        if (params.logout === 'success') {
            alert('You have successfully logged out.');
        } else {
            alert('No user is logged in');
        }
    };
</script>
</html>
