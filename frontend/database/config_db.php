<?php

	// Database connection settings
	$dbhost = 'localhost:3306'; // Use 'localhost' or '127.0.0.1' with port number if needed
	$dbuser = 'root';
	$dbpwd = '';
	
	// Establish connection
	$conn = mysqli_connect($dbhost, $dbuser, $dbpwd);
	
	// Check connection
	if (!$conn) {
		die("Connection Failed: " . mysqli_connect_error());
	}
	
	// Select the database
	if (!mysqli_select_db($conn, "st20-furniture-store")) {
		die("Error selecting database: " . mysqli_error($conn));
	}
	
	// Set the character set to UTF-8
	mysqli_set_charset($conn, "utf8");

	// Function to display styled messages based on type
	function msgstyle($msg, $type) {
		switch ($type) {
			case 'success':
				echo '
					<div class="col-lg-8 offset-lg-2">
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<strong>Success!</strong> ' . $msg . '.
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				';
				break;
				
			case 'warning':
				echo '
					<div class="col-lg-8 offset-lg-2">
						<div class="alert alert-warning alert-dismissible fade show" role="alert">
							<strong>Warning!</strong> ' . $msg . '.
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				';
				break;
				
			case 'info':
				echo '
					<div class="col-lg-8 offset-lg-2">
						<div class="alert alert-info alert-dismissible fade show" role="alert">
							<strong>Info!</strong> ' . $msg . '.
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				';
				break;
				
			case 'danger':
				echo '
					<div class="col-lg-8 offset-lg-2">
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<strong>Danger!</strong> ' . $msg . '.
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				';
				break;
		}
	}

?>
