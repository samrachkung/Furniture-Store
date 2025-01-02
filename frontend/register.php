<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");
include_once '../frontend/database/config_db.php';

// Process form submission
if (isset($_POST['btnSignup'])) {
    // Sanitize inputs
    $username = $conn->real_escape_string(trim($_POST['txtusername']));
    $userpwd = $conn->real_escape_string(trim($_POST['txtpassword']));
    $userpwd = md5($userpwd); // Encrypt password with MD5

    // Basic validation for empty fields
    if (empty($username) || empty($userpwd)) {
        $message = 'Please fill in all fields.';
    } else {
        // Check if the username already exists
        $sql = "SELECT * FROM users WHERE UserName='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $message = 'Username already exists. Please choose another one.';
        } else {
            // Insert new user data into the database
            $sql = "INSERT INTO users (UserName, Password) VALUES ('$username', '$userpwd')";
            if ($conn->query($sql) === TRUE) {
                $message = 'Registration completed successfully.';
            } else {
                $message = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <link rel="shortcut icon" type="image/x-icon" href="../favicon.png">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="../backend/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../backend/assets/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../backend/assets/css/style.css">
</head>

<body>
    <div class="main-wrapper account-wrapper">
        <div class="account-page">
            <div class="account-center">
                <div class="account-box">
                    <?php
                        // Display message if set
                        if (isset($message)) {
                            echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
                                    <strong>Info!</strong> ' . htmlspecialchars($message) . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                        }
                    ?>
                    <form action="" method="post" class="form-signin">
                        <div class="account-logo">
                            <a href="#"><img src="../favicon.png" alt="Logo"></a>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="txtusername" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="txtpassword" class="form-control" required>
                        </div>
                        <div class="form-group checkbox">
                            <label>
                                <input type="checkbox" required> I have read and agree to the Terms & Conditions
                            </label>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" name="btnSignup" class="btn btn-primary account-btn">Signup</button>
                        </div>
                        <div class="text-center login-link">
                            Already have an account? <a href="login.php">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="../backend/assets/js/jquery-3.2.1.min.js"></script>
    <script src="../backend/assets/js/popper.min.js"></script>
    <script src="../backend/assets/js/bootstrap.min.js"></script>
    <script src="../backend/assets/js/app.js"></script>
</body>
</html>
