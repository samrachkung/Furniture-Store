<?php
$id = $_GET['p_id']; // Product ID passed as parameter

// Prepare the SQL statement with parameterized query
$sql = "
    SELECT
        p.ProductID, 
        p.ProductName,
        p.Price,
        p.description, 
        p.image, 
        c.CategoryName
    FROM
        products AS p
    INNER JOIN
        productcategories AS c ON p.CategoryID = c.CategoryID
    WHERE
        p.ProductID = ?
";

// Prepare and execute the query
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch result
$row = mysqli_fetch_array($result);

$p_name = $row['ProductName'];
$p_price = $row['Price'];
$p_description = $row['description'];
$p_image = $row['image'];
$c_name = $row['CategoryName']; // Fetch category name

?>
<style>
    .profile-img img{
        width: 100px;
        height: 100px;
    }
</style>
<div class="row">
    <div class="col-sm-7 col-6">
        <h4 class="page-title">Product Profile</h4>
    </div>
    <div class="col-sm-5 col-6 text-right m-b-30">
        <a href="index.php?product=edit-profile&p_id=<?= $id ?>" class="btn btn-primary btn-rounded"><i class="fa fa-pencil"></i> Edit Profile</a>
    </div>
</div>
<div class="card-box profile-header">
    <div class="row">
        <div class="col-md-12">
            <div class="profile-view">
                <div class="profile-img-wrap">
                   
                    <div class="profile-img">
                        <img src="assets/img/product_img/<?= htmlspecialchars($p_image) ?>" alt="">
                    </div>
                </div>
                <div class="profile-basic">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="profile-info-left">
                                <h3 class="user-name m-t-0 mb-0"><?= htmlspecialchars($p_name); ?></h3>
                                <small class="text-muted"><?= htmlspecialchars($c_name); ?></small>
                                <div class="product-price">Price: $<?= htmlspecialchars($p_price); ?></div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <ul class="personal-info">
                                <li>
                                    <span class="title">Description:</span>
                                    <span class="text"><?= htmlspecialchars($p_description); ?></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>                        
        </div>
    </div>
</div>
<div class="profile-tabs">
    <ul class="nav nav-tabs nav-tabs-bottom">
        <li class="nav-item"><a class="nav-link active" href="#about-cont" data-toggle="tab">Details</a></li>
        <li class="nav-item"><a class="nav-link" href="#bottom-tab2" data-toggle="tab">Reviews</a></li>
        <li class="nav-item"><a class="nav-link" href="#bottom-tab3" data-toggle="tab">Supplier Info</a></li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane show active" id="about-cont">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-box">
                        <h3 class="card-title">Product Details</h3>
                        <p><?= htmlspecialchars($p_description); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
