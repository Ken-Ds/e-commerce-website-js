<!-- AUTHORED BY: EVANGELISTA, KENDRICK -->

<?php
include "dbconnect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $searchKey = $_POST['searchKey'] ?? '';
    $sortName = $_POST['sortName'] ?? '';
    $sortPrice = $_POST['sortPrice'] ?? '';
    $id = isset($_POST['id']) ? $_POST['id'] : '';


    global $conn;
    
    if (empty($searchKey)) {
        $searchTerm = '%'; // If no search term, match all products
    } else {
        $searchTerm = "%" . $searchKey . "%"; // Standard search term with wildcards
    }

    // Base SQL query
    $sql = "SELECT * FROM products WHERE orgID = ? AND pName LIKE ?";

    // Modify SQL query with sort conditions
    if ($sortName == 'asc') {
        $sql .= " ORDER BY pName ASC";
    } elseif ($sortName == 'desc') {
        $sql .= " ORDER BY pName DESC";
    } elseif ($sortPrice == 'high') {
        $sql .= " ORDER BY price DESC";
    } elseif ($sortPrice == 'low') {
        $sql .= " ORDER BY price ASC";
    }

    error_log("Query: ". $sql);

    error_log("Params: id=" . $id . ", searchTerm=" . $searchTerm . ", sortName=" . $sortName . ", sortPrice=" . $sortPrice);
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $id, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Collect products
    $products = [];
    while ($row = $result->fetch_assoc()) {
        if (isset($row['pImage'])) {
            $row['pImage'] = base64_encode($row['pImage']);
        }
        $products[] = $row;
    }

    //error_log("Products: " . print_r($products, true));

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($products);
    exit();
} 
?>
