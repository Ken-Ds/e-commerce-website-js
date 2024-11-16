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
?>

<form class='add-product-body' action='../db/edit.php?pid=<?php echo $product['productID']?>' method='POST' enctype='multipart/form-data' onsubmit="return validateForm()">
    <h1 style='text-align: center;'>EDIT PRODUCT FORM</h1>
    <div class='sides'>
        <div class='left-side'>
            <div class='image'>
                <img src='<?php echo $imageSrc ?>' alt='Upload Photo' id='product-pic'>
                <div>
                    <label for='input-file'>Upload Image</label>
                </div>
                <input type='file' name='pic' accept='image/jpeg, image/png, image/jpg' id='input-file'>
                <div class='stock-and-price'>
                    <p>Stock: <input type='text' name='stock' id='stock' value='<?php echo $product['stock'] ?>' placeholder='0'></p>
                    <p>Price: ₱ <input type='text' name='price' id='price' value='<?php echo $product['price']?>' placeholder='0.00'></p>
                </div>
            </div>
        </div>
        <div class='right-side'>
            <div class='detail'>
                <div class='lft'>
                    <p>Product Name: </p>
                </div>
                <div class='rgt'>
                    <input type='text' name='name' id='name' value='<?php echo $product['pName']?>' placeholder='Enter Product Name'>
                </div>
            </div>
            <div class='detail'>
                <div class='lft'>
                    <p>Category: </p>
                </div>
                <div class='rgt'>
                    <select name='category' id='category'>
                        <option value='' disabled selected>--Choose Category--</option>
                        <option value='Food' <?php echo ($product['pCategory']== 'Food' ? 'selected' : '') ?>>Food</option>
                        <option value='Household' <?php echo ($product['pCategory']== 'Household' ? 'selected' : '') ?>>Household</option>
                        <option value='Crafts' <?php echo ($product['pCategory']== 'Crafts' ? 'selected' : '') ?>>Crafts</option>
                        <option value='Electronics' <?php echo ($product['pCategory']== 'Electronics' ? 'selected' : '') ?>>Electronics</option>
                        <option value='Other' <?php echo ($product['pCategory']== 'Other' ? 'selected' : '') ?>>Other</option>
                    </select>
                </div>
            </div>
            <div class='detail'>
                <div class='lft'>
                    <p>Product Details: </p>
                </div>
                <div class='rgt'>
                    <textarea name='desc' id='desc' class='detail-box' rows='5' placeholder='Add Description...'><?php echo $product['pDesc'] ?></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <input type='hidden' name='orgID' value='<?php echo $orgID ?>'>

    <div class='add-product-buttons-section'>
        <button class='button' type='button' onclick="window.location.href='view-product.php?id=<?php echo $orgID?>'">Cancel</button>
        <button class='button' type='submit'>Edit</button>
    </div>
</form>

<script>
function validateForm() {
    const name = document.getElementById('name').value.trim();
    const desc = document.getElementById('desc').value.trim();
    const category = document.getElementById('category').value;
    const stock = parseInt(document.getElementById('stock').value.trim());
    const price = parseFloat(document.getElementById('price').value.trim());

    if (!name || !desc || !category || isNaN(stock) || isNaN(price)) {
        alert('Please fill out all fields.');
        return false;
    }

    if (stock <= 0) {
        alert('Stock must be greater than 0.');
        return false;
    }

    if (price <= 0) {
        alert('Price must be greater than 0.');
        return false;
    }

    return true;
}

let productPic = document.getElementById('product-pic');
let inputFile = document.getElementById('input-file');

inputFile.onchange = function() {
    productPic.src = URL.createObjectURL(inputFile.files[0]);
};
</script>

<?php include '../includes/footer.php'; ?>
