<?php 
include '../includes/header.php';
include '../db/get-functions.php';

// Get the product details
$product = getProduct(); 

$orgID = isset($_GET['id']) ? $_GET['id'] : ''; 

if (empty($product['pImage'])) {
    $imageSrc = "../style/img/prod-placeholder.jpg";
} else {
    $imageSrc = "data:image/png;charset=utf8;base64," . base64_encode($product['pImage']); 
}

echo "
        <form class='add-product-body' action='../db/update-product.php?id=$product[productID]' method='POST' enctype='multipart/form-data'>
            <h1 style='text-align: center;'>EDIT PRODUCT FORM</h1>
            <div class='sides'>
                <div class='left-side'>
                    <div class='image'>
                        <img src='$imageSrc' alt='Upload Photo' id='product-pic'>
                        <div>
                        <label for='input-file'>Upload Image</label>
                        </div>
                        <input type='file' name='pic' accept='image/jpeg, image/png, image/jpg' id='input-file'>
                        <div class='stock-and-price'>
                            <p>Stock: <input type='text' name='stock' value='$product[stock]' placeholder='0'></p>
                            <p>Price: ₱ <input type='text' name='price' value='$product[price]' placeholder='0.00'></p>
                        </div>
                    </div>
                </div>
                <div class='right-side'>
                    <div class='detail'>
                        <div class='lft'>
                            <p>Product Name: </p>
                        </div>
                        <div class='rgt'>
                            <input type='text' name='name' value='$product[pName]' placeholder='Enter Product Name'>
                        </div>
                    </div>
                    <div class='detail'>
                        <div class='lft'>
                            <p>Category: </p>
                        </div>
                        <div class='rgt'>
                            <select name='category' id=''>
                                <option value='' disabled selected>--Choose Category--</option>
                                <option value='Food'" . ($product['pCategory']== 'Food' ? 'selected' : '') . ">Food</option>
                                <option value='Household'" . ($product['pCategory']== 'Household' ? 'selected' : '') . ">Household</option>
                                <option value='Crafts'" . ($product['pCategory']== 'Crafts' ? 'selected' : '') . ">Crafts</option>
                                <option value='Electronics'" . ($product['pCategory']== 'Electronics' ? 'selected' : '') . ">Electronics</option>
                                <option value='Other'" . ($product['pCategory']== 'Other' ? 'selected' : '') . ">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class='detail'>
                        <div class='lft'>
                            <p>Product Details: </p>
                        </div>
                        <div class='rgt'>
                            <textarea name='desc' class='detail-box' rows='5' placeholder='Add Description...'>$product[pDesc]</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <input type='hidden' name='orgID' value='$orgID'>

            <div class='add-product-buttons-section'>
                <button class='button' type='button' onclick=\"window.location.href='view-product.php?id=$orgID'\">Cancel</button>
                <button class='button' type='submit'>Edit</button>
            </div>
        </form>
    
    <script>
    let productPic = document.getElementById('product-pic');
    let inputFile = document.getElementById('input-file');

    inputFile.onchange = function(){
        productPic.src = URL.createObjectURL(inputFile.files[0]);
    }
    </script>
    ";

include '../includes/footer.php';
?>
