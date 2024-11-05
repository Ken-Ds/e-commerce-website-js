<?php
include '../includes/header.php';
include '../db/get-functions.php';

$orders = getOrders();
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

$filteredOrders = [];

foreach ($orders as $order) {
    $orderStatus = strtolower($order['custStatus']); 
    
    if ($statusFilter === 'pending' && $orderStatus === 'pending') {
        $filteredOrders[] = $order;
    } elseif ($statusFilter === 'delivered' && $orderStatus === 'delivered') {
        $filteredOrders[] = $order;
    } elseif ($statusFilter === 'all') {
        $filteredOrders[] = $order; 
    }
}

function renderOrders($orders) {
    foreach ($orders as $order) {
        $isDelivered = strtolower($order['custStatus']) === 'delivered'; 
        echo "
        <div class='order'>
            <div class='order-summary' onclick='toggleDetails(this)'>
                <span>{$order['username']}</span>
                <span>{$order['dateOrdered']}</span>
                <span class='order-status'>{$order['custStatus']}</span>
                <span>₱{$order['totalAmount']}</span>
                <button class='mark-delivered' onclick='markAsDelivered({$order['orderID']}, event)' " . ($isDelivered ? "disabled" : "") . "> Mark as Delivered </button>
            </div>
            <div class='order-details' style='display: none;'>
                <table>
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populate with actual product details from your database -->
                        <tr>
                            <td><img src='../style/img/prod-placeholder.jpg' alt='Product'></td>
                            <td>Sample Product</td>
                            <td>2</td>
                            <td>₱100</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>";
    }
}
?>

<section class='inner-body'>
    <div class='filter-orders'>
        <button onclick="filterOrders('all')" class="<?= $statusFilter == 'all' ? 'active' : '' ?>">All</button>
        <button onclick="filterOrders('pending')" class="<?= $statusFilter == 'pending' ? 'active' : '' ?>">Pending</button>
        <button onclick="filterOrders('delivered')" class="<?= $statusFilter == 'delivered' ? 'active' : '' ?>">Delivered</button>
    </div>
    <div class='orders'>
        <?php if (count($filteredOrders) > 0) {
            renderOrders($filteredOrders);
        } else {
            echo "<p>No orders found.</p>";
        } ?>
    </div>
</section>

<script>
function toggleDetails(element) {
    const details = element.nextElementSibling; 
    details.style.display = (details.style.display === "none") ? "block" : "none";
}

function filterOrders(status) {
    window.location.href = `view-orders.php?status=${status}`;
}

function markAsDelivered(orderId, event) {
    event.stopPropagation(); 

    if (confirm('Are you sure you want to mark this order as delivered?')) {
        fetch(`update-order-status.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ orderId: orderId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order Successfully Delivered!'); 
                const button = event.target;
                button.disabled = true; 
                const orderSummary = button.closest('.order-summary');
                orderSummary.querySelector('.order-status').innerText = 'Delivered'; 
            } else {
                alert('Failed to update order status.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
</script>

<?php include '../includes/footer.php'; ?>