<?php

include '../includes/header.php';
include '../db/get-functions.php';

echo "
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
                <div class='search-container'>
                    <div class='search-inner'>
                        <input type='text' class = 'search-bar' placeholder='Search a Product'> 
                        <img src='../style/img/search-btn.png' alt='Search Icon'>
                    </div>
                </div>
                <button class='to-add' type='button' onclick = 'location.href='add-product.html';'> + </button>
            </div>

            <div class='filter-product'>
                <h2>Sort: </h2>

                <div class='select-container'>
                    <select class='alphabetical'>
                        <option value='' disabled selected>--Alphabetically--</option>
                        <option>Ascending (A-Z)</option>
                        <option>Descending (Z-A)</option>
                    </select>
                </div>
            
                <div class='select-container'>
                    <select class='price'>
                        <option value='' disabled selected>--Prices--</option>
                        <option>Highest to Lowest</option>
                        <option>Lowest to Highest</option>
                    </select>
                </div>
            </div>
            <div class='products'>";
                viewProducts();
echo "
            </div>
        </section>
    ";

include '../includes/footer.php';
?>