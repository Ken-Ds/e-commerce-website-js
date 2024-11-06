<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "signup_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_POST['userID'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $userType = $_POST['userType'];
    $orgId = ($userType == "vendor") ? $_POST['orgId'] : null;
    $profileImage = null;

   
    if ($userType == "vendor" && isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/ings-website/pages/uploads/" . basename($_FILES["profileImage"]["name"]);
        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $imagePath)) {
            $profileImage = $imagePath;
        }
    }

    
    $sql = "INSERT INTO users (userID, username, email, password, userType, orgId, profileImage) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $userID, $username, $email, $password, $userType, $orgId, $profileImage);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../style/signup.css">
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
