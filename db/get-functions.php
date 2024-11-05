<?php

    include '../db/dbconnect.php';

    function viewProducts($sortName = '', $sortPrice = '') {
        global $conn; 
        $sql = "SELECT * FROM products";
    
        if ($sortName == 'asc') {
            $sql .= " ORDER BY pName ASC";
        } elseif ($sortName == 'desc') {
            $sql .= " ORDER BY pName DESC";
        } elseif ($sortPrice == 'high') {
            $sql .= " ORDER BY price DESC";
        } elseif ($sortPrice == 'low') {
            $sql .= " ORDER BY price ASC";
        }
    
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
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
                            <button class='delete' type='button' onclick='confirmDelete({$product['productID']}, \"" . addslashes($product['pName']) . "\")'>
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
    
    function searchProduct($searchKey, $sortName = '', $sortPrice = '') {
        global $conn;
        $sql = "SELECT * FROM products WHERE pName LIKE ?";
        $searchTerm = "%" . $searchKey . "%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
   
        if ($sortName == 'asc') {
            $sql .= " ORDER BY pName ASC";
        } elseif ($sortName == 'desc') {
            $sql .= " ORDER BY pName DESC";
        } elseif ($sortPrice == 'high') {
            $sql .= " ORDER BY price DESC";
        } elseif ($sortPrice == 'low') {
            $sql .= " ORDER BY price ASC";
        }
    
        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
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
                            <button class='delete' type='button' onclick='confirmDelete({$product['productID']}, \"" . addslashes($product['pName']) . "\")'>
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
            echo "No products found matching your search.";
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

    function deleteProduct(){
        
    }
?>
