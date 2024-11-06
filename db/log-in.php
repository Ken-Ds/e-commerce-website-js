<?php
include '../db/dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

 
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
          
            session_start();
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['userType'] = $user['userType'];
            
            
            header("Location: ../pages/view-product.php?id={$user['userID']}");
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='../pages/login.php';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid username or password.'); window.location.href='../pages/login.php';</script>";
        exit();
    }
}
?>
