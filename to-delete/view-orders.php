<?php
    include '../includes/header.php';
    include '../db/get-functions.php';
    $orgid = isset($_GET['id']) ? $_GET['id'] : '';
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

            var orderDivH2 = document.createElement('h2');
            orderDivH2.class = 'order-username';
            orderDivH.innerText = order.userName; // No Order

            var orderDivP1 = document.createElement('p');
            orderDivP1.class = 'order-date';
            orderDivH.innerText = "Order Date: " + order.Date;

            var orderDivP3 = document.createElement('p');
            orderDivp3.class = 'order-total';

            var p3Strong = document.createElement('strong');
        }
    }
</script>


<?php include '../includes/footer.php'; ?>