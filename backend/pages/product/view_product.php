<!-- Modal for Product View -->
<div class="modal fade" id="view_product<?= $row['ProductID'] ?>" tabindex="-1" aria-labelledby="productModalLabel<?= $row['ProductID'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel<?= $row['ProductID'] ?>">Product Details - [<?= $row['ProductID'] ?>]</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-box profile-header">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="profile-view">
                                <div class="profile-img-wrap">
                                    <div class="profile-img">
                                        <img src="assets/img/product_img/<?= $row['image'] ?>" alt="">
                                    </div>
                                </div>
                                <div class="profile-basic">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="profile-info-left">
                                                <h3 class="user-name m-t-0 mb-0"><?= $row['ProductName'] ?></h3>
                                                <small class="text-muted">Category ID: <?= $row['CategoryID'] ?></small>
                                                <div class="product-id">Product ID : <?= $row['ProductID'] ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <ul class="personal-info">
                                                <li>
                                                    <span class="title">Price:</span>
                                                    <span class="text"><?= $row['Price'] ?> USD</span>
                                                </li>
                                                <li>
                                                    <span class="title">Description:</span>
                                                    <span class="text"><?= $row['description'] ?></span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal -->
