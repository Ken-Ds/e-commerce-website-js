<?php

    include '../db/dbconnect.php';

    function viewProducts($sortName = '', $sortPrice = '') {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
            global $conn;

            $sql = $conn->prepare("SELECT * FROM products WHERE orgID = ?");

            if ($sortName == 'asc') {
                $sql = $conn->prepare("SELECT * FROM products WHERE orgID = ? ORDER BY pName ASC");
            } elseif ($sortName == 'desc') {
                $sql = $conn->prepare("SELECT * FROM products WHERE orgID = ? ORDER BY pName DESC");
            } elseif ($sortPrice == 'high') {
                $sql = $conn->prepare("SELECT * FROM products WHERE orgID = ? ORDER BY price DESC");
            } elseif ($sortPrice == 'low') {
                $sql = $conn->prepare("SELECT * FROM products WHERE orgID = ? ORDER BY price ASC");
            }
        
            $sql->bind_param("i", $id);
            $sql->execute();
            
            $result = $sql->get_result();
            
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
                                <button class='to-edit' type='button' onclick=\"window.location.href='edit-product.php?pid={$product['productID']}&id={$id}';\">Edit</button>
                            </div>
                        </div>
                    ";
                }
            } else {
                echo "No products found";
            }
        
    }
    
    function searchProduct($searchKey, $sortName = '', $sortPrice = '') {
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        global $conn;
        $sql = "SELECT * FROM products WHERE orgID = ? AND pName LIKE ?";
        $searchTerm = "%" . $searchKey . "%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $id, $searchTerm);
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
        
        if (isset($_GET['pid'])) {
            $product_id = $_GET['pid'];

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

    function getSalesData($userID) {
        global $conn;
    
        $query = "SELECT 
                    p.pName AS ProductName,
                    od.price AS Price,
                    SUM(od.quantity) AS Quantity,
                    (od.price * od.quantity) AS TotalAmount
                  FROM 
                    orders o
                  JOIN 
                    orderdetails od ON o.orderID = od.orderID
                  JOIN 
                    products p ON od.productID = p.productID
                  WHERE 
                    o.orgID = ?  -- Filter for store owner with orgID (userID)
                  GROUP BY
                    p.pName
                  ORDER BY 
                    o.orderID";
        
        // Prepare the query to prevent SQL injection
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $userID);  // Bind the userID to the prepared statement
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
    
        return $result;
    }

    function getOrders($orgID = null) {
        global $conn;
        
        $sql = "SELECT orders.orderID, orders.totalAmount, orders.dateOrdered, orders.dateServed,
                       orders.status, users.username, organizations.oName AS orgName
                 FROM orders
                 JOIN users ON orders.userID = users.userID
                 JOIN organizations ON orders.orgID = organizations.orgID";
        
        if ($orgID !== null) {
            $sql .= " WHERE orders.orgID = " . (int)$orgID;
        }
        
        $sql .= " ORDER BY orders.dateOrdered DESC";
        
        $result = $conn->query($sql);
        
        $orders = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        }
        
        return $orders;
    }

    function getOrderDetails($orderID) {
        global $conn; 
    
        $query = "
            SELECT 
                od.orderID,
                p.productID,
                p.pName,
                p.pImage,
                od.quantity,
                p.price AS originalPrice
            FROM 
                orderdetails od
            JOIN 
                products p ON od.productID = p.productID
            WHERE 
                od.orderID = ?
        ";
    
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $orderID);
                $stmt->execute();
                $result = $stmt->get_result();
    
            $orderDetails = [];
            while ($row = $result->fetch_assoc()) {
                $orderDetails[] = $row;
            }
    
            $stmt->close();
    
            return $orderDetails;
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    }
?>
