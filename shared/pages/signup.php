<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../style/css/signup.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <img src="../style/img/logo.png" alt="Logo">
            <h1>In Genius Orders</h1>
        </div>

        <div class="right-section" style="background: url('../style/img/events-bg.jpg') no-repeat center center; background-size: cover;">
            <div class="signup-form">
                <h2>Create an Account</h2>
                <form action="signup.php" method="POST" enctype="multipart/form-data">
                    <div class="name">

                    </div>
                    <input type="text" name="userID" placeholder="UserID" required>
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    
                    <div class="user-type">
                        <label>
                            <input type="radio" name="userType" value="customer" onclick="toggleOrgId()" checked> Customer
                        </label>
                        <label>
                            <input type="radio" name="userType" value="vendor" onclick="toggleOrgId()"> Vendor
                        </label>
                    </div>

                    <div id="orgIdContainer" style="display: none;">
                        <input type="text" name="orgId" placeholder="OrgID">
                    </div>

                    <div id="imageUploadContainer" style="display: none;">
                        <input type="file" name="profileImage" accept="image/*">
                    </div>

                    <button type="submit">Sign Up</button>
                </form>
                <div class="login-text">
                    <p>Already have an account? <a href="login.php">Log In</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleOrgId() {
            var userType = document.querySelector('input[name="userType"]:checked').value;
            document.getElementById('orgIdContainer').style.display = (userType === "vendor") ? "block" : "none";
            document.getElementById('imageUploadContainer').style.display = (userType === "vendor") ? "block" : "none";
        }
    </script>
</body>
</html>
