getData = `type=room`;
$.ajax({
	type: "GET",
	url: "api.php",
	data: getData,
	success: function(data) {
		get_rooms_success(data);
	}
});

function offButtonClickListener(){
	const type = "room";

	// Update listener
	this.removeEventListener("click", offButtonClickListener);
	this.addEventListener("click", onButtonClickListener);

	// Update toggle button
	this.classList.remove("btn-dark");
	this.classList.add("btn-light");
	this.innerHTML = "ON";

	// Change message
	message = this.parentNode.querySelector(".message");
	message.innerHTML = "All lights are on.";

	// Change background color to picker color 
	card = this.parentNode.parentNode;
	colorPicker = this.parentNode.querySelector(".colorPicker");
	card.style.backgroundColor = colorPicker.value;

	// Make text dark for light background
	cardBody = this.parentNode;
	cardBody.classList.remove("text-white");

	// Put brightness slider to max
	brightnessSlider = this.parentNode.parentNode.querySelector(".custom-range");
	brightnessSlider.value = 254;

	// AJAX call
	let id = this.parentNode.parentNode.dataset.id;
	let postData = `function=toggle&id=${id}&type=${type}&on=true`;
	$.ajax({
		type: "POST",
		url: "api.php",
		data: postData,
		success: function(data) {
			// console.log(data);	
		}
	});
}

function onButtonClickListener() {
	const type = "room";

	// Update listener
	this.removeEventListener("click", onButtonClickListener);
	this.addEventListener("click", offButtonClickListener);

	// Update toggle button
	this.classList.remove("btn-light");
	this.classList.add("btn-dark");
	this.innerHTML = "OFF";

	// Change message
	message = this.parentNode.querySelector(".message");
	message.innerHTML = "All lights are off.";

	// Set background to dark
	card = this.parentNode.parentNode;
	card.style.backgroundColor = "#222222";
	
	// Make text white for dark background
	cardBody = this.parentNode;
	cardBody.classList.add("text-white");

	// Put brightness slider to 0
	brightnessSlider = this.parentNode.parentNode.querySelector(".custom-range");
	brightnessSlider.value = 0;

	// AJAX call
	let id = this.parentNode.parentNode.dataset.id;
	let postData = `function=toggle&id=${id}&type=${type}&on=false`;
	$.ajax({
		type: "POST",
		url: "api.php",
		data: postData,
		success: function(data) {
			// console.log(data);	
		}
	});
}

function colorPickerListener() {
	const type = "room";

	// Front end changes
	card = this.parentNode.parentNode;
	card.style.backgroundColor = event.target.value;

	// AJAX call
	let hex = event.target.value;
	hex = hex.substring(1);
	let xy = hex_to_cie(hex);
	let id = this.parentNode.parentNode.dataset.id;
	let postData = `function=change_color&id=${id}&type=${type}&xy=${xy}`;

	$.ajax({
		type: "POST",
		url: "api.php",
		data: postData,
		success: function(data) {
			// console.log(data);	
		}
	});
}

function sliderChangeListener() {
	const type = "room";
	// AJAX call

	// Get slider value (brightness)
	brightness = this.value;

	let id = this.parentNode.parentNode.parentNode.dataset.id;
	let postData = `function=set_brightness&id=${id}&type=${type}&brightness=${brightness}`;

	$.ajax({
		type: "POST",
		url: "api.php",
		data: postData,
		success: function(data) {
			// console.log(data);	
		}
	});
}

function initCardJS() {
	// Set color picker listeners
	colorPickers = document.querySelectorAll(".colorPicker");
	colorPickers.forEach(function(colorPicker){
		colorPicker.addEventListener("change", colorPickerListener);
	});

	// Set on/off button listeners
	onButtons = document.querySelectorAll(".btn-light");
	onButtons.forEach(function(btn){
		btn.addEventListener("click", onButtonClickListener);
	});
	offButtons = document.querySelectorAll(".btn-dark");
	offButtons.forEach(function(btn){
		btn.addEventListener("click", offButtonClickListener);
	});

	// Set brightness slider listeners
	sliders = document.querySelectorAll(".custom-range");
	sliders.forEach(function(slider){
		// console.log("set slider listener");
		slider.addEventListener("change", sliderChangeListener);
	});
}

function get_rooms_success(results) {

	// console.log(results);

	rooms = JSON.parse(results);

	// console.log("rooms");
	// console.log(rooms);
	// console.log("rooms[0]");
	// console.log(rooms[0]);
	// console.log("rooms[0].name");
	// console.log(rooms[0].name);
	// console.log("rooms[0].lights.length");
	// console.log(rooms[0].lights.length);

	// console.log(rooms);

	newInnerHTML = "";

	// make cards based on the results
	newInnerHTML += `<div class="row m-2 p-2">`;
	for(i=0; i < rooms.length; i++) {
		// From JSON response
		let id = i+1;
		let room_name = rooms[i].name;
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
		let hex = cie_to_hex(xy[0], xy[1]);

		if(state_any_on == true) {
			button = `<a href="#" class="btn btn-light float-right">ON</a>`;
			background_color = cie_to_hex(xy[0], xy[1]);
			brightness = bri;

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
						<a href="lights.php?&room-id=${id}&room-name=${room_name}"><h5 class="card-title">${room_name}</h5></a>
						<p class="card-text message">${message}</p>
						<input type="color" value="#${cie_to_hex(xy[0], xy[1])}" class="colorPicker">
						${button}
					</div>
					<div class="card-footer">
						<div class="justify-content-center">
							<input type="range" class="custom-range" id="brightness" min="0" max="254" value="${brightness}">
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
}