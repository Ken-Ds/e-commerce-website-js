<?php
include '../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            echo "Step 1: Connected to database<br>";
        }
    
        $username = $_POST['username'];
        $password = $_POST['password'];
    
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        if (!$stmt) {
            die("Statement preparation failed: " . $conn->error);
        }
    
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo "Step 2: Username found in database<br>";
    
            if ($password === $user['password']) { 
                echo "Step 3: Password matched<br>";
    
                session_start();
                $_SESSION['userID'] = $user['userID'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['userType'] = $user['userType'];
                
                echo "Step 4: Session variables set successfully<br>";
    
                header("Location: ../pages/view-product.php?id={$user['orgID']}");
                exit();
            } else {
                echo "Password did not match.<br>";
            }
        } else {
            echo "Username not found in database.<br>";
        }
    
        $stmt->close();
        $conn->close();
    }
}
?>