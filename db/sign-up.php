<?php
include "../db/dbconnect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $userType = $_POST['userType'];
    $orgId = ($userType == "vendor") ? $_POST['orgId'] : null;
    $imgContent = null;

   
    // Handle image upload
    if (!empty($_FILES["profileImage"]["name"])) {
        $fileName = basename ($_FILES["profileImage"]["name"]);
        $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $uploadOk = true;

        // Check if image file is an actual image or fake
        $check = getimagesize($_FILES["profileImage"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('Uploaded file is not a valid image.'); window.location.href = '../pages/add-product.php?id={$orgId}';</script>";
            $uploadOk = false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
            echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.'); window.location.href = '../pages/add-product.php?id={$orgId}';</script>";
            $uploadOk = false;
        }

        $image = $_FILES["profileImage"]["tmp_name"];
        $imgContent = addslashes(file_get_contents($image));
    }

    if ($userType == "vendor"){
        $sql = "INSERT INTO users (username, email, password, type, orgId, uImg) VALUES (?, ?, ?, ?, ?, '".$imgContent."')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $username, $email, $password, $userType, $orgId);
    } else {
        $sql = "INSERT INTO users (username, email, password, type, uImg) VALUES (?, ?, ?, ?, '".$imgContent."')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $password, $userType);
    }

    if ($stmt->execute()) {
        echo "<script>alert('User Registration Successful!'); window.location.href='../pages/login.php'</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>