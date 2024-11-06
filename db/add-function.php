<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pName = trim($_POST['pName']);
    $pCategory = $_POST['pCategory'];
    $pDesc = trim($_POST['pDesc']);
    $stock = intval($_POST['stock']);
    $price = floatval($_POST['price']);
    $pImage = $_FILES['pImage'];

    // Set orgID to 1 as a default value
    $orgId = 1;  // Default orgID

    // Validate inputs
    if (empty($pName) || empty($pCategory) || $stock < 0 || $price < 0) {
        echo "<script>alert('Please provide valid input for all fields.'); window.location.href = 'add-product.php';</script>";
        exit();
    }

    // Handle image upload
    if (!empty($pImage['tmp_name'])) {
        $targetDir = "../uploads/";
        $imageFileType = strtolower(pathinfo($pImage["name"], PATHINFO_EXTENSION));
        $targetFile = $targetDir . uniqid() . "." . $imageFileType;
        $uploadOk = true;

        // Check if image file is an actual image or fake
        $check = getimagesize($pImage["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('Uploaded file is not a valid image.'); window.location.href = 'add-product.php';</script>";
            $uploadOk = false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
            echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.'); window.location.href = 'add-product.php';</script>";
            $uploadOk = false;
        }

        // Upload file if valid
        if ($uploadOk && move_uploaded_file($pImage["tmp_name"], $targetFile)) {
            $imagePath = $targetFile; // Store the path of the uploaded image
        } else {
            echo "<script>alert('Error uploading image. Please try again.'); window.location.href = 'add-product.php';</script>";
            exit();
        }
    } else {
        $imagePath = '';  // No image uploaded, set to empty or default value
    }

    // Insert product details into database
    $stmt = $conn->prepare("INSERT INTO products (pName, pCategory, pDesc, stock, price, pImage, orgId) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiiss", $pName, $pCategory, $pDesc, $stock, $price, $imagePath, $orgId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully.'); window.location.href = 'view-product.html';</script>";
    } else {
        echo "<script>alert('Error adding product. Please try again.'); window.location.href = 'add-product.php';</script>";
    }

    $stmt->close();
}

$conn->close();
?>
