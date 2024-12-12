<?php
include 'dbconnect.php';

if (isset($_GET['pid'])) {
    $orgID = isset($_POST['orgID']) ? $_POST['orgID'] : '';  
    $prodid = $_GET['pid'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $desc = $_POST['desc'];
    

    $stmt = $conn->prepare("UPDATE products SET stock=?, price=?, pName=?, pCategory=?, pdesc=? WHERE productID=?");
    $stmt->bind_param("idsssi", $stock, $price, $name, $category, $desc, $prodid);

    if ($stmt->execute()) {
        if(!empty($_FILES['pic']['name'])){
            $fileName = basename ($_FILES['pic']['name']);
            $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowTypes = array('jpg', 'png', 'jpeg');
            if(in_array($fileType, $allowTypes)){
                $image = $_FILES['pic']['tmp_name'];
                $imgContent = addslashes(file_get_contents($image));
            }

            $update = $conn->prepare("UPDATE products SET pImage='".$imgContent."' WHERE productID=$prodid");

            if($update->execute()){
                echo "<script>alert('Product Edit Successful!'); window.location.href='../pages/view-product.php?id={$orgID}'</script>";
            } else {
                echo "<script>alert('File Upload Failed. Try Again!');</script>";
            }
            
        }
        echo "<script>alert('Product Edit Successful!'); window.location.href='../pages/view-product.php?id={$orgID}'</script>";
    } else {
        echo "<script>alert('Failed to edit product details. Try Again!');</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No product ID provided.";
}
?>
