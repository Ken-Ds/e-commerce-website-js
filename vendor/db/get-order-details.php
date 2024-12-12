<!-- AUTHORED BY: LUMANGLAS, YENZY HYNNA -->
<?php
include 'dbconnect.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $orderID = $_POST['orderID'] ?? '';

    global $conn; 

    $query = " SELECT 
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

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $orderID);
$stmt->execute();
$result = $stmt->get_result();

$orderDetails = [];

while($row = $result->fetch_assoc()){
    if(isset($row['pImage'])){
        $row['pImage'] = base64_encode($row['pImage']);
    }
    $orderDetails[] = $row;
}

header('Content-Type: application/json');
    echo json_encode($orderDetails);
    exit();

// if ($stmt = $conn->prepare($query)) {
//     $stmt->bind_param("i", $orderID);
//         $stmt->execute();
//         $result = $stmt->get_result();

//     $orderDetails = [];
//     while ($row = $result->fetch_assoc()) {
//         $orderDetails[] = $row;
//     }

//     $stmt->close();

//      return $orderDetails;
// } else {
//     die("Error preparing statement: " . $conn->error);
// }
}
?>