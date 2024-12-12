<?php
// Author: Miguel Joshua Burgos --Create the getsaledDataByOrg 

    include '../db/dbconnect.php';


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

    function getSalesDataByOrg($orgID) {
        global $conn;
        
        $query = "
            SELECT 
                orgs.oName AS OrganizationName,
                p.pName AS ProductName,
                od.price AS Price,
                SUM(od.quantity) AS Quantity,
                (od.price * SUM(od.quantity)) AS TotalAmount
            FROM 
                orders o
            JOIN 
                orderdetails od ON o.orderID = od.orderID
            JOIN 
                products p ON od.productID = p.productID
            JOIN 
                organizations orgs ON o.orgID = orgs.orgID
            WHERE 
                o.status = 'Delivered' AND o.orgID = ?  -- Filter by the user's orgID
            GROUP BY 
                orgs.oName, p.pName
            ORDER BY 
                orgs.oName, p.pName";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $orgID);  
        $stmt->execute();
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

    function returnValue($value){
        return $value;
    }
?>
