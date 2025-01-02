<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");

// Ensure the cart session variable is initialized as an array if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Calculate the total cart amount and store it in session
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $product) {
        // Error handling if product data is missing
        if (!isset($product['Price']) || !isset($product['quantity'])) {
            echo "Error: Cart item missing Price or quantity.";
            continue;
        }
        $total += $product['Price'] * $product['quantity'];
    }
    return $total;
}

// Define a unique key for the user's cart in the cookie
$userCartKey = 'cart_' . ($_SESSION['user_login'] ?? 'guest');

// Check if a cart cookie exists and merge with session cart if necessary
if (isset($_COOKIE[$userCartKey])) {
    $cookie_cart = @unserialize($_COOKIE[$userCartKey]);
    if ($cookie_cart !== false) {
        foreach ($cookie_cart as $productID => $product) {
            // If product doesn't exist in session cart, add it from the cookie
            if (!isset($_SESSION['cart'][$productID])) {
                $_SESSION['cart'][$productID] = $product;
            }
        }
    } else {
        echo "Error: Could not read cart data from cookie.";
    }
}

// Handle cart updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $productID => $quantity) {
        if (isset($_SESSION['cart'][$productID])) {
            $_SESSION['cart'][$productID]['quantity'] = max(1, (int)$quantity);
        }
    }
    // Update the cart cookie to match the session cart
    setcookie($userCartKey, serialize($_SESSION['cart']), time() + (86400 * 30), "/");
    header('Location: index.php?layout=cart'); // Redirect to refresh the page and show updated cart
    exit;
}

// Handle product removal
if (isset($_GET['remove']) && isset($_SESSION['cart'][$_GET['remove']])) {
    unset($_SESSION['cart'][$_GET['remove']]);
    // Update the cart cookie after removal
    setcookie($userCartKey, serialize($_SESSION['cart']), time() + (86400 * 30), "/");
    header('Location: index.php?layout=cart'); // Redirect to refresh the page and show updated cart
    exit;
}

// Retrieve the current cart and calculate the total
$cart = $_SESSION['cart'];
$total = calculateTotal($cart);

// Store the total in session for cross-page access
$_SESSION['cart_total'] = $total;
?>

<h1>Your Shopping Cart</h1>
<form method="post" action="index.php?layout=cart">
    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($cart)): ?>
                <tr>
                    <td colspan="6">Your cart is empty.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($cart as $productID => $product): ?>
                    <tr>
                        <td><img src="../backend/assets/img/product_img/<?= htmlspecialchars($product['image']); ?>"
                                alt="<?= htmlspecialchars($product['ProductName']); ?>" width="100"></td>
                        <td><?= htmlspecialchars($product['ProductName']); ?></td>
                        <td>$<?= number_format($product['Price'], 2); ?></td>
                        <td>
                            <input type="number" name="quantity[<?= $productID; ?>]" value="<?= $product['quantity']; ?>"
                                min="1">
                        </td>
                        <td>$<?= number_format($product['Price'] * $product['quantity'], 2); ?></td>
                        <td><a href="cart.php?remove=<?= $productID; ?>" class="btn btn-outline-black">X</a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="cart-summary">
        <h3>Cart Totals</h3>
        <p>Subtotal: $<?= number_format($total, 2); ?></p>
        <p>Total: $<?= number_format($total, 2); ?></p>
    </div>

    <button type="submit" name="update_cart" class="btn btn-primary">Update Cart</button>
    <button type="submit" formaction="checkout.php" class="btn btn-primary">Proceed to Checkout</button>
</form>

<button type="button" onclick="window.location='index.php?layout=shop'" class="btn btn-outline-black">Continue Shopping</button>

<!-- Add CSS styles here -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #f4f4f4;
        font-weight: bold;
        color: #333;
    }

    td img {
        max-width: 100px;
        border-radius: 5px;
    }

    input[type="number"] {
        width: 60px;
        padding: 5px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-align: center;
    }

    .btn {
        padding: 10px 20px;
        background-color: #333;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
    }

    .btn:hover {
        background-color: #555;
    }

    .btn-outline-black {
        background-color: transparent;
        border: 2px solid #333;
        color: #333;
    }

    .btn-outline-black:hover {
        background-color: #333;
        color: white;
    }
/* 
    .cart-summary {
        align-items: center;
        text-align: right;
        margin-top: 20px;
    }

    .cart-summary p {
        font-size: 18px;
        color: #333;
    }

    .cart-summary h3 {
        margin-bottom: 10px;
        color: #333;
    } */

    @media (max-width: 768px) {
        table {
            font-size: 14px;
        }

        td, th {
            padding: 8px;
        }
    }
</style>