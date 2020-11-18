<?php

require 'config/config.php';

// Check if access_token is set
if(isset($_SESSION["access_token"])) $access_token = $_SESSION["access_token"];
else $access_token = get_user_access_token();

// Check if whitelist_identifier is set 
if(isset($_SESSION["whitelist_identifier"])) $whitelist_identifier = $_SESSION["whitelist_identifier"];
else $whitelist_identifier = whitelist($access_token);

// Check for command
if(isset($_POST["function"])) {
	if($_POST["function"] == "toggle") {
		$id = $_POST["id"];
		$on = $_POST["on"];
		toggle($access_token, $whitelist_identifier, $id, $on);
	} else if($_POST["function"] == "change_color") {
		$id = $_POST["id"];
		$xy = $_POST["xy"];
		change_color($access_token, $whitelist_identifier, $id, $xy);
	} else if($_POST["function"] == "set_brightness") {
		$id = $_POST["id"];
		$brightness = $_POST["brightness"];
		set_brightness($access_token, $whitelist_identifier, $id, $brightness);
	}
}

echo get_all_rooms($access_token, $whitelist_identifier);

// Get the user's access_token from database
// Return access_token
function get_user_access_token() {

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_errno;
		exit();
	}

	$sql = "SELECT access_token FROM users WHERE email='" . $_SESSION['email'] . "';";

	$results = $mysqli->query($sql);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	$mysqli->close();

	$access_token = $results->fetch_row()[0];

	$_SESSION["access_token"] = $access_token;

	// return access token
	return $access_token;
}

// Create a whitelist entry/username remotely
// Return whitelist identifier
function whitelist($access_token) {

	// First step: PUT to bridge config
	$url_PUT = "https://api.meethue.com/bridge/0/config";
	$data_PUT = array(
		"linkbutton" => true
	);
	$curl_PUT = curl_init();
	curl_setopt_array($curl_PUT, array(
          CURLOPT_URL => $url_PUT,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => "PUT",
          CURLOPT_POSTFIELDS => json_encode($data_PUT),
          CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
      ));
	$response_PUT = curl_exec($curl_PUT);
	// echo "RESPONSE PUT: ";
	// echo json_encode($response_PUT);

	// Second step: POST to bridge
	$url_POST = "https://api.meethue.com/bridge/";
	$data_POST = array(
		"devicetype" => "lightremote"
	);
	$curl_POST = curl_init();
	curl_setopt_array($curl_POST, array(
		CURLOPT_URL => $url_POST,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => json_encode($data_POST),
         CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
	));
	$response_POST = curl_exec($curl_POST);
	// echo "RESPONSE POST:";
	// echo json_encode($response_POST);

	$response_POST = json_decode($response_POST);

	$whitelist_identifier = $response_POST[0]->success->username;

	// Update in database
	update_user_whitelist_identifier($whitelist_identifier);

	$_SESSION["whitelist_identifier"] = $whitelist_identifier;

	return $whitelist_identifier;
}

// Update user's whitelist identifier in database
// Called from whitelist()
// No return value
function update_user_whitelist_identifier($whitelist_identifier) {
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if($mysqli->connect_errno) {
		echo $mysqli->connect_errno;
		exit();
	}

	$sql = "UPDATE users SET whitelist_identifier ='" . $whitelist_identifier . "' WHERE email='" . $_SESSION['email'] . "';" ;

	$results = $mysqli->query($sql);
	if(!$results) {
		echo $mysqli->error;
		exit();
	}

	$mysqli->close();
}

// // Return user's whitelist identifier in database
// function get_user_whitelist_identifier() {
// 	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
// 	if($mysqli->connect_errno) {
// 		echo $mysqli->connect_errno;
// 		exit();
// 	}

// 	$sql = "UPDATE users SET whitelist_identifier ='" . $whitelist_identifier . "' WHERE email='" . $_SESSION['email'] . "';" ;

// 	$results = $mysqli->query($sql);
// 	if(!$results) {
// 		echo $mysqli->error;
// 		exit();
// 	}

// 	$mysqli->close();
// }

// Return all lights
function get_all_lights($access_token, $whitelist_identifier) {
	$url = "https://api.meethue.com/bridge/" . $whitelist_identifier . "/lights";

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
	));
	$response_GET = curl_exec($curl);

	return $response_GET;
}

// Return all rooms
function get_all_rooms($access_token, $whitelist_identifier) {
	$url = "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups";

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
	));
	$response_GET = curl_exec($curl);

	$response_GET = json_decode($response_GET);

	// Filter for only "type": "Room"
	$rooms = array();
	foreach ($response_GET as $light_group) {
		if($light_group->type == "Room") {
			array_push($rooms, $light_group);
		}
	}

	return json_encode($rooms);
}

function toggle($access_token, $whitelist_identifier, $id, $on) {
	$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups/" . $id . "/action";

	if($on == "true") $on = true;
	else $on = false;

	$data_PUT = array(
		"on" => $on
	);

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => json_encode($data_PUT),
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
	));

	$response = curl_exec($curl);

	return $response;
}

function change_color($access_token, $whitelist_identifier, $id, $xy) {
	$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups/" . $id . "/action";

	$explode = explode(",", $xy);

	$data_PUT = array(
		"xy" => array(floatval($explode[0]), floatval($explode[1]))
	);

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => json_encode($data_PUT),
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
	));

	$response = curl_exec($curl);

	return $response;
}

function set_brightness($access_token, $whitelist_identifier, $id, $brightness) {
	$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups/" . $id . "/action";

	echo "set room brightness";

	$data_PUT = array(
		"bri" => intval($brightness)
	);

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => json_encode($data_PUT),
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
	));

	$response = curl_exec($curl);

	return $response;
}



 ?>