<?php

    include '../db/dbconnect.php';

    function viewProducts(){
        global $conn;
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                echo " 
                    <div class='product'>
                        <div class='prod-img'>
                            <img src='../style/img/sample-prod.jpg' alt='Product 1'>
                        </div>
                        <div class='name-price'>
                            <h3>$product[pName]</h3>
                            <p> ₱{$product['price']}</p>
                        </div>
                        <p>$product[pDesc]</p>
                        <div class='delete-container'>
                            <button class='delete' type='button' onclick=''>
                                <img class = 'delete-icon' src ='../style/img/close-bin.png' alt='Delete'>
                                <img class = 'open-icon' src = '../style/img/open-bin.png' alt='Delete'>
                            </button>
                        </div>
                        <div class='stock-edit'>
                            <p>Stock: {$product['stock']}</p> 
                            <button class='to-edit' type='button' onclick=\"window.location.href='edit-product.php?id=$product[productID]';\">Edit</button>
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
            // Get the product ID from the URL
            $product_id = $_GET['id'];

            // Now you can use $product_id to fetch product details from the database
            // Example: Fetch product details using the ID
            $sql = "SELECT * FROM products WHERE productID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id); // "i" indicates the type is integer
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if a product was found
            if ($result->num_rows > 0) {
                // Fetch the product data
                return $product = $result->fetch_assoc();
                // Now you can access product details like $product['name'], $product['stock'], etc.
            } else {
                echo "Product not found.";
            }
        } else {
            echo "No product ID specified.";
        }
    }
?>
