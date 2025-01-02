<?php
session_start();
require __DIR__ . '/../frontend/database/config_db.php';
require __DIR__ . '/vendor/autoload.php';

use Stripe\Stripe;
use Stripe\PaymentIntent;

// Set Stripe API Key (replace with your actual key)
Stripe::setApiKey('sk_test_51QE02NC6KaJZQelZuhumMn20hbDDPvTJmxxROW3c3GmwsZIRrta4FcJU2azQh04EHUiTViFqlD3vueW2gucbuzpQ00hU4wmLDs');

if (!isset($_SESSION['cart_total'])) {
    echo "Error: Cart total not found. Please return to the cart.";
    exit;
}

$cartTotal = $_SESSION['cart_total'];
$amountInCents = $cartTotal * 100;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method_id'])) {
    try {
        $paymentIntent = PaymentIntent::create([
            'amount' => $amountInCents,
            'currency' => 'usd',
            'payment_method' => $_POST['payment_method_id'],
            'confirmation_method' => 'manual',
            'confirm' => true,
            'return_url' => 'http://localhost/ST20-Furniture-Store/frontend/index.php?layout=thankyou', // Replace with your actual URL
        ]);
        
        header("Location: index.php?layout=thankyou");
        exit;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "Payment failed: " . htmlspecialchars($e->getMessage());
    } catch (Exception $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 500px;
            background: #fff;
            padding: 2em;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h2 {
            color: #333;
            margin-bottom: 1.5em;
        }
        .form-group label {
            font-weight: bold;
        }
        #card-element {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            margin-bottom: 1em;
        }
        .btn-primary {
            width: 100%;
            padding: 10px;
        }
        #error-message {
            color: red;
            margin-top: 1em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <form id="payment-form" method="POST">
            <div class="form-group">
                <label for="address">Street Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="Street Address" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="City" value="Phnom Penh" required>
            </div>
            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" class="form-control" id="province" name="province" placeholder="Province" required>
            </div>
            <div class="form-group">
                <label for="postal_code">Postal Code</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" placeholder="Postal Code" required>
            </div>
            <div id="card-element"></div>
            <button type="submit" class="btn btn-primary">Pay $<?= number_format($cartTotal, 2); ?></button>
            <input type="hidden" name="payment_method_id" id="payment-method-id">
            <div id="error-message"></div>
        </form>
    </div>

    <script>
    const stripe = Stripe('pk_test_51QE02NC6KaJZQelZg5U9UqH6JD5xDlv7RY2vbSq9jwXYwJDgzZw62Qmj8tnAyqmb0UqIM5wOlngS7E2vxB93SpLv00MONR4Qh5');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    document.getElementById('payment-form').addEventListener('submit', async (event) => {
        event.preventDefault();

        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            document.getElementById('error-message').textContent = error.message;
        } else {
            document.getElementById('payment-method-id').value = paymentMethod.id;
            document.getElementById('payment-form').submit();
        }
    });
    </script>
</body>
</html>
