<?php 

require 'config/config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno) {
	// echo $mysqli->connect_errno;
	exit();
}

$sql_categories = "SELECT * FROM categories;";
$results_categories = $mysqli->query($sql_categories);
$send_categories = $mysqli->query($sql_categories);
if( $results_categories == false) {
	// echo $mysqli->error;
	exit();
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Message Board</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
					<a class="nav-link" href="settings.php">Messages<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="settings.php">Settings</a>
				</li>
			</ul>
		</div>
	</nav>

	<div class="container text-white">

		<div class="row m-2 p-2">
			<h1>Message Board</h1>
		</div>

		<div class="row m-2 p-2">
			<div class="col-6">
				<h5>Filter by category</h5>
				<select name="category_id" id="category-id" class="form-control">
					<option value="" selected>-- Select One --</option>
					<!-- PHP Output Here -->
					<?php while( $row = $results_categories->fetch_assoc() ): ?>
						<option value="<?php echo $row['id']; ?>">
							<?php echo $row['name']; ?>
						</option>
					<?php endwhile; ?>
				</select>
			</div>
		</div>

		<div id="message_div" class="overflow-auto" style="max-height: 400px;">
		</div>


		<div class="row m-2 p-2">
			<div class="col-6">
				<form id="form-send-message" action="#" method="POST" class="form-inline">
					<input type="text" class="form-control mb-2" id="message-text" name="message-text" placeholder="Write a message...">

					<button type="submit" class="btn btn-primary mb-2" action="#">Send to</button>

					<select name="send_category_id" id="send-category-id" class="form-control">
						<!-- PHP Output Here -->
						<?php $row = $send_categories->fetch_assoc(); ?>
							<option value="<?php echo $row['id']; ?>" selected>
								<?php echo $row['name']; ?>
							</option>

						<?php while( $row = $send_categories->fetch_assoc() ): ?>
							<option value="<?php echo $row['id']; ?>">
								<?php echo $row['name']; ?>
							</option>
						<?php endwhile; ?>
					</select>

				</form>
			</div>
		</div>

	</div>

	<script>
		// Preload all messages
		$.ajax({
				type: "GET",
				url: "messages-backend.php",
				success: function(data) {
					// console.log(data);
					data = JSON.parse(data);

					let message_div = document.querySelector('#message_div');

					newInnerHTML = "";

					for(i=0; i<data.length; i++) {
						newInnerHTML += `<div class="row m-2 p-2"><div class="col-6"><b>`;
						let sender = data[i].email.split("@")[0];
						newInnerHTML += sender;
						newInnerHTML += `</b>:  `;
						let content = data[i].content;
						newInnerHTML += content;
						newInnerHTML += `</div></div>`;
					}

					message_div.innerHTML = newInnerHTML;
				}
		});

		// Load filtered messages
		let selectedCategory = 0;
		let selector = document.querySelector("#category-id");
		selector.addEventListener("change", function(){
			selectedCategory = selector.value;

			// Make ajax call to messages-backend.php with category=?
			// Use returned results to fill messages
			// Also do this at the beginning with category=0
			getData = `category_id=${selectedCategory}`;
			$.ajax({
				type: "GET",
				url: "messages-backend.php",
				data: getData,
				success: function(data) {
					// console.log(data);
					data = JSON.parse(data);

					let message_div = document.querySelector('#message_div');

					newInnerHTML = "";

					for(i=0; i<data.length; i++) {
						newInnerHTML += `<div class="row m-2 p-2"><div class="col-6"><b>`;
						let sender = data[i].email.split("@")[0];
						newInnerHTML += sender;
						newInnerHTML += `</b>:  `;
						let content = data[i].content;
						newInnerHTML += content;
						newInnerHTML += `</div></div>`;
					}

					message_div.innerHTML = newInnerHTML;
				}
			});
		});

		// Send a message
		let formSendMessage = document.querySelector("#form-send-message");
		formSendMessage.addEventListener("submit", function() {
			event.preventDefault();

			let selectedCategory = document.querySelector("#send-category-id").value;
			let content = document.querySelector("#message-text").value;

			let postData = `content=${content}&category_id=${selectedCategory}`;
			$.ajax({
				type: "POST",
				data: postData,
				url: "messages-backend-send.php",
				success: function(data) {
					// console.log(data);
					let message_div = document.querySelector('#message_div');

					newInnerHTML = "";

					for(i=0; i<data.length; i++) {
						newInnerHTML += `<div class="row m-2 p-2"><div class="col-6"><b>`;
						let sender = data[i].email.split("@")[0];
						newInnerHTML += sender;
						newInnerHTML += `</b>:  `;
						let content = data[i].content;
						newInnerHTML += content;
						newInnerHTML += `</div></div>`;
					}

					message_div.innerHTML = newInnerHTML;

					$.ajax({
				type: "GET",
				url: "messages-backend.php",
				success: function(data) {
					// console.log(data);
					data = JSON.parse(data);

					let message_div = document.querySelector('#message_div');

					newInnerHTML = "";

					for(i=0; i<data.length; i++) {
						newInnerHTML += `<div class="row m-2 p-2"><div class="col-6"><b>`;
						let sender = data[i].email.split("@")[0];
						newInnerHTML += sender;
						newInnerHTML += `</b>:  `;
						let content = data[i].content;
						newInnerHTML += content;
						newInnerHTML += `</div></div>`;
					}

					message_div.innerHTML = newInnerHTML;
				}
		});
				}
			});

		});
	</script>

</body>


</html>