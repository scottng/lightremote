<!DOCTYPE html>
<html>
<head>
	<title>Rooms</title>

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
				<li class="nav-item active">
					<a class="nav-link" href="settings.php">Settings<span class="sr-only">(current)</span></a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="container text-white">

		<div class="row m-2 p-2">
			<h1>Settings</h1>
		</div>

		<div class="row m-2 p-2">
			<div class="col-6">
				<form id="form-update-email" action="update_email_confirmation.php" method="POST">
					<h4>Update email address</h4>
					<input type="email" class="form-control mb-2" id="email" name="email" placeholder="New email address">
					<button type="submit" class="btn btn-dark mb-2" action="#">Update email address</button>
				</form>
			</div>
		</div>

		<div class="row m-2 p-2">
			<div class="col-6">
				<form id="form-update-password" action="update_password_confirmation.php" method="POST">
					<h4>Update password</h4>
					<input type="password" class="form-control mb-2" id="password" name="password" placeholder="New password">
					<button type="submit" class="btn btn-dark mb-2" action="#">Update password</button>
				</form>
			</div>
		</div>

		<div class="row m-2 p-2">
			<div class="col-6">
				<form action="signout.php" method="POST">
					<button type="submit" class="btn btn-primary mb-2">Sign out</button>
				</form>
				<form id="form-delete-account" action="delete_account_confirmation.php" method="POST">
					<button type="submit" class="btn btn-danger mb-2">Delete my account</button>
				</form>
			</div>
		</div>
	</div>

	<script>
		
		document.querySelector('#form-update-email').onsubmit = function(event) {
			if(document.querySelector('#email').value.trim().length == 0) {
				document.querySelector('#email').classList.add('is-invalid');
				console.log('invalid');
			} else {
				alert(`Are you sure you want to change your email to ${document.querySelector('#email').value.trim()}?`);
				document.querySelector('#email').classList.remove('is-invalid');
			}
		}

		document.querySelector('#form-update-password').onsubmit = function(event) {
			if(document.querySelector('#password').value.trim().length == 0) {
				document.querySelector('#password').classList.add('is-invalid');
			} else {
				alert("Are you sure you want to change your password?");
				document.querySelector('#password').classList.remove('is-invalid');
			}
		}

		document.querySelector('#form-delete-account').onsubmit = function(event) {
			alert("Are you sure you want to delete your account?");
		}
	</script>

</body>


</html>