<?php
include "../includes/header.php"
?>
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
                            <img src='../style/img/search-btn.png' alt='Search Icon'>
                        </button>
                    </div>
                </form>
                <button class='to-add' type='button' onclick = 'location.href="../pages/add-product.php"'> + </button>
            </div>

            <div class='filter-product'>
                <h2>Sort: </h2>
                <form action="" method="POST">
                    <div class='select-container'>
                        <select name='sortName' class='alphabetical' onchange="this.form.submit()">
                            <option value='' disabled selected>--Alphabetically--</option>
                            <option value='asc'>Ascending (A-Z)</option>
                            <option value='desc'>Descending (Z-A)</option>
                        </select>
                    </div>
                
                    <div class='select-container'>
                        <select name='sortPrice' class='price' onchange="this.form.submit()">
                            <option value='' disabled selected>--Prices--</option>
                            <option value='high'>Highest to Lowest</option>
                            <option value='low'>Lowest to Highest</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class='products'>
                <?php
                include '../db/get-functions.php';
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $searchKey = $_POST['searchKey'] ?? '';
                    $sortName = $_POST['sortName'] ?? '';
                    $sortPrice = $_POST['sortPrice'] ?? '';
                
                    if (!empty($searchKey)) {
                        searchProduct($searchKey, $sortName, $sortPrice);
                    } else {
                        viewProducts($sortName, $sortPrice);
                    }
                } else {
                    viewProducts();
                }
                ?>
            </div>
        </section>

        <script>
            function confirmDelete(productId, productName) {
                if (confirm("Are you sure you want to delete the product: " + productName + "?")) {
                    window.location.href = '../db/delete.php?id=' + productId;
                }
            }
        </script>

        
<?php include "../includes/footer.php"; ?>