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
	offButtons.forEach(function(btn) {
		btn.addEventListener("click", offButtonClickListener);
	});
}

function offButtonClickListener(){
	this.removeEventListener("click", offButtonClickListener);
	this.addEventListener("click", onButtonClickListener);

	this.classList.remove("btn-dark");
	this.classList.add("btn-light");
	this.innerHTML = "ON";

	message = this.parentNode.querySelector(".message");
	message.innerHTML = "All lights are on.";

	card = this.parentNode.parentNode;
	colorPicker = this.parentNode.querySelector(".colorPicker");
	card.style.backgroundColor = colorPicker.value;

	cardBody = this.parentNode;
	cardBody.classList.remove("text-white");

	// AJAX call
	let id = this.parentNode.parentNode.dataset.id;
	let postData = `function=room_toggle&id=${id}&on=true`;
	ajaxPost("api.php", postData, function(results) {
		console.log(results);
	});
}

function onButtonClickListener() {
	this.removeEventListener("click", onButtonClickListener);
	this.addEventListener("click", offButtonClickListener);

	this.classList.remove("btn-light");
	this.classList.add("btn-dark");
	this.innerHTML = "OFF";

	message = this.parentNode.querySelector(".message");
	message.innerHTML = "All lights are off.";

	card = this.parentNode.parentNode;
	card.style.backgroundColor = "#222222";
	
	cardBody = this.parentNode;
	cardBody.classList.add("text-white");

	// AJAX call
	let id = this.parentNode.parentNode.dataset.id;
	let postData = `function=room_toggle&id=${id}&on=false`;
	ajaxPost("api.php", postData, function(results) {
		console.log(results);
	});
}

function colorPickerListener() {
	// Front end changes
	card = this.parentNode.parentNode;
	card.style.backgroundColor = event.target.value;

	// AJAX call
	let hex = event.target.value;
	hex = hex.substring(1);
	let xy = hex_to_cie(hex);
	console.log("XY: ");
	console.log(xy);
	let id = this.parentNode.parentNode.dataset.id;
	let postData = `function=room_change_color&id=${id}&xy=${xy}`;
	ajaxPost("api.php", postData, function(results) {
		console.log(results);
	});
}