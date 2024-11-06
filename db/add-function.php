<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pName = trim($_POST['pName']);
    $pCategory = $_POST['pCategory'];
    $pDesc = trim($_POST['pDesc']);
    $stock = intval($_POST['stock']);
    $price = floatval($_POST['price']);
    $imgContent='';

    // Set orgID to 1 as a default value
    $orgId = isset($_GET['id']) ? $_GET['id'] : '';

    // Validate inputs
    if (empty($pName) || empty($pCategory) || $stock < 0 || $price < 0) {
        echo "<script>alert('Please provide valid input for all fields.'); window.location.href = '../pages/add-product.php';</script>";
        exit();
    }

    // Handle image upload
    if (!empty($_FILES["pImage"]["name"])) {
        $fileName = basename ($_FILES["pImage"]["name"]);
        $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $uploadOk = true;

        // Check if image file is an actual image or fake
        $check = getimagesize($_FILES["pImage"]["tmp_name"]);
        if ($check === false) {
            echo "<script>alert('Uploaded file is not a valid image.'); window.location.href = '../pages/add-product.php';</script>";
            $uploadOk = false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ['jpg', 'png', 'jpeg'])) {
            echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.'); window.location.href = '../pages/add-product.php';</script>";
            $uploadOk = false;
        }

        $image = $_FILES["pImage"]["tmp_name"];
        $imgContent = addslashes(file_get_contents($image));
    
    } 

    // Insert product details into database
    $stmt = $conn->prepare("INSERT INTO products (pName, pCategory, pDesc, stock, price, pImage, orgId) VALUES (?, ?, ?, ?, ?, '".$imgContent."', ?)");
    $stmt->bind_param("sssiis", $pName, $pCategory, $pDesc, $stock, $price, $orgId);
    
    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully.'); window.location.href = '../pages/view-product.php';</script>";
    } else {
        echo "<script>alert('Error adding product. Please try again.'); window.location.href = '../pages/add-product.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
