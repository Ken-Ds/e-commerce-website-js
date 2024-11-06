<?php
include '../includes/header.php';
include '../db/get-functions.php';

$orgid = isset($_GET['id']) ? $_GET['id'] : '';
$orders = getOrders($orgid);
$statusFilter = isset($_GET['status']) ? htmlspecialchars($_GET['status']) : 'all'; 

$filteredOrders = [];

foreach ($orders as $order) {
    $orderStatus = strtolower($order['status']); 
    
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
        $isDelivered = strtolower($order['status']) === 'delivered'; 
        
        $orderDetails = getOrderDetails($order['orderID']); 

        $collapseImage = "<img src='../style/img/expand.png' alt='Expand' class='toggle-image'>"; 

        echo "
        <div class='order'>
            <div class='order-summary' onclick='toggleDetails(this)'>
                <h2>{$order['username']}</h2>
                <p>Order Date {$order['dateOrdered']}</p>
                <p class='order-status'><em>{$order['status']}</em></p>
                
                <p><strong>Total Amount: ₱{$order['totalAmount']}</strong></p>
                <button class='mark-delivered' onclick='markAsDelivered({$order['orderID']}, event)' " . ($isDelivered ? "disabled" : "") . "> Mark as Delivered </button>
                
                <!-- Collapse/Expand Button -->
                <button class='collapse-expand-btn'>
                    {$collapseImage}
                </button>
            </div>

            <div class='order-products-table' style='display: none;'>
                <table>
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>";

        if (!empty($orderDetails)) {
            foreach ($orderDetails as $product) {
                if (empty($product['pImage'])) {
                    $imageSrc = "../style/img/prod-placeholder.jpg";
                } else {
                    if (is_string($product['pImage']) && file_exists($product['pImage'])) {
                        $imageSrc = "../style/img/{$product['pImage']}";
                    } else {
                        $imageSrc = "data:image/jpeg;base64," . base64_encode($product['pImage']);
                    }
                }

                $productName = $product['pName'];  
                $quantity = $product['quantity'];  
                $price = $product['price'];        
                $totalAmount = $quantity * $price; 

                echo "
                <tr>
                    <td><img src='$imageSrc' alt='Product Image' class='product-image'></td>
                    <td>{$productName}</td>
                    <td>{$quantity}</td>
                    <td>₱{$totalAmount}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No products found for this order.</td></tr>";
        }

        echo "
                    </tbody>
                </table>
            </div>
        </div>";
    }
}
?>

<section class='inner-body'>
<div class='orders-container'>
         <h2>Orders</h2>
         </div>
    <div class='filter-orders'>
        <button onclick="filterOrders('all')" class="<?= $statusFilter == 'all' ? 'active' : '' ?>">All</button>
        <button onclick="filterOrders('pending')" class="<?= $statusFilter == 'pending' ? 'active' : '' ?>">Pending</button>
        <button onclick="filterOrders('delivered')" class="<?= $statusFilter == 'delivered' ? 'active' : '' ?>">Delivered</button>
    </div>
    <div class='orders'>
        <?php if (count($filteredOrders) > 0) {
            renderOrders($filteredOrders);
        } else {
            echo "<img src='../style/img/noOrder.png' alt='No Orders' class = 'no-order'>";
        } ?>
    </div>
</section>

<script>
function toggleDetails(element) {
    const details = element.nextElementSibling; 
    details.style.display = (details.style.display === "none") ? "block" : "none";
}

function filterOrders(status) {
    window.location.href = `view-orders.php?status=${status}&id=<?php echo $orgid ?>`;
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
        };
    }


function toggleDetails(button) {
    var orderDetails = button.closest('.order').querySelector('.order-products-table');
    var buttonImage = button.querySelector('.toggle-image');
    
    if (orderDetails.style.display === 'none' || orderDetails.style.display === '') {
        orderDetails.style.display = 'block'; 
        buttonImage.src = '../style/img/collapse.jpg'; 
        buttonImage.alt = 'Collapse'; 
        orderContainer.style.backgroundColor = "#81acc2"; 
    } else {
        orderDetails.style.display = 'none'; 
        buttonImage.src = '../style/img/expand.png';
        buttonImage.alt = 'Expand'; 
        orderContainer.style.backgroundColor = "#f9f9f9";    }
}
</script>

<?php include '../includes/footer.php'; ?>