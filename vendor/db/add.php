<!-- AUTHORED BY: MODELO, ALEXX EVAN -->
 
<?php
session_start();
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize text inputs to prevent HTML injection
    $pName = htmlspecialchars(trim($_POST['pName']), ENT_QUOTES, 'UTF-8');
    $pCategory = htmlspecialchars(trim($_POST['pCategory']), ENT_QUOTES, 'UTF-8');
    $pDesc = htmlspecialchars(trim($_POST['pDesc']), ENT_QUOTES, 'UTF-8');
    
    // Sanitize numeric inputs
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);

    // Validate inputs
    if (empty($pName) || empty($pCategory) || empty($pDesc) || !$stock || !$price) {
        echo "<script>alert('Please provide valid input for all fields.'); window.history.back();</script>";
        exit();
    }

    $userID = $_SESSION['userID'] ?? null;
    if (!$userID) {
        echo "<script>alert('User not logged in.'); window.location.href = '../pages/login.php';</script>";
        exit();
    }

    $sqlFetchOrgID = "SELECT orgID FROM users WHERE userID = ?";
    $stmtFetchOrgID = $conn->prepare($sqlFetchOrgID);
    $stmtFetchOrgID->bind_param("i", $userID);
    $stmtFetchOrgID->execute();
    $stmtFetchOrgID->bind_result($orgId);
    $stmtFetchOrgID->fetch();
    $stmtFetchOrgID->close();

    if (!$orgId) {
        echo "<script>alert('User organization not found.'); window.location.href = '../pages/add-product.php';</script>";
        exit();
    }

    $imgContent = null;

    if (!empty($_FILES["pImage"]["name"])) {
        $fileName = basename($_FILES["pImage"]["name"]);
        $imageFileType = pathinfo($fileName, PATHINFO_EXTENSION);
        $check = getimagesize($_FILES["pImage"]["tmp_name"]);

        if ($check === false) {
            echo "<script>alert('Uploaded file is not a valid image.'); window.history.back();</script>";
            exit();
        }

        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            echo "<script>alert('Only JPG, JPEG, and PNG files are allowed.'); window.history.back();</script>";
            exit();
        }

        $image = $_FILES["pImage"]["tmp_name"];
        $imgContent = file_get_contents($image);
    }

    $stmt = $conn->prepare("INSERT INTO products (pName, pCategory, pDesc, stock, price, pImage, orgID) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiisi", $pName, $pCategory, $pDesc, $stock, $price, $imgContent, $orgId);

    if ($stmt->execute()) {
        echo "<script>alert('Product added successfully.'); window.location.href = '../pages/view-product.php?id={$orgId}';</script>";
    } else {
        echo "<script>alert('Error adding product. Please try again.'); window.history.back();</script>";
    }

    $stmt->close();
}
$conn->close();
?>
