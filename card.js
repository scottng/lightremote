function initCardJS() {
	// Change card background color when color changed
	colorPickers = document.querySelectorAll(".colorPicker");
	colorPickers.forEach(function(colorPicker){
		colorPicker.addEventListener("change", function(event) {
			card = this.parentNode.parentNode;
			card.style.backgroundColor = event.target.value;
		});
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
}