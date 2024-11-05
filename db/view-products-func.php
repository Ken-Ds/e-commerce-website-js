<?php

    include '../db/dbconnect.php';
    
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
            echo " <div class='product'>
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
                        <button class='to-edit' type='button' onclick='window.location.href='../html/edit_product.php?id=$product[productID];'>Edit</button>
                    </div>
                </div>
                ";
        }
    } else {
        echo "No products found";
    }
?>
