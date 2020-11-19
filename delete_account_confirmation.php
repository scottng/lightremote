<?php 

require 'config/config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno) {
	exit();
}

// Delete all records of user
$sql = "DELETE FROM users WHERE email='" . $_SESSION['email'] . "';";

$results = $mysqli->query($sql);
if(!$results) {
	$error = $mysqli->error;
	echo $error;
	exit();
}

// Sign out
session_start();
session_destroy();

 ?>

 <!DOCTYPE html>
<html>
<head>
	<title>Delete Account Confirmation</title>

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
				<li class="nav-item">
					<a class="nav-link" href="register_form.php">Register</a>
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
						<div class="text-success">Account was successfully deleted.</div>
					<?php endif; ?>
			</div> 
		</div>

	</div> 



</body>
</html>