<?php
	$id = $_GET['p_edit'];
	$sql = "SELECT * FROM products WHERE ProductID = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$product_name = $row['ProductName'];
		$product_price = $row['Price'];
		$category_id = $row['CategoryID'];
		$product_description = $row['description'];
		$product_image = $row['image'];
	}

	if (isset($_POST['btnUpdate'])) {
		$product_name = $_POST['ProductName'];
		$product_price = $_POST['Price'];
		$product_description = $_POST['description'];
		$category_id = $_POST['sel_category'];

		// File upload handling
		$filename = $_FILES['product_img']['name'];
		$filesize = $_FILES['product_img']['size'];
		$filetmp = $_FILES['product_img']['tmp_name'];
		$file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$allowed_extensions = array("jpeg", "png", "jpg");

		if (empty($filename)) {
			// If no new image is uploaded, keep the current image
			$filename = $product_image;
		} else {
			// Check file size and extension
			if ($filesize > 2097152) {
				echo msgstyle('File size must not exceed 2MB!', 'info');
			} elseif (!in_array($file_ext, $allowed_extensions)) {
				echo msgstyle('Invalid file extension. Please choose a jpeg, jpg, or png file.', 'info');
			} else {
				// Remove old image if exists
				if (file_exists("assets/img/product_img/" . $product_image)) {
					unlink("assets/img/product_img/" . $product_image);
				}
				// Move uploaded file to target directory
				move_uploaded_file($filetmp, "assets/img/product_img/" . $filename);
			}
		}

		// Update product details in the database
		$sql = "
			UPDATE products SET
				ProductName = ?,
				CategoryID = ?,
				Price = ?,
				description = ?,
				image = ?
			WHERE ProductID = ?";
		$stmt = $conn->prepare($sql);
		if ($stmt === false) {
			echo msgstyle('Error in SQL preparation: ' . htmlspecialchars($conn->error), 'info');
		} else {
			$stmt->bind_param("sisssi", $product_name, $category_id, $product_price, $product_description, $filename, $id);
			if ($stmt->execute()) {
				echo msgstyle('Product updated successfully!', 'success');
			} else {
				echo msgstyle('Failed to update product!', 'info');
			}
			$stmt->close();
		}
	}
?>

<!-- HTML form for updating product -->
<div class="row">
	<div class="col-lg-8 offset-lg-2">
		<h4 class="page-title">Update Product</h4>

		<form method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label>Product Name <span class="text-danger">*</span></label>
						<input class="form-control" type="text" name="ProductName" value="<?= htmlspecialchars($product_name); ?>" required>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Price <span class="text-danger">*</span></label>
						<input class="form-control" type="number" name="Price" value="<?= htmlspecialchars($product_price); ?>" required>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Category <span class="text-danger">*</span></label>
						<select class="form-control select" name="sel_category" required>
							<option value="">--- Please Select ---</option>
							<?php
								$sql = mysqli_query($conn, "SELECT * FROM productcategories;");
								while ($row = mysqli_fetch_array($sql)) {
									$selected = ($row['CategoryID'] == $category_id) ? 'selected' : '';
									echo "<option value='{$row['CategoryID']}' $selected>{$row['CategoryName']}</option>";
								}
							?>
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Product Image</label>
						<div class="profile-upload">
							<div class="upload-img">
								<img src="assets/img/product_img/<?= htmlspecialchars($product_image); ?>" alt="Product Image">
							</div>
							<div class="upload-input">
								<input type="file" class="form-control" name="product_img">
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<label>Description</label>
						<textarea class="form-control" rows="3" name="description" required><?= htmlspecialchars($product_description); ?></textarea>
					</div>
				</div>
			</div>
			<div class="m-t-20 text-center">
				<button type="submit" name="btnUpdate" class="btn btn-primary submit-btn">Update Product</button>
			</div>
		</form>
	</div>
</div>
