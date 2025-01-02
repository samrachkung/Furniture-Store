<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_login'])) {
    $username = $_SESSION['user_login'];

    // Check if the cart exists for the user and unset it
    if (isset($_SESSION['cart'][$username])) {
        unset($_SESSION['cart'][$username]); // Unset the user's cart data
    }

    // Unset any other session data associated with the user, if needed
    if (isset($_SESSION[$username])) {
        unset($_SESSION[$username]); // Unset additional user-specific session data
    }
}

// Destroy the entire session
session_destroy();

// Clear cookies by setting their expiration time to the past
if (isset($_COOKIE['PHPSESSID'])) {
    setcookie('PHPSESSID', '', time() - 3600, '/'); // Clear session cookie
}

// Redirect to the homepage or login page after logout
header("Location: index.php");

exit;
?>
