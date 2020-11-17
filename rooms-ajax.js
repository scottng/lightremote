function ajaxGet(endpointUrl, returnFunction){
	console.log("ajaxGet()");
	var xhr = new XMLHttpRequest();
	xhr.open('GET', endpointUrl, true);
	xhr.onreadystatechange = function(){
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

var btn_get_rooms = document.querySelector("#btn-get-rooms");

btn_get_rooms.addEventListener("click", ajaxGet("api.php", function(results){

	console.log(results);

	rooms = JSON.parse(results);

	// console.log("rooms");
	// console.log(rooms);
	// console.log("rooms[0]");
	// console.log(rooms[0]);
	// console.log("rooms[0].name");
	// console.log(rooms[0].name);
	// console.log("rooms[0].lights.length");
	// console.log(rooms[0].lights.length);

	console.log(rooms);


	newInnerHTML = "";

	// make cards based on the results
	newInnerHTML += `<div class="row m-2 p-2">`;
	for(i=0; i < rooms.length; i++) {
		// From JSON response
		let id = i;
		let name = rooms[i].name;
		let lights = rooms[i].lights.length;
		let state_all_on = rooms[i].state.all_on;
		let state_any_on = rooms[i].state.any_on;
		let xy = rooms[i].action.xy;
		let bri = rooms[i].action.bri;

		// Prepare data to put in card
		let message = "";
		let button = "";
		let background_color = 0;
		let text_white = "";
		let brightness = 0;
		if(state_any_on == true) {
			button = `<a href="#" class="btn btn-light float-right">ON</a>`;
			background_color = cie_to_hex(xy[0], xy[1]);
			brightness = bri * (100/254);

			if(state_all_on) message = "All lights are on.";
			else message = "Some lights are on.";
		} else {
			button = `<a href="#" class="btn btn-dark float-right">OFF</a>`;
			background_color = 222222;
			text_white = "text-white";
			brightness = 0;
			message = "All lights are off.";
		}

		let cardHTML = `
			<div class="col-lg-4 col-md-4 col-sm-12 col-12">
				<div class="card" style="background:#${background_color};" data-id=${id}>
					<div class="card-body ${text_white}">
						<a href="lights.php"><h5 class="card-title">${name}</h5></a>
						<p class="card-text message">${message}</p>
						<input type="color" value="#${cie_to_hex(xy[0], xy[1])}" class="colorPicker">
						${button}
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="100" value="${brightness}">
						</div>
					</div>
				</div>
			</div>
		`;
		newInnerHTML += cardHTML;
	}
	newInnerHTML += "</div>";

	cardContainer = document.querySelector("#card-container");
	cardContainer.innerHTML += newInnerHTML;

	initCardJS();
}));