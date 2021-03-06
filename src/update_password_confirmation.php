<?php 

require 'config/config.php';

if(!isset($_POST['password']) || empty($_POST['password'])) {
	$error = "No password provided";
} else {
	// Store new password in database
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		exit();
	}

	// Hash the password
	$password = hash("sha256", $_POST["password"]);

	// Insert new user into users table
	$sql = "UPDATE users SET password ='" . $password . "' WHERE email='" . $_SESSION['email'] . "';";

	$results = $mysqli->query($sql);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}
}

 ?>

 <!DOCTYPE html>
<html>
<head>
	<title>Update Password Confirmation</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <link rel="stylesheet" href="style.css">
</head>
<body>
	<!-- Navigation -->
	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
		<a class="navbar-brand" href="#">LightRemote</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="rooms.php">Rooms</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="messages.php">Messages</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="settings.php">Settings<span class="sr-only">(current)</span></a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="container">

		<div class="row mt-4">
				<div class="col-12">
					<?php if ( isset($error) && !empty($error) ) : ?>
						<div class="text-danger"><?php echo $error; ?></div>
					<?php else : ?>
						<div class="text-success">Password was successfully updated.</div>
					<?php endif; ?>
			</div> 
		</div>

		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" role="button" class="btn btn-light">Back</a>
			</div>
		</div>

	</div> 



</body>
</html>