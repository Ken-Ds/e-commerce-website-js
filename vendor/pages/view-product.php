<?php
    include '../includes/navbar.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>In Genius Orders</title>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='../style/css/view-products.css' rel='stylesheet'>
</head>
<body>
        <section class = 'event-title'>
            <h1><span>Bravo!</span> Teknokomersiyo</h1>
            <h3>
                Featuring booths where students can display and 
                sell their unique creations, fostering community engagement 
                and supporting local artisans.  
            </h3>
            <div class='overlay'></div>
        </section>

        <section class='inner-body'>
            <div class='search-product'>
                <h1>Your Products</h1>
                <form class='search-container' method="POST">
                    <div class='search-inner'>
                        <input type='text' name ='searchKey' class = 'search-bar' placeholder='Search a Product'> 
                        <button type="submit" class="search">
                            <img src='../../shared/style/img/search-btn.png' alt='Search Icon'>
                        </button>
                    </div>
                </form>
                <?php
                    $id = isset($_GET['id']) ? $_GET['id'] : '';
                ?>
                <button class='to-add' type='button' onclick = 'location.href="add-product.php?id=<?php echo htmlspecialchars($id);?>"'> + </button>
            </div>

            <div class='filter-product'>
                <h2>Sort: </h2>
                <form class='select-form' action="" method="POST">
                    <div class='select-container'>
                        <select name='sortName' class='alphabetical'>
                            <option value='' disabled selected>--Alphabetically--</option>
                            <option value='asc'>Ascending (A-Z)</option>
                            <option value='desc'>Descending (Z-A)</option>
                        </select>
                    </div>
                
                    <div class='select-container'>
                        <select name='sortPrice' class='price'>
                            <option value='' disabled selected>--Prices--</option>
                            <option value='high'>Highest to Lowest</option>
                            <option value='low'>Lowest to Highest</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class='products'>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                fetchProducts();
            });

            function confirmDelete(productId, productName) {
                if (confirm("Are you sure you want to delete the product: " + productName + "?")) {
                    var urlParams = new URLSearchParams(window.location.search);
                    var orgId = urlParams.get('id'); 
                    window.location.href = '../db/delete.php?pid=' + productId + '&id='+ orgId;
                }
            }
            
            function fetchProducts(searchKey = '', sortName = '', sortPrice = '') {
                var formData = new FormData();
                formData.append('searchKey', searchKey);
                formData.append('sortName', sortName);
                formData.append('sortPrice', sortPrice);
                formData.append('id', <?php echo $id ?>);

                fetch('../db/view-and-filter.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    var productContainer = document.querySelector('.products');
                    productContainer.innerHTML = '';  
                    
                    if (data.length === 0) {
                        productContainer.innerHTML = 'No products found!';
                    } else {
                        data.forEach(product => {
                            var img = product.pImage ? 'data:image/jpeg;base64,' + product.pImage : "../style/img/prod-placeholder.jpg";

                            var productDiv = document.createElement('div');
                            productDiv.className = 'product';

                            var imgDiv = document.createElement('div');
                            imgDiv.className = 'prod-img';
                            var imgElement = document.createElement('img');
                            imgElement.src = img;
                            imgElement.alt = product.pName;

                            var namePriceDiv = document.createElement('div');
                            namePriceDiv.className = 'name-price';
                            var name = document.createElement('h3');
                            name.innerText = product.pName;
                            var price = document.createElement('p');
                            price.innerText = "₱" + product.price;

                            var desc = document.createElement('p');
                            desc.innerText = product.pDesc;

                       
                            var deleteDiv = document.createElement('div');
                            deleteDiv.className = 'delete-container';
                            var deleteBtn = document.createElement('button');
                            deleteBtn.className = 'delete';
                            deleteBtn.type = 'button';
                            var openDelElement = document.createElement('img');
                            var closeDelElement = document.createElement('img');
                            openDelElement.className='open-icon';
                            openDelElement.src = '../style/img/open-bin.png';
                            openDelElement.alt = 'Delete';
                            closeDelElement.className='delete-icon';
                            closeDelElement.src = '../style/img/close-bin.png';
                            closeDelElement.alt = 'Delete';
                            deleteBtn.onclick=function() {
                                confirmDelete(product.productID, product.pName);
                            };

                            var stockEditDiv = document.createElement('div');
                            stockEditDiv.className = "stock-edit";
                            var stock = document.createElement('p');
                            stock.innerText = "Stock: " + product.stock;
                            var toEditBtn = document.createElement('button');
                            toEditBtn.className = "to-edit";
                            toEditBtn.type = "button";
                            toEditBtn.innerText = 'Edit';
                            toEditBtn.onclick=function(){
                                window.location.href="edit-product.php?pid=" + product.productID + "&id=" + <?php echo $id?>;
                            };

                            imgDiv.appendChild(imgElement);
                            namePriceDiv.appendChild(name);
                            namePriceDiv.appendChild(price);
                            deleteDiv.appendChild(deleteBtn);
                            deleteBtn.appendChild(closeDelElement);
                            deleteBtn.appendChild(openDelElement);
                            stockEditDiv.appendChild(stock);
                            stockEditDiv.appendChild(toEditBtn);

                            productDiv.appendChild(imgDiv);
                            productDiv.appendChild(namePriceDiv);
                            productDiv.appendChild(desc);
                            productDiv.appendChild(deleteDiv);
                            productDiv.appendChild(stockEditDiv);

                            productContainer.appendChild(productDiv);

                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            document.querySelector('.search-container').addEventListener('submit', function(event) {
                event.preventDefault(); 
                var searchKey = document.querySelector('.search-bar').value;
                var sortName = document.querySelector('.alphabetical').value;
                var sortPrice = document.querySelector('.price').value;
                fetchProducts(searchKey, sortName, sortPrice); 
            });

            document.querySelector('.select-form').addEventListener('change', function(event) {
                event.preventDefault(); 
                var searchKey = document.querySelector('.search-bar').value;
                var sortName = document.querySelector('.alphabetical').value;
                var sortPrice = document.querySelector('.price').value;
                fetchProducts(searchKey, sortName, sortPrice); 
                document.querySelector('.select-form').reset();
            });

        </script>

<?php include '../includes/footer.php'; ?>