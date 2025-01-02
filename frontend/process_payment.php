<?php
session_start();
require '../frontend/database/config_db.php';
require __DIR__ . '/vendor/autoload.php';

use Stripe\Stripe;
use Stripe\PaymentIntent;

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['cart_total'])) {
    echo "Error: Cart total not found.";
    exit;
}

try {
    $cartTotal = $_SESSION['cart_total'];
    $amountInCents = $cartTotal * 100;

    Stripe::setApiKey('sk_test_51QE02NC6KaJZQelZuhumMn20hbDDPvTJmxxROW3c3GmwsZIRrta4FcJU2azQh04EHUiTViFqlD3vueW2gucbuzpQ00hU4wmLDs');

    $paymentIntent = PaymentIntent::create([
        'amount' => $amountInCents,
        'currency' => 'usd',
        'payment_method' => $_POST['payment-method-id'],
        'confirm' => true,
    ]);

    echo "Payment successful! Thank you for your order.";

} catch (\Stripe\Exception\ApiErrorException $e) {
    echo "Payment failed: " . htmlspecialchars($e->getMessage());
    exit;
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
