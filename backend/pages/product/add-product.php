<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <h4 class="page-title">Add Product</h4>
    </div>

    <?php
    $product_name = "";
    $product_price = "";
    $product_description = "";
    $category_id = "";
    $msg8 = ""; // Message variable for file errors

    if (isset($_POST['btnSave'])) {

        $product_name = $_POST['ProductName'];
        $product_price = $_POST['Price'];
        $product_description = $_POST['description'];
        $category_id = $_POST['sel_category'];

        // File upload handling
        $filename = $_FILES['product_img']['name'];
        $filesize = $_FILES['product_img']['size'];
        $filetmp = $_FILES['product_img']['tmp_name'];
        $filetype = $_FILES['product_img']['type'];

        $filename_bstr = explode(".", $filename); //photo-img.PnG
        $file_ext = strtolower(end($filename_bstr)); //PnG = png
        $extensions = array("jpeg", "png", "jpg"); // Allowed extensions

        if ($filename == '') {
            echo msgstyle('Please select a file!', 'info');
        } else {
            if ($filesize > 2097152) {
                echo msgstyle('File size must not exceed 2MB!', 'info');
            } else {
                if (in_array($file_ext, $extensions) === false) {
                    echo msgstyle('Invalid file extension, please choose a jpeg, jpg, or png file.', 'info');
                } else {
                    move_uploaded_file($filetmp, "assets/img/product_img/" . $filename);

                    // Insert into database
                    if (
                        $product_name != '' &&
                        $category_id != '' &&
                        $product_price != '' &&
                        $product_description != '' &&
                        $filename != ''
                    ) {

                        $sql = "
                            INSERT INTO products (
                                ProductName,
                                CategoryID,
                                Price,
                                description,
                                image
                            ) VALUES (?, ?, ?, ?, ?)";

                        $stmt = $conn->prepare($sql);

                        if ($stmt === false) {
                            echo msgstyle('Error in SQL preparation: ' . htmlspecialchars($conn->error), 'info');
                        } else {
                            $stmt->bind_param(
                                "sssss",  // 5 string parameters
                                $product_name,
                                $category_id,
                                $product_price,
                                $product_description,
                                $filename
                            );

                            // Execute statement
                            if ($stmt->execute()) {
                                echo msgstyle('Data inserted successfully!', 'success');
                            } else {
                                echo msgstyle('Failed to insert data!', 'info');
                            }

                            // Close the statement
                            $stmt->close();
                        }
                    } else {
                        echo msgstyle('All fields are required!', 'info');
                    }
                }
            }
        }
    }
    ?>

</div>

<div class="row">
    <div class="col-lg-8 offset-lg-2">
        <form method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Product Name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="ProductName" value="<?= $product_name; ?>" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Price <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" name="Price" value="<?= $product_price; ?>" required>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select class="form-control select" name="sel_category" required>
                                    <option value="">--- Please Select ---</option>
                                    <?php
                                    $sql = mysqli_query($conn, "SELECT * FROM productcategories;");
                                    while ($row = mysqli_fetch_array($sql)) {
                                        echo "<option value=" . $row['CategoryID'] . ">" . $row['CategoryName'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Product Image <span class="text-danger">*</span></label>
                        <div class="profile-upload">
                            <div class="upload-img">
                                <img alt="" src="assets/img/user.jpg">
                            </div>
                            <div class="upload-input">
                                <input type="file" class="form-control" name="product_img" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" rows="3" cols="30" name="description" required><?= $product_description; ?></textarea>
            </div>
            <div class="m-t-20 text-center">
                <button type="submit" name="btnSave" class="btn btn-primary submit-btn">Create Product</button>
            </div>
        </form>
    </div>
</div>
