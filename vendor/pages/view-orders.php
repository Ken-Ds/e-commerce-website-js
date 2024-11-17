<?php
    include '../includes/navbar.php';
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>In Genius Orders</title>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='../style/css/view-orders.css' rel='stylesheet'>
</head>
<body>

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
        
    </div>

</section>


<script>
    document.addEventListener('DOMContentLoaded', function( ){
        fetchOrders(<?php echo $orgid?>);
    });

    function fetchOrders(orgID = ''){
        var orders = JSON.parse('<?=json_encode($filteredOrders);?>');
        var ordersContainer = document.querySelector('.orders');
        ordersContainer.innerHTML = '';
        // var order
        if (orders.length === 0) {            
            var nOrderDiv = document.createElement('div');
            nOrderDiv.className = 'no-orders-message';

            var nOrderImagElement = document.createElement('img');
            nOrderImagElement.src = '../style/img/noOrder.png';
            nOrderImagElement.alt = 'No Orders';
            nOrderImagElement.className = 'no-orders-image';

            var nOrderP = document.createElement('p');
            nOrderP.innerText = "No Orders Found!";

            nOrderDiv.appendChild(nOrderImagElement);
            nOrderDiv.appendChild(nOrderP);

            orderContainer.appendChild(nOrderDiv);
        } else {
            orders.forEach(order => {
            
            var orderDiv = document.createElement('div');
            orderDiv.className = 'order';

            //Start Order Div
            var orderSumDiv = document.createElement('div');
            orderSumDiv.className = 'order-summary';
            orderSumDiv.onclick = function() {
                toggleDetails(this);
            };

            var orderSumDivH2 = document.createElement('h2');
            orderSumDivH2.className = 'order-username';
            orderSumDivH2.innerText = order.username;

            var orderSumDivP1 = document.createElement('p');
            orderSumDivP1.className = 'order-date';
            orderSumDivP1.innerText = "Order Date: " + order.dateOrdered;

            var orderSumDivP2 = document.createElement('p');
            orderSumDivP2.className = 'order-status';
            
            var orderSumDivP2EM = document.createElement('em')
            orderSumDivP2EM.innerText =  order.status;

            var orderSumDivP3 = document.createElement('p');
            orderSumDivP3.className = 'order-total';

            var p3Strong = document.createElement('strong');
            p3Strong.innerText = "Total Amount: ₱" + order.totalAmount;

            var stat = order.status.toLowerCase();
            var isDelivered = (stat === 'delivered');

            var markDeliveredButton = document.createElement('button');
            markDeliveredButton.className = 'mark-delivered';
            markDeliveredButton.onclick = function(event) {
                 markAsDelivered(order.ID, event); 
            };
            markDeliveredButton.innerText = "Mark as Delivered";
            if(isDelivered){
                markDeliveredButton.disabled = "true";
            }

            var collapseExpandButton = document.createElement('button');
            collapseExpandButton.className = 'collapse-expand-btn';

            var collapseImage = document.createElement('img');
            collapseImage.src = '../style/img/expand.png';
            collapseImage.alt = 'Expand';
            collapseImage.className = 'toggle-image';
            
            //End Order Div

            orderSumDiv.appendChild(orderSumDivH2);
            orderSumDiv.appendChild(orderSumDivP1);

            orderSumDivP2.appendChild(orderSumDivP2EM);
            orderSumDiv.appendChild(orderSumDivP2);

            orderSumDivP3.appendChild(p3Strong);
            orderSumDiv.appendChild(orderSumDivP3);

            orderSumDiv.appendChild(markDeliveredButton);

            collapseExpandButton.appendChild(collapseImage);
            orderSumDiv.appendChild(collapseExpandButton);

            orderDiv.appendChild(orderSumDiv);

            //Start Order Prodcuts Div

            var orderProductsTableDiv = document.createElement('div');
            orderProductsTableDiv.className = 'order-products-table';
            orderProductsTableDiv.style='display: none';

            var table = document.createElement('table');

            var thead = document.createElement('thead');

            var tr = document.createElement('tr');

            var th1 = document.createElement('th');
            th1.innerText = "Product Image";
            var th2 = document.createElement('th');
            th2.innerText = "Product Name";
            var th3 = document.createElement('th');
            th3.innerText = "Quantity";
            var th4 = document.createElement('th');
            th4.innerText = "Original Price";
            var th5 = document.createElement('th');
            th5.innerText = "Amount";

            tr.appendChild(th1);
            tr.appendChild(th2);
            tr.appendChild(th3);
            tr.appendChild(th4);
            tr.appendChild(th5);

            thead.appendChild(tr);

            var tbody = document.createElement('tbody');

            var formData = new FormData();
            formData.append('orderID', order.orderID);

            fetch('../db/get-order-details.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(products => {
                if(products.length === 0 ){
                    var tr2 = document.createElement('tr');
                    var td = document.createElement('td');
                    td.colspan='4';
                    td.innerText = "No products found for this order"

                    tr2.appendChild(td);
                } else {
                    products.forEach(product => {
                        var tr2 = document.createElement('tr');
                        var td1 = document.createElement('th');

                        var prodImg = product.pImage ? 'data:image/jpeg;base64,' + product.pImage : "../style/img/prod-placeholder.jpg";

                        var td1Image = document.createElement('img');
                        td1Image.src = prodImg;
                        td1Image.alt = product.pName;
                        td1Image.className = 'product-image';

                        var td2 = document.createElement('th');
                        td2.innerText = product.pName;
                        var td3 = document.createElement('th');
                        td3.innerText = product.quantity;
                        var td4 = document.createElement('th');
                        td4.innerText = "₱" + product.originalPrice;
                        var td5 = document.createElement('th');
                        td5.innerText = "₱" + (product.quantity * product.originalPrice).toFixed(2);

                        td1.appendChild(td1Image);

                        tr2.appendChild(td1);
                        tr2.appendChild(td2);
                        tr2.appendChild(td3);
                        tr2.appendChild(td4);
                        tr2.appendChild(td5);

                        tbody.appendChild(tr2);
                    });
                }
            })
            .catch(error => console.error('Error:', error));
            table.appendChild(thead);
            table.appendChild(tbody);

            orderProductsTableDiv.appendChild(table);
            orderDiv.append(orderProductsTableDiv);
            ordersContainer.appendChild(orderDiv);
            });
        }
    }
    

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
        var orderContainer = button.closest('.order'); 
        
        if (orderDetails.style.display === 'none' || orderDetails.style.display === '') {
            orderDetails.style.display = 'block'; 
            buttonImage.src = '../style/img/collapse.jpg'; 
            buttonImage.alt = 'Collapse'; 
            orderContainer.style.backgroundColor = "#81acc2";
        } else {
            orderDetails.style.display = 'none'; 
            buttonImage.src = '../style/img/expand.png'; 
            buttonImage.alt = 'Expand'; 
            orderContainer.style.backgroundColor = "#f9f9f9"; 
        }
    }
</script>


<?php include '../includes/footer.php'; ?>