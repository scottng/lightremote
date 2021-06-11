<?php

// If no user is logged in, do the usual things. Otherwise redirect user out of this page
if(!isset($_SESSION["logged_out"]) || !_SESSION["logged_in"]) {

require 'config/config.php';

// Check if user has entered in email/password
if ( isset($_POST['email']) && isset($_POST['password']) ) {
	// User did not enter email/password, it's blank
	if ( empty($_POST['email']) || empty($_POST['password']) ) {

		$error = "Please enter email and password.";

	}
	else {
		// User did enter user/pw but need to check if user/pw combo is correct
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		if($mysqli->connect_errno) {
			// echo $mysqli->connect_error;
			exit();
		}

		// hash pw then compare to db 
		$passwordInput = hash("sha256", $_POST["password"]);

		$sql = "SELECT * FROM users
					WHERE email = '" . $_POST['email'] . "' AND password = '" . $passwordInput . "';";

		// echo "<hr>" . $sql . "<hr>";
		
		$results = $mysqli->query($sql);

		if(!$results) {
			// echo $mysqli->error;
			exit();
		}

		// If we get >=1 result back, means user/pw combo is correct
		if($results->num_rows > 0) {

			// Set session variables to remember this user
			$_SESSION["email"] = $_POST["email"];
			$_SESSION["logged_in"] = true;

			// Redirect user to the home page
			header("Location: rooms.php");
		}
		else {
			$error = "Invalid email or password.";
		}
	} 

}
} else {
	// Redirect logged in user
	header("Location: rooms.php");

}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>

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
				<li class="nav-item active">
					<a class="nav-link" href="login.php">Login<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="register_form.php">Register</a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="d-flex justify-content-center align-items-center m-5 text-white">
		<h4>
			Control Philips Hue lights from your web browser.
		</h4>
	</div>

	<div class="d-flex justify-content-center align-items-center m-5 text-white">
		<form action="login.php" method="POST">

			<div class="row mb-3">
				<div class="font-italic text-danger col-sm-9 ml-sm-auto">
					<?php
						if ( isset($error) && !empty($error) ) {
							echo $error;
						}
					?>
				</div>
			</div>

			<div class="form-row align-items-center">
				<div class="col-auto">
					<input type="email" class="form-control mb-2" name="email" placeholder="Email address">
				</div>
				<div class="col-auto">
					<div class="input-group mb-2">
						<input type="password" class="form-control" name="password" placeholder="Password">
					</div>
				</div>
			</div>
			<div class="form-row">
				<div class="col-auto">
					<button type="submit" class="btn btn-primary mb-2">Login</button>
				</div>
				<div class="col-auto">
					<a href="register_form.php">Register for an account.</a>
				</div>
			</div>
		</form>
	</div>
</body>
</html>