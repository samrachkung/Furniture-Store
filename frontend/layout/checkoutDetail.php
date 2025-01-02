<?php
session_start();
include_once '../frontend/database/config_db.php';

if (!isset($_SESSION['user_login'])) {
    header("Location: login.php");
    exit;
}

$cart = $_SESSION['cart'][$_SESSION['user_login']] ?? [];
$total = calculateTotal($cart);
$_SESSION['cart_total'] = $total; // Store total for the payment page

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['billing_details'] = array_map('htmlspecialchars', [
        'country' => $_POST['c_country'],
        'fname' => $_POST['c_fname'],
        'lname' => $_POST['c_lname'],
        'company_name' => $_POST['c_companyname'],
        'address' => $_POST['c_address'],
        'state_country' => $_POST['c_state_country'],
        'postal_zip' => $_POST['c_postal_zip'],
        'email_address' => $_POST['c_email_address'],
        'phone' => $_POST['c_phone'],
        'order_notes' => $_POST['c_order_notes']
    ]);

    // Redirect to checkout page after setting billing details
    header("Location: checkout.php");
    exit;
}

function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $product) {
        $total += $product['Price'] * $product['quantity'];
    }
    return $total;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>

<body>
    <form method="POST" action="checkout.php">
        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <h2 class="h3 mb-3 text-black">Billing Details</h2>
                <div class="p-3 p-lg-5 border bg-white">
                    <div class="form-group">
                        <label for="c_country" class="text-black">Country <span class="text-danger">*</span></label>
                        <select id="c_country" class="form-control" name="c_country" required>
                            <option value="1">Select a country</option>
                            <option value="2">Bangladesh</option>
                            <option value="3">Algeria</option>
                            <option value="4">Afghanistan</option>
                            <option value="5">Ghana</option>
                            <option value="6">Albania</option>
                            <option value="7">Bahrain</option>
                            <option value="8">Colombia</option>
                            <option value="9">Dominican Republic</option>
                        </select>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="c_fname" class="text-black">First Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_fname" name="c_fname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="c_lname" class="text-black">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_lname" name="c_lname" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="c_companyname" class="text-black">Company Name</label>
                            <input type="text" class="form-control" id="c_companyname" name="c_companyname">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="c_address" class="text-black">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_address" name="c_address"
                                placeholder="Street address" required>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <input type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)">
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="c_state_country" class="text-black">State / Country <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_state_country" name="c_state_country"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="c_postal_zip" class="text-black">Postal / Zip <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip" required>
                        </div>
                    </div>

                    <div class="form-group row mb-5">
                        <div class="col-md-6">
                            <label for="c_email_address" class="text-black">Email Address <span
                                    class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="c_email_address" name="c_email_address"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="c_phone" class="text-black">Phone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="c_phone" name="c_phone" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="c_create_account" class="text-black"><input type="checkbox" value="1"
                                id="c_create_account" name="c_create_account"> Create an account?</label>
                    </div>

                    <div class="form-group">
                        <label for="c_order_notes" class="text-black">Order Notes</label>
                        <textarea name="c_order_notes" id="c_order_notes" cols="30" rows="5" class="form-control"
                            placeholder="Write your notes here..."></textarea>
                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <h2 class="h3 mb-3 text-black">Coupon Code</h2>
                        <div class="p-3 p-lg-5 border bg-white">
                            <label for="c_code" class="text-black mb-3">Enter your coupon code if you have
                                one</label>
                            <div class="input-group w-75 couponcode-wrap">
                                <input type="text" class="form-control me-2" id="c_code" placeholder="Coupon Code"
                                    aria-label="Coupon Code" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-black btn-sm" type="button" id="button-addon2">Apply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-md-12">
                        <h2 class="h3 mb-3 text-black">Your Order</h2>
                        <div class="p-3 p-lg-5 border bg-white">
                            <table class="table site-block-order-table mb-5">
                                <thead>
                                    <th>Product</th>
                                    <th>Total</th>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['ProductName']); ?> <strong
                                                    class="mx-2">x</strong> <?= $product['quantity']; ?></td>
                                            <td>$<?= number_format($product['Price'] * $product['quantity'], 2); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Cart Subtotal</strong>
                                        </td>
                                        <td class="text-black">$<?= number_format($total, 2); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="text-black font-weight-bold"><strong>Order Total</strong>
                                        </td>
                                        <td class="text-black font-weight-bold">
                                            <strong>$<?= number_format($total, 2); ?></strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="border p-3 mb-3">
                                <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsebank"
                                        role="button" aria-expanded="false" aria-controls="collapsebank">Direct Bank
                                        Transfer</a></h3>
                                <div class="collapse" id="collapsebank">
                                    <div class="py-2">
                                        <p class="mb-0">Make your payment directly into our bank account. Please
                                            use your Order ID as the payment reference. Your order will not be
                                            shipped until the funds have cleared in our account.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border p-3 mb-3">
                                <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsecheque"
                                        role="button" aria-expanded="false" aria-controls="collapsecheque">Cheque
                                        Payment</a></h3>
                                <div class="collapse" id="collapsecheque">
                                    <div class="py-2">
                                        <p class="mb-0">Please send your cheque to Store Name, Store Street,
                                            Store Town, Store State / County, Store Postcode.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="border p-3 mb-5">
                                <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsepaypal"
                                        role="button" aria-expanded="false" aria-controls="collapsepaypal">PayPal</a>
                                </h3>
                                <div class="collapse" id="collapsepaypal">
                                    <div class="py-2">
                                        <p class="mb-0">Pay via PayPal; you can pay with your credit card if you
                                            don’t have a PayPal account.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <button class="btn btn-black btn-lg btn-block"
                                    type="submit">Place Order</button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</body>

</html>