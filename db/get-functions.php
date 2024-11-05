<?php

    include '../db/dbconnect.php';

    function viewProducts() {
        global $conn; 
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                // Check if pImage is null or empty
                if (empty($product['pImage'])) {
                    $imageSrc = "../style/img/prod-placeholder.jpg";
                } else {
                    $imageSrc = "data:image/jpeg;base64," . base64_encode($product['pImage']); 
                }
    
                echo " 
                    <div class='product'>
                        <div class='prod-img'>
                            <img src='$imageSrc' alt='{$product['pName']}'>
                        </div>
                        <div class='name-price'>
                            <h3>{$product['pName']}</h3>
                            <p> ₱{$product['price']}</p>
                        </div>
                        <p>{$product['pDesc']}</p>
                        <div class='delete-container'>
                            <button class='delete' type='button' onclick=''>
                                <img class='delete-icon' src='../style/img/close-bin.png' alt='Delete'>
                                <img class='open-icon' src='../style/img/open-bin.png' alt='Delete'>
                            </button>
                        </div>
                        <div class='stock-edit'>
                            <p>Stock: {$product['stock']}</p> 
                            <button class='to-edit' type='button' onclick=\"window.location.href='edit-product.php?id={$product['productID']}';\">Edit</button>
                        </div>
                    </div>
                ";
            }
        } else {
            echo "No products found";
        }
    }
    

    function getProduct(){
        global $conn;
        
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            $sql = "SELECT * FROM products WHERE productID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id); 
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $product = $result->fetch_assoc();
            } else {
                echo "Product not found.";
            }
        } else {
            echo "No product ID specified.";
        }
    }

?>
