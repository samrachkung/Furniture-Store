<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");
include_once '../frontend/database/config_db.php';

?>
<!DOCTYPE html>
<html lang="en">
<!-- login23:11-->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	<link rel="shortcut icon" href="../favicon.png">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="../backend/assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../backend/assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="../backend/assets/css/style.css">
	<!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body>
	<div class="main-wrapper account-wrapper">
		<div class="account-page">
			<div class="account-center">
				<div class="account-box">

					<?php
					if (isset($_POST['btnLogin'])) {
						$username = $conn->real_escape_string(trim($_POST['txtusername']));
						$userpwd = $conn->real_escape_string(trim($_POST['txtpassword']));
						$userpwd = md5($userpwd);
						if ($username == "" || $userpwd == "") {
							echo '<div class="alert alert-info alert-dismissible fade show" role="alert">
										  <strong>Info!</strong> Please input all fields!.
										  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										  </button>
										</div>';
						} else {
							$sql = "select * from customers 
									where name='$username' and password='$userpwd'";


							#echo $sql;	
							$result = $conn->query($sql);
							if (mysqli_num_rows($result) > 0) {
								$row = mysqli_fetch_array($result);
								$_SESSION['user_login'] = $username;


								#check rememberme
								if (!empty($_POST['rememberme'])) {
									setcookie("user_login", $username, time() + 604800); #24*60*60*7=1week
									setcookie("user_passwd", $_POST['txtpassword'], time() + 604800);

								} else {
									if (isset($_COOKIE['user_login'])) {
										setcookie("user_login", "");
									}
									if (isset($_COOKIE['user_passwd'])) {
										setcookie("user_passwd", "");
									}
								}

								header("location:index.php");
							} else {
								echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
											  <strong>Info!</strong> Wrong Username or Password!.
											  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											  </button>
											</div>';
							}
						}
					}
					?>

					<form method="post" class="form-signin">
						<div class="account-logo">
							<a href="#"><img src="../favicon.png" alt=""></a>
						</div>

						<div class="form-group">
							<label>Username</label>
							<input type="text" name="txtusername" autofocus="" class="form-control">
						</div>
						<div class="form-group">
							<label>Password</label>
							<input type="password" name="txtpassword" class="form-control">
						</div>
						<div class="form-group">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" name="rememberme" <?php if (isset($_COOKIE['user_login'])) {
									echo 'checked';
								} ?> />
								<label class="form-check-label" for="RememberPassword">
									Remember me
								</label>
							</div>
						</div>
						<div class="form-group text-right">
							<a href="forgot-password.html">Forgot your password?</a>
						</div>
						<div class="form-group text-center">
							<button type="submit" name="btnLogin" class="btn btn-primary account-btn">Login</button>
						</div>
						<div class="text-center register-link">
							Don’t have an account? <a href="register.php">Register Now</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script src="assets/js/jquery-3.2.1.min.js"></script>
	<script src="assets/js/popper.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/app.js"></script>
</body>


<!-- login23:12-->

</html>