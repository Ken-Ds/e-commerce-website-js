<!DOCTYPE html>
<html>
    <head>
        <title>INGS Add Product</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../style/style.css" rel="stylesheet">
        <script>
            // Function to confirm before submitting the form
            function confirmSubmission(event) {
                event.preventDefault();
                if (confirm("Are you sure you want to add this product?")) {
                    document.getElementById("productForm").submit();
                }
            }

            // Function to preview the selected image
            function previewImage(event) {
                const imageInput = event.target;
                const file = imageInput.files[0];
                const preview = document.getElementById('imagePreview');
                
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result; // Set the image preview to the selected image
                    };
                    reader.readAsDataURL(file);
                    document.getElementById('removeButton').style.display = 'block';
                }
            }

            // Function to remove the selected image and reset the preview
            function removeImage() {
                const preview = document.getElementById('imagePreview');
                const imageInput = document.getElementById('pImageInput');
                
                preview.src = "../style/img/upload-img.png";
                imageInput.value = "";  // Clear the file input
                document.getElementById('removeButton').style.display = 'none';
            }
        </script>
    </head>
    <body>
        
        <?php include '../includes/header.php'; ?>
        
        <form id="productForm" class="add-product-body" action="../db/add-function.php" method="post" enctype="multipart/form-data">
            <h1 style="text-align: center;">ADD PRODUCT FORM</h1>
            <div class="sides">
                <div class="left-side">
                    
                    <div class="image" onclick="document.getElementById('pImageInput').click()">
                        <!-- Image Preview -->
                        <img id="imagePreview" src="../style/img/upload-img.png" alt="Upload Photo" style="cursor: pointer;">
                        <!-- File input for image -->
                        <input type="file" id="pImageInput" name="pImage" accept="image/*" style="display: none;" onchange="previewImage(event)" required>
                        
                        <!-- Remove button for image -->
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
                                <option>Apparel</option>
                                <option>Food</option>
                                <option>Accessory</option>
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
                <button class="button" type="button" onclick="location.href='view-product.html'">Cancel</button>
                <button class="button" type="button" onclick="confirmSubmission(event)">Add</button>
            </div>
        </form>

        <?php include '../includes/footer.php'; ?>

    </body>
</html>
