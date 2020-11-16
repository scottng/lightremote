<?php 
require 'config/config.php';

?>

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
	<script src="card.js"></script>
	<script>
		function ajaxGet(endpointUrl, returnFunction){
			console.log("ajaxGet()");
			var xhr = new XMLHttpRequest();
			xhr.open('GET', endpointUrl, true);
			xhr.onreadystatechange = function(){
				console.log("state change ajaxGet");
				if (xhr.readyState == XMLHttpRequest.DONE) {
					if (xhr.status == 200) {
						// When ajax call is complete, call this function, pass a string with the response
						returnFunction( xhr.responseText );
					} else {
						alert('AJAX Error.');
						console.log(xhr.status);
					}
				}
			}
			xhr.send();
		};

		function ajaxPost(endpointUrl, postData, returnFunction){
			console.log("ajaxPost()");
			var xhr = new XMLHttpRequest();
			xhr.open('POST', endpointUrl, true);
			xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xhr.onreadystatechange = function(){
				console.log("state change ajaxPost");
				if (xhr.readyState == XMLHttpRequest.DONE) {
					if (xhr.status == 200) {
						returnFunction( xhr.responseText );
					} else {
						alert('AJAX Error.');
						console.log(xhr.status);
					}
				}
			}
			xhr.send(postData);
		};
	</script>

	<!-- Navigation -->
	<nav class="navbar navbar-expand-md navbar-dark bg-dark">
		<a class="navbar-brand" href="#">LightRemote</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="rooms.php">Rooms<span class="sr-only">(current)</span></a>
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
				<h1>Rooms</h1>
			</div>
		</div>

		<!-- Hue OAuth -->
		<?php 
			$scope = [];

			$authorize_url_params = array(
				"clientid" => $client_id,
				"appid" => $appid,
				"deviceid" => $deviceid,
				"devicename" => $devicename,
				"state" => $state,
				"response_type" => "code"
			);

			$authorize_url = "https://api.meethue.com/oauth2/auth?" . http_build_query($authorize_url_params);
		 ?>

		<div class="row m-2 p2">
			<a class="btn btn-primary" href="<?php echo $authorize_url; ?>" role="button">Connect with Hue</a>
		</div>
		<!-- End Hue OAuth -->

		<!-- AJAX -> PHP -->
		<div class="row m-2 p2">
			<a class="btn btn-primary" id="btn-get-rooms">Get rooms</a>
		</div>

		<script>
			var btn_get_rooms = document.querySelector("#btn-get-rooms");

			btn_get_rooms.addEventListener("click", ajaxGet("api.php", function(results){
				console.log("btn_get_rooms pressed");
				console.log(results);
			}));
		</script>

		<!-- AJAX -> PHP -->


		<div class="row m-2 p-2">
			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#fff6db;">
					<div class="card-body">
						<a href="lights.php"><h5 class="card-title">Living Room</h5></a>
						<p class="card-text">All lights are on.</p>
						<input type="color" value="#fff6db" id="colorLivingRoom" class="colorPicker">
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
				<div class="card" style="background:#eb5234;">
					<div class="card-body">
						<a href="lights.php"><h5 class="card-title">Kitchen</h5></a>
						<p class="card-text">5 lights are on.</p>
						<input type="color" value="#eb5234" id="colorKitchen" class="colorPicker">
						<a href="#" class="btn btn-light float-right">ON</a>
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="67">
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#ebae34;">
					<div class="card-body">
						<a href="lights.php"><h5 class="card-title">Master Bedroom</h5></a>
						<p class="card-text">2 lights are on.</p>
						<input type="color" value="#ebae34" id="colorMasterBedroom" class="colorPicker">
						<a href="#" class="btn btn-light float-right">ON</a>
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="100">
						</div>
					</div>
				</div>
			</div>

			
		</div>

		<div class="row m-2 p-2">

			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#222222;">
					<div class="card-body text-white">
						<a href="lights.php"><h5 class="card-title">Master Bathroom</h5></a>
						<p class="card-text">All lights are off.</p>
						<input type="color" value="#222222" id="colorMasterBathroom" class="colorPicker">
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
						<a href="lights.php"><h5 class="card-title">Guest Bedroom</h5></a>
						<p class="card-text">All lights are off.</p>
						<input type="color" value="#222222" id="colorGuestBedroom" class="colorPicker">
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