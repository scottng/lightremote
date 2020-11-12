<?php
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Signed out</title>

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

	<div class="d-flex justify-content-center align-items-center m-5 text-white">
		<h4>
			You are now signed out out.
		</h4>
	</div>

	<div class="d-flex justify-content-center align-items-center m-5 text-white">
		<form action="login.php" method="POST">
			<button type="submit" class="btn btn-dark mb-2">Login</button>
		</form>
	</div>


</body>
</html>