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
            </form>
        </div>
    </div>
</body>
</html>
