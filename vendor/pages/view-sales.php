<?php
include '../db/get-functions.php';
include '../includes/navbar.php';

session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../shared/pages/login.php');
    exit();
}

$userID = $_SESSION['userID'];

$query = "SELECT orgID FROM users WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $orgID = $result->fetch_assoc()['orgID'];
} else {
    die("Error: Organization ID not found for the user.");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>In Genius Orders</title>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='../style/css/view-sales.css' rel='stylesheet'>
</head>
<body>

    <div class="sales-container">
        <h1 class="sales-header">Sales</h1>
        <div class="table-wrapper">
            <table class="sales-table">
                <thead>
                    <tr> 
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch sales data using the updated function
                    $salesData = getSalesData($userID, $orgID);
                    $grandTotal = 0;

                    // Fetch and display each row of data
                    if ($salesData && $salesData->num_rows > 0) {
                        while ($row = $salesData->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                            echo "<td>₱" . htmlspecialchars(number_format($row['Price'], 2)) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                            echo "<td>₱" . htmlspecialchars(number_format($row['TotalAmount'], 2)) . "</td>";
                            echo "</tr>";

                            $grandTotal += $row['TotalAmount'];
                        }
                    } else {
                        echo "<tr><td colspan='4'>No sales data available.</td></tr>";
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total-label">Total Sales</td>
                        <td>₱<?php echo number_format($grandTotal, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

<?php include "../includes/footer.php"; ?>
</body>
</html>