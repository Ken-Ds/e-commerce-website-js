<?php include '../includes/header.php'; ?>

        <script>
            // Function to confirm before submitting the form
            function confirmSubmission(event) {
                event.preventDefault();
                if (confirm("Are you sure you want to add this product?")) {
                    var form = document.getElementById("productForm");
                    
                    // Get the id parameter from the URL
                    var urlParams = new URLSearchParams(window.location.search);
                    var orgId = urlParams.get('id'); // Get the 'id' parameter value from the URL
                    
                    // Check if the 'id' parameter exists
                    if (orgId) {
                        // Create a hidden input field for orgId and set its value
                        var orgIdInput = document.createElement("input");
                        orgIdInput.type = "hidden";
                        orgIdInput.name = "id"; 
                        orgIdInput.value = orgId;  // Set the value to the 'id' from URL

                        // Append the hidden input to the form
                        form.appendChild(orgIdInput);
                        
                        // Submit the form
                        form.submit();
                    } else {
                        alert("ID parameter is missing in the URL.");
                    }
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
        
        <form id="productForm" class="add-product-body" action="../db/add-function.php" method="post" enctype="multipart/form-data">
            <h1 style="text-align: center;">ADD PRODUCT FORM</h1>
            <div class="sides">
                <div class="left-side">
                    
                    <div class="image">
                        <!-- Image Preview -->
                        <img id="imagePreview" src="../style/img/upload-img.png" alt="Upload Photo" style="cursor: pointer;" onclick="document.getElementById('pImageInput').click()">>
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
                <button class="button" type="button" onclick="location.href='view-product.html'">Cancel</button>
                <button class="button" type="button" onclick="confirmSubmission(event)">Add</button>
            </div>
        </form>

<?php include '../includes/footer.php'; ?>
