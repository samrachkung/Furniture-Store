<div class="row">
	<div class="col-sm-4 col-3">
		<h4 class="page-title">Product</h4>
	</div>

	<div class="col-sm-8 col-9 text-right m-b-20">
		<a href="index.php?product=add-product" class="btn btn-primary btn-rounded float-right"><i
				class="fa fa-plus"></i> Add Product</a>
	</div>

	<?php
	if (isset($_GET['msg'])) {
		if ($_GET['msg'] == 200) {
			echo msgstyle('Data updated successfully!', 'success');
		} elseif ($_GET['msg'] == 202) {
			echo msgstyle('Data deleted successfully!', 'danger');
		} else {
			echo msgstyle('Error in page', 'info');
		}
	}
	?>
</div>

<form method="get">
	<div class="row filter-row">
		<input type="hidden" name="product" value="products" />
		<div class="col-sm-6 col-md-3">
			<div class="form-group form-focus select-focus">
				<label class="focus-label">Category</label>
				<select class="form-control select" name="key_category">
					<option value="">--- Please Select ---</option>
					<?php
					$sql = mysqli_query($conn, "SELECT * FROM productcategories;");
					while ($row = mysqli_fetch_array($sql)) {
						echo "<option value='" . $row['CategoryID'] . "'>" . $row['CategoryName'] . "</option>";
					}
					?>
				</select>
			</div>
		</div>

		<div class="col-sm-6 col-md-3">
			<div class="form-group form-focus">
				<label class="focus-label">Search by Product Name</label>
				<input type="text" name="keyinputdata" class="form-control floating">
			</div>
		</div>

		<div class="col-sm-6 col-md-3">
			<button type="submit" name="btnSearch" class="btn btn-success btn-block"> Search </button>
		</div>
	</div>
</form>

<div class="row product-grid">
	<?php
	# Searching data
	if (isset($_GET['btnSearch'])) {
		$key_category = $_GET['key_category'];
		$keyinputdata = $_GET['keyinputdata'];

		# Pagination setup for searching
		$number_of_page = 0;
		$s = "SELECT count(*) FROM products as p
				INNER JOIN productcategories as c ON p.CategoryID = c.CategoryID";

		$q = $conn->query($s);
		$r = mysqli_fetch_row($q);
		$row_per_page = 8;
		$number_of_page = ceil($r[0] / $row_per_page);  # Rounding numbers up
	
		if (!isset($_GET['pn'])) {
			$current_page = 0;
		} else {
			$current_page = $_GET['pn'];
			$current_page = ($current_page - 1) * $row_per_page;
		}

		# Query for searching products
		$sql_select = "
				SELECT
					p.ProductID, 
					p.ProductName,
					p.CategoryID,
					p.Price,				
					p.description,
					p.image
				FROM
					products as p
				INNER JOIN
					productcategories as c ON p.CategoryID = c.CategoryID";

		# Condition based query
		if ($key_category == "" && $keyinputdata == "") {
			$sql = $sql_select . " LIMIT $current_page, $row_per_page";
		}
		if ($key_category) {
			$sql = $sql_select . " WHERE
					c.CategoryID = '$key_category'
				ORDER BY
					c.CategoryID DESC LIMIT $current_page, $row_per_page";
		}
		if ($keyinputdata) {
			$sql = $sql_select . " WHERE
					p.ProductName LIKE  '%" . $keyinputdata . "%'
				ORDER BY
					p.ProductID DESC LIMIT $current_page, $row_per_page";
		}
		$result = mysqli_query($conn, $sql);
		$num_row = $result->num_rows;
	} else {
		# Default pagination
		$number_of_page = 0;
		$s = "SELECT count(*) FROM products";

		$q = $conn->query($s);
		$r = mysqli_fetch_row($q);
		$row_per_page = 8;
		$number_of_page = ceil($r[0] / $row_per_page);

		if (!isset($_GET['pn'])) {
			$current_page = 0;
		} else {
			$current_page = $_GET['pn'];
			$current_page = ($current_page - 1) * $row_per_page;
		}

		# Default query for products
		$sql = "
				SELECT
					p.ProductID, 
					p.ProductName,
					p.CategoryID,
					p.Price,				
					p.description,
					p.image
				FROM
					products as p
				ORDER BY
					p.ProductID DESC LIMIT $current_page, $row_per_page";

		$result = mysqli_query($conn, $sql);
		$num_row = $result->num_rows;
	}

	if ($result->num_rows > 0) {
		$i = 1;
		while ($row = mysqli_fetch_array($result)) {
			?>
			<div class="col-md-4 col-sm-4 col-lg-3">
				<div class="profile-widget">
					<div class="product-img">
						<a class="avatar" href="index.php?product=profile&p_id=<?= $row['ProductID'] ?>">
							<img alt="" src="assets/img/product_img/<?= $row['image'] ?>">
						</a>
					</div>
					<div class="dropdown profile-action">
						<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
								class="fa fa-ellipsis-v"></i></a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="index.php?product=edit-product&p_edit=<?= $row['ProductID'] ?>"><i
									class="fa fa-pencil m-r-5"></i> Edit</a>
							<a class="dropdown-item" href="#" data-toggle="modal"
								data-target="#view_product<?= $row['ProductID'] ?>">
								<i class="fa fa-eye m-r-5"></i> View
							</a>
							<a href="pages/product/del_product.php?Productid=<?= $row['ProductID'] ?>"
								onclick="return confirm('Are you sure to delete it?')" class="dropdown-item">
								<i class="fa fa-trash-o m-r-5"></i> Delete
							</a>

						</div>
					</div>
					<h4 class="product-name text-ellipsis">
						<a href="index.php?product=profile&p_id=<?= $row['ProductID'] ?>">
							<?= $row['ProductName'] ?>
						</a>
					</h4>
					<div class="product-price"><?= $row['Price'] ?> USD</div>
				</div>
			</div>
			<?php
			$i++;
			require("pages/product/view_product.php");
		}
	} else {
		echo '
				<div class="col-sm-12">
					<div class="see-all">
						<a class="see-all-btn" href="#" style="color:#f62d51;">No Data Found!</a>
					</div>
				</div>
			';
	}
	?>
</div>

<?php
if ($result->num_rows > 0) {
	?>
	<div class="row">
		<div class="col-sm-12">
			<div class="see-all">
				<?php
				require_once("pages/pagin/paggin.php");
				?>
			</div>
		</div>
	</div>
	<?php
}
?>