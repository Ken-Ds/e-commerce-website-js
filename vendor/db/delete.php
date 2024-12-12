<!-- AUTHORED BY: EVANGELISTA, KENDRICK -->
 
<?php
include '../db/dbconnect.php';

if (isset($_GET['id']) && isset($_GET['pid'])) {
    $product_id = $_GET['pid'];
    $orgid = $_GET['id'];

    $conn->begin_transaction();

    try {
        $deleteOrderDetailsSql = "DELETE FROM orderdetails WHERE productID = ?";
        $stmt = $conn->prepare($deleteOrderDetailsSql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        $deleteProductSql = "DELETE FROM products WHERE productID = ?";
        $stmt = $conn->prepare($deleteProductSql);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();

        $conn->commit();

        header("Location: ../pages/view-product.php?id={$orgid}&message=Product deleted successfully");
        exit();
    } catch (mysqli_sql_exception $exception) {

        $conn->rollback();
        header("Location: ../pages/view-product.php?id={$orgid}&message=Error deleting product: " . $exception->getMessage());
        exit();
    }
} else {
    header("Location: ../pages/view-product.php?id={$orgid}&message=No product ID specified");
    exit();
}
?>