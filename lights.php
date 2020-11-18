<!DOCTYPE html>
<html>
<head>
	<title>Lights</title>

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
					<a class="nav-link" href="rooms.php">Rooms</a>
				</li>
				<li class="nav-item active">
					<a class="nav-link" href="#">Lights<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="settings.php">Settings</a>
				</li>
			</ul>
		</div>
	</nav>

	

	<div class="container">

		<div class="row m-2 p-2">
			<div class="text-white">
				<a href="rooms.php" class="btn btn-primary">Back</a>
				<h1>Lights</h1>
			</div>
		</div>

		<div class="row m-2 p-2">
			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#fff6db;">
					<div class="card-body">
						<h5 class="card-title">Hue color lamp 1</h5>
						<input type="color" value="#fff6db" class="colorPicker">
						<a href="#" class="btn btn-light float-right">ON</a>
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="100">
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#222222;">
					<div class="card-body text-white">
						<h5 class="card-title">Hue color lamp 2</h5>
						<input type="color" value="#222222" class="colorPicker">
						<a href="#" class="btn btn-dark float-right">OFF</a>
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="0">
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#222222;">
					<div class="card-body text-white">
						<h5 class="card-title">Hue color lamp 3</h5>
						<input type="color" value="#222222" class="colorPicker">
						<a href="#" class="btn btn-dark float-right">OFF</a>
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="0">
						</div>
					</div>
				</div>
			</div>

			
		</div>

		<div class="row m-2 p-2">

			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#222222;">
					<div class="card-body text-white">
						<h5 class="card-title">Hue color lamp 4</h5>
						<input type="color" value="#222222" class="colorPicker">
						<a href="#" class="btn btn-dark float-right">OFF</a>
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="0">
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#222222;">
					<div class="card-body text-white">
						<h5 class="card-title">Hue color lamp 5</h5>
						<input type="color" value="#222222" class="colorPicker">
						<a href="#" class="btn btn-dark float-right">OFF</a>
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="0">
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#222222;">
					<div class="card-body text-white">
						<h5 class="card-title">Hue color lamp 6</h5>
						<input type="color" value="#222222" class="colorPicker">
						<a href="#" class="btn btn-dark float-right">OFF</a>
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="0">
						</div>
					</div>
				</div>
			</div>

			
		</div>
	</div>

</body>


</html>