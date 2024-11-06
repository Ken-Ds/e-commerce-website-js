<?php
// Include the functions.php file to access the getSalesData() function
include '../db/get-functions.php';
include '../includes/header.php';
?>
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
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                    $salesData = getSalesData($id); // Assuming 1 is the userID for the store owner
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

<?php include "../includes/footer.php"?>
