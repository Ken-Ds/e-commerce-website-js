<?php
// Include the functions.php file to access the getSalesData() function
include '../db/get-functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../style/style.css" rel="stylesheet">
    <title>Sales</title>
</head>
<body class="sales-body">

    <header class="header">
        <nav>
            <a href="view-product.html"><img src="../style/img/logo.png" alt="Logo of INGS"></a>
            <h1>In Genius Orders</h1>
            <div class="navbar" id="navbar">
                <ul>
                    <li><a href="view-product.html">Products</a></li>
                    <li><a href="#">Orders</a></li>
                    <li><a href="view-sales.php">Sales</a></li>
                    <li><a href="view-profile.html">Profile</a></li>
                </ul>
            </div>
        </nav>
    </header>

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
                    // Get sales data using the method
                    $salesData = getSalesData(1); // Assuming 1 is the userID for the store owner
                    $grandTotal = 0;

                    // Fetch and display each row of data
                    if ($salesData && $salesData->num_rows > 0) {
                        while ($row = $salesData->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                            echo "<td>$" . htmlspecialchars(number_format($row['Price'], 2)) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                            echo "<td>$" . htmlspecialchars(number_format($row['TotalAmount'], 2)) . "</td>";
                            echo "</tr>";

                            $grandTotal += $row['TotalAmount'];
                        }
                    } else {
                        echo "<tr><td colspan='4'>No sales data available.</td></tr>";
                    }

                    // Close the connection
                    $conn->close();
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total-label">Total Sales</td>
                        <td>$<?php echo number_format($grandTotal, 2); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</body>
</html>
