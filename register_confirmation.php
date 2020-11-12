<?php

require 'config/config.php';

if ( !isset($_POST['email']) || empty($_POST['email'])
	|| !isset($_POST['password']) || empty($_POST['password']) ) {
	$error = "Please fill out all required fields.";
} else {
	// Store this user into the database
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		// echo $mysqli->connect_errno;
		exit();
	}

	// Check if email address is already taken
	$sql_registered = "SELECT * FROM users WHERE email = '" . $_POST["email"] . "';";

	$results_registered = $mysqli->query($sql_registered);
	if(!$results_registered) {
		// echo $mysqli->error;
		exit();
	}
	// var_dump($results_registered);

	if($results_registered->num_rows > 0) {
		$error = "Email is taken. Choose another.";
	} else {
		// Hash the password
		$password = hash("sha256", $_POST["password"]);

		// Insert new user into users table
		$sql = "INSERT INTO users (email, password) VALUES('" 
		. $_POST["email"] . "','" . $password . "'); ";

		// echo "<hr>" . $sql . "<hr>";

		$results = $mysqli->query($sql);
		if(!$results) {
			echo $mysqli->error;
			exit();
		}
	}

	$mysqli->close();
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Register Confirmation</title>

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
					<a class="nav-link" href="login.php">Login</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="register_form.php">Register<span class="sr-only">(current)</span></a>
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
						<div class="text-success"><?php echo $_POST['email']; ?> was successfully registered.</div>
					<?php endif; ?>
			</div> 
		</div>

		<div class="row mt-4 mb-4">
			<div class="col-12">
				<a href="login.php" role="button" class="btn btn-primary">Login</a>
				<a href="<?php echo $_SERVER['HTTP_REFERER']; ?>" role="button" class="btn btn-light">Back</a>
			</div>
		</div>

	</div> 



</body>
</html>