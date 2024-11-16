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
        
    </div>

</section>


<script>
    document.addEventListener('DOMContentLoaded', function( ){
        fetchOrders();
    });

    function fetchOrders(){
        var orderContainer = document.querySelector('.orders');
        orderContainer.innerHTML = '';
        // var order
        if (data.length === 0) {            

            var nOrderImagElement = document.createElement('img');
            nOrderImagElement.src = '../style/img/noOrder.png';
            nOrderImagElement.alt = 'No Orders';
            nOrderImagElement.class = 'no-orders-image';

            var nOrderP = document.createElement('p');
            nOrderP.innerText = "No Orders Found!";

            orderContainer.appendChild(nOrderImagElement);
            orderContainer.appendChild(nOrderP);
        } else {
            var orderDiv = document.createElement('div');
            orderDiv.class = 'order-summary';
            orderDiv.onclick = 'toggleDetails(this)';

            //Start Order Dive

            var orderDivH2 = document.createElement('h2');
            orderDivH2.class = 'order-username';
            orderDivH.innerText = order.userName; // No Order

            var orderDivP1 = document.createElement('p');
            orderDivP1.class = 'order-date';
            orderDivH.innerText = "Order Date: " + order.Date;  // No Order

            var orderDivP2 = document.createElement('p');
            orderDivP2.class = 'order-status';
            
            var orderDivP2EM = document.createElement('em')
            orderDivP2EM.innerText =  + order.status;  // No Order

            var orderDivP3 = document.createElement('p');
            orderDivp3.class = 'order-total';

            var p3Strong = document.createElement('strong');
            p3Strong.innerText = "Total Amount: ₱" + order.totalAmount; // No Order

            var markDeliveredButton = document.createElement('button');
            markDeliveredButton.class = 'order-total';
            markDeliveredButton.onclick = function(event) { markAsDelivered(order.ID, event); };
            markDeliveredButton.innerText = "Mark as Delivered";
            if(order.status === 'delivered'){
                markDeliveredButton.disabled = "true";
            }

            var collapseExpandButton = document.createElement('button');
            collapseExpandButton.class = 'collapse-epand-btn'

            var collapseImage = document.createElement('img');
            collapseImage.src = '../style/img/expand.png'
            collapseImage.alt = 'Expand'
            
            //End Order Dive

            //Start Order Prodcuts Div

            var orderProductsTableDiv = document.createElement('div');
            orderProductsTableDiv.class = 'order-products-table';
            orderProductsTableDiv.style='display: none;'

            var table = document.createElement('table');

            var thread = document.createElement('thead');

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

            var tbody = document.createElement('tbody');

            var tr2 = document.createElement('tr');
            if(product.length === 0 ){
                var td = document.createElement('td');
                td.colspan='4';
                td.innerText = "No products found for this order"
            } else {
                

                var tha1 = document.createElement('th');

                var thA1Image = document.createElement('img');
                
                var tha2 = document.createElement('th');
                th2.innerText = product.name; // No Product
                var tha3 = document.createElement('th');
                th3.innerText = product.quantity; // No Product
                var tha4 = document.createElement('th');
                th4.innerText = product.price; // No Product
                var tha5 = document.createElement('th');
                th5.innerText = product.amount; // No Product
            }
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