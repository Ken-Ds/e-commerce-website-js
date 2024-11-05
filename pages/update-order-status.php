<?php
include '../db/get-functions.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);
    $orderId = $data['orderId'];

    $result = markOrderAsDelivered($orderId); 

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update order status.']);
    }
    exit;
}

function markOrderAsDelivered($orderId) {
$conn = new mysqli("localhost", "root", "", "ings");

    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        return false; 
    }

    $stmt = $conn->prepare("UPDATE orders SET custStatus = ? WHERE orderID = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error); 
        return false;
    }

    $status = 'Delivered'; // Set the new status
    $stmt->bind_param('si', $status, $orderId); 

    if ($stmt->execute()) {
        $stmt->close(); 
        $conn->close(); 
        return true; 
    } else {
        error_log("Execute failed: " . $stmt->error); 
        $stmt->close(); 
        $conn->close();
        return false; 
    }
}
?>