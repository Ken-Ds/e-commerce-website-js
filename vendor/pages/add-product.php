<?php
session_start();

if (!isset($_SESSION['userID'])) {
    header('Location: ../pages/login.php');
    exit();
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");

include '../includes/navbar.php'; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>In Genius Orders</title>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='../style/css/add-edit.css' rel='stylesheet'>
</head>
<body>

<script>
    function validateForm(event) {
        event.preventDefault();
        
        const pName = document.querySelector('input[name="pName"]').value;
        const pCategory = document.querySelector('select[name="pCategory"]').value;
        const pDesc = document.querySelector('textarea[name="pDesc"]').value;
        const stock = document.querySelector('input[name="stock"]').value;
        const price = document.querySelector('input[name="price"]').value;
        const pImage = document.getElementById('pImageInput').files[0];

        if (pImage) {
            const imageFileType = pImage.type.split('/')[1].toLowerCase();
            if (!['jpg', 'jpeg', 'png'].includes(imageFileType)) {
            alert('Only JPG, JPEG, and PNG files are allowed.');
            return;
            }
        } else {
        alert('Please select an image file.');
        return;
        }
        
        if (!stock || isNaN(stock) || stock <= 0) {
            alert('Please enter a valid stock number.');
            return;
        }

        if (!price || isNaN(price) || price <= 0) {
            alert('Please enter a valid price.');
            return;
        }

        if (!pName.trim()) {
            alert('Please enter a valid product name.');
            return;
        }

        if (!pCategory) {
            alert('Please select a product category.');
            return;
        }

        if (!pDesc.trim()) {
            alert('Please enter a valid product description.');
            return;
        }

        document.getElementById("productForm").submit();
    }

    function previewImage(event) {
        const imageInput = event.target;
        const file = imageInput.files[0];
        const preview = document.getElementById('imagePreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
            document.getElementById('removeButton').style.display = 'block';
        }
    }

    function removeImage() {
        const preview = document.getElementById('imagePreview');
        const imageInput = document.getElementById('pImageInput');
        
        preview.src = "../style/img/upload-img.png";
        imageInput.value = "";
        document.getElementById('removeButton').style.display = 'none';
    }

    function handleCancel() {
        var urlParams = new URLSearchParams(window.location.search);
        var orgId = urlParams.get('id');

        if (orgId) {
            window.location.href = 'view-product.php?id=' + orgId;
        } else {
            window.location.href = '../db/add.php';
        }
    }

</script>

<form id="productForm" class="add-product-body" action="../db/add.php" method="post" enctype="multipart/form-data">
    <h1 style="text-align: center;">ADD PRODUCT FORM</h1>
    <div class="sides">
        <div class="left-side">
            <div class="image">
                <img id="imagePreview" 
                     src="../style/img/upload-img.png" 
                     alt="Upload Photo" style="cursor: pointer;" onclick="document.getElementById('pImageInput').click()">
                <input type="file" id="pImageInput" name="pImage" accept="image/*" style="display: none;" onchange="previewImage(event)">
                <button class="removeButton" type="button" id="removeButton" style="display: none; margin-top: 10px;" onclick="removeImage()">Remove Photo</button>
                
                <div class="stock-and-price">
                    <p>Stock: <input type="text" name="stock" placeholder="0" required></p>
                    <p>Price: ₱ <input type="text" name="price" placeholder="0.00" required></p>
                </div>
            </div>
        </div>
        <div class="right-side">
            <div class="detail">
                <div class="lft">
                    <p>Product Name: </p>
                </div>
                <div class="rgt">
                    <input type="text" name="pName" placeholder="Enter Product Name" required>
                </div>
            </div>
            <div class="detail">
                <div class="lft">
                    <p>Category: </p>
                </div>
                <div class="rgt">
                    <select name="pCategory" required>
                        <option value="" disabled selected>--Choose Category--</option>
                        <option>Food</option>
                        <option>Household</option>
                        <option>Crafts</option>
                        <option>Electronics</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>
            <div class="detail">
                <div class="lft">
                    <p>Product Details: </p>
                </div>
                <div class="rgt">
                    <textarea name="pDesc" class="detail-box" rows="5" placeholder="Add Description..."></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <div class="add-product-buttons-section">
        <!-- Updated Cancel button to use the handleCancel function -->
        <button class="button" type="button" onclick="handleCancel()">Cancel</button>
        <button class="button" type="button" onclick="validateForm(event)">Add</button>
    </div>
</form>


<?php include '../includes/footer.php'; ?>
