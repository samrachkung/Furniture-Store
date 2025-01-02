<?php
session_start();
date_default_timezone_set("Asia/Phnom_Penh");
include_once '../frontend/database/config_db.php';

if (isset($_COOKIE['user_login'])) {
    $_SESSION['user_login'] = $_COOKIE['user_login'];

}
?>

<!doctype html>
<html lang="en">

<head>
	<?php include "../frontend/layout/header.php"; ?>
</head>

<body>

	<!-- Start Header/Navigation -->
	<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

		<?php include "../frontend/layout/navbar.php"; ?>
	</nav>
	<!-- End Header/Navigation -->

	<?php
	if (isset($_GET['layout'])) {
		include "../frontend/layout/" . $_GET['layout'] . '.php';
	} else {
		include "../frontend/layout/default.php";
	}

	?>

	<!-- Start Footer Section -->
	<footer class="footer-section">
		<?php include "../frontend/layout/footer.php"; ?>
	</footer>
	<!-- End Footer Section -->


	<?php include "../frontend/layout/script.php"; ?>
</body>

</html>