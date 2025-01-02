<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");
include_once "../frontend/database/config_db.php";

// Get username from session
$username = $_SESSION['user_login'] ?? 'guest';

// Initialize the user's cart in session if not set
if (!isset($_SESSION['cart'][$username])) {
    $_SESSION['cart'][$username] = [];
}

// Handle adding products to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ProductID'])) {
    $productID = (int)$_POST['ProductID'];
    $productName = htmlspecialchars($_POST['ProductName']);
    $price = (float)$_POST['Price'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    // Update quantity if product already exists in cart; otherwise, add new item
    if (isset($_SESSION['cart'][$username][$productID])) {
        $_SESSION['cart'][$username][$productID]['quantity'] += $quantity;
    } else {
        $_SESSION['cart'][$username][$productID] = [
            'ProductName' => $productName,
            'Price' => $price,
            'quantity' => $quantity
        ];
    }

    // Update cart cookie for persistence
    setcookie("cart_{$username}", serialize($_SESSION['cart'][$username]), time() + (86400 * 30), "/");

    // Redirect to cart page
    header("Location: index.php?layout=cart");
    exit();
}

// Fetch all products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($conn->error) {
    echo "Error fetching products: " . $conn->error;
    exit();
}
?>

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-12 col-md-4 col-lg-3 mb-15">
                        <form action="index.php?layout=shop" method="POST" class="product-item">
                            <img src="../backend/assets/img/product_img/<?= htmlspecialchars($row['image']); ?>"
                                 class="img-fluid product-thumbnail" alt="<?= htmlspecialchars($row['ProductName']); ?>">
                            <h3 class="product-title"><?= htmlspecialchars($row['ProductName']); ?></h3>
                            <strong class="product-price" id="price-<?= $row['ProductID']; ?>">
                                $<?= number_format($row['Price'], 2); ?>
                            </strong>
                            <p class="product-description"><?= htmlspecialchars($row['description']); ?></p>

                            <!-- Quantity Input with JavaScript-Updated Price -->
                            <div class="mb-1 d-flex align-items-center quantity-container">
                                <button class="btn btn-outline-black decrease" type="button" data-id="<?= $row['ProductID']; ?>">−</button>
                                <input type="text" name="quantity" class="form-control text-center quantity-amount"
                                       id="quantity-<?= $row['ProductID']; ?>" value="1" readonly>
                                <button class="btn btn-outline-black increase" type="button" data-id="<?= $row['ProductID']; ?>">+</button>
                            </div>

                            <!-- Hidden Fields for PHP Data Handling -->
                            <input type="hidden" name="ProductID" value="<?= $row['ProductID']; ?>">
                            <input type="hidden" name="ProductName" value="<?= htmlspecialchars($row['ProductName']); ?>">
                            <input type="hidden" name="Price" value="<?= $row['Price']; ?>" id="hidden-price-<?= $row['ProductID']; ?>">

                            <!-- Add to Cart Button -->
                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found!</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- JavaScript to Handle Quantity Change and Price Update -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const increaseBtns = document.querySelectorAll('.increase');
    const decreaseBtns = document.querySelectorAll('.decrease');

    // Increase quantity
    increaseBtns.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const quantityInput = document.getElementById(`quantity-${id}`);
            let quantity = parseInt(quantityInput.value);
            quantity += 1;
            quantityInput.value = quantity;
            updatePrice(id, quantity);
        });
    });

    // Decrease quantity
    decreaseBtns.forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const quantityInput = document.getElementById(`quantity-${id}`);
            let quantity = parseInt(quantityInput.value);
            if (quantity > 1) {
                quantity -= 1;
                quantityInput.value = quantity;
                updatePrice(id, quantity);
            }
        });
    });

    // Update price display based on quantity
    function updatePrice(id, quantity) {
        const priceElement = document.getElementById(`price-${id}`);
        const hiddenPrice = document.getElementById(`hidden-price-${id}`).value;
        const totalPrice = (hiddenPrice * quantity).toFixed(2);
        priceElement.textContent = `$${totalPrice}`;
    }
});
</script>

<!-- Styling for the Product Display and Interactivity -->
<style>
.product-item {
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 20px;
    text-align: center;
    transition: box-shadow 0.3s ease-in-out;
    border-radius: 8px;
    position: relative;
}
.product-item:hover {
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
}
.product-thumbnail {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    margin-bottom: 15px;
    border-radius: 5px;
}
.product-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}
.product-price {
    font-size: 20px;
    font-weight: bold;
    color: #ff5722;
    margin-bottom: 10px;
}
.product-description {
    font-size: 14px;
    color: #555;
    margin-bottom: 15px;
}
.quantity-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
}
.quantity-amount {
    width: 50px;
    padding: 5px;
    font-size: 16px;
    text-align: center;
}
.btn-outline-black {
    background-color: black;
    color: white;
    padding: 5px 10px;
    font-size: 16px;
    margin: 0 5px;
    border: none;
}
.btn-primary {
    background-color: #ff5722;
    border: none;
    padding: 10px 20px;
    color: white;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}
.btn-primary:hover {
    background-color: #e64a19;
}
.row {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: center;
}
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}
</style>
