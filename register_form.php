<!DOCTYPE html>
<html>
<head>
	<title>Register</title>

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

	<div class="d-flex justify-content-center align-items-center m-5 text-white">
		<h4>
			Register to connect with Philips Hue.
		</h4>
	</div>

	<div class="d-flex text-white justify-content-center align-items-center m-5">

		<form action="register_confirmation.php" method="POST">

			<div class="form-row align-items-center">
				<label for="email" class="col-4 col-form-label">Email: <span class="text-danger">*</span></label>
				<div class="col-6">
					<input type="email" class="form-control" id="email" name="email">
					<small id="email-error" class="invalid-feedback">Email is required.</small>
				</div>
			</div>

			<div class="form-row align-items-center">
				<label for="password" class="col-4 col-form-label">Password: <span class="text-danger">*</span></label>
				<div class="col-6">
					<input type="password" class="form-control" id="password" name="password">
					<small id="password-error" class="invalid-feedback">Password is required.</small>
				</div>
			</div> 

			<div class="form-row align-items-center">
				<label for="password" class="col-4 col-form-label">Confirm Password: <span class="text-danger">*</span></label>
				<div class="col-6">
					<input type="password" class="form-control" id="confirm-password" name="confirm-password">
					<small id="confirm-password-error" class="invalid-feedback">Passwords do not match.</small>
				</div>
			</div> 

			<div class="form-row">
				<div class="col-4">
					<span class="text-danger font-italic">* Required</span>
				</div>
			</div> 

			<div class="form-row">
				<div class="col-auto">
					<button type="submit" class="btn btn-primary">Register</button>
				</div>
				<div class="col-auto">
					<a href="login.php">Cancel</a>
				</div>
			</div>

		</form>
	</div> <!-- .container -->

	<script>
		document.querySelector('form').onsubmit = function(){

			if(document.querySelector('#email').value.trim().length == 0) {
				document.querySelector('#email').classList.add('is-invalid');
			} else {
				document.querySelector('#email').classList.remove('is-invalid');
			}

			if(document.querySelector('#password').value.trim().length == 0) {
				document.querySelector('#password').classList.add('is-invalid');
			} else {
				document.querySelector('#password').classList.remove('is-invalid');
			}

			if(document.querySelector('#password').value !=
				document.querySelector('#confirm-password').value) {
				document.querySelector('#confirm-password').classList.add('is-invalid');
			} else {
				document.querySelector('#confirm-password').classList.remove('is-invalid');
			}

			return ( !document.querySelectorAll('.is-invalid').length > 0 );
		}
	</script>


</body>
</html>