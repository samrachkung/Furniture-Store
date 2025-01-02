<?php
include_once '../frontend/database/config_db.php';

if (isset($_GET['Productid']) && is_numeric($_GET['Productid'])) {
    $id = $_GET['Productid'];

    // Debugging: Check if the Productid is received
    if ($id) {
        echo "ProductID received: " . $id;
    }

    // Delete query
    $sql = "DELETE FROM products WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $delete_result = $stmt->execute();

    if ($delete_result) {
        // Redirect on success
        header("Location: ../index.php?msg=202");
        exit();
    } else {
        echo "Error: Unable to delete the product!";
    }
} else {
    echo "Invalid ProductID!";
}
?>
