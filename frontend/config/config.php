<?php
// config.php
require '../vendor/autoload.php';
$_stripe_secrete_key = 'sk_test_51QE02NC6KaJZQelZuhumMn20hbDDPvTJmxxROW3c3GmwsZIRrta4FcJU2azQh04EHUiTViFqlD3vueW2gucbuzpQ00hU4wmLDs';
\Stripe\Stripe::setApiKey($_stripe_secrete_key); // Replace with your Stripe secret key

?>
