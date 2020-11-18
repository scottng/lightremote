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
	$id = $_POST["id"];
	$type = $_POST["type"];

	// echo "ID";
	// echo $id;
	// echo "TYPE";
	// echo $type;

	if($_POST["function"] == "toggle") {
		$on = $_POST["on"];
		toggle($access_token, $whitelist_identifier, $id, $type, $on);
	} else if($_POST["function"] == "change_color") {
		$xy = $_POST["xy"];
		change_color($access_token, $whitelist_identifier, $id, $type, $xy);
	} else if($_POST["function"] == "set_brightness") {
		$brightness = $_POST["brightness"];
		set_brightness($access_token, $whitelist_identifier, $id, $type, $brightness);
	}
}

// Get status of rooms
if(isset($_GET["type"])) {
	if($_GET["type"] == "room") echo get_all($access_token, $whitelist_identifier, "room");
	else echo get_all($access_token, $whitelist_identifier, "light");
}

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

// Return all lights
function get_all($access_token, $whitelist_identifier, $type) {
	$url = "";
	if($type == "light") {
		$url = "https://api.meethue.com/bridge/" . $whitelist_identifier . "/lights";
	} else {
		$url = "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups";
	}

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
	));
	$response_GET = curl_exec($curl);

	if($type == "light") {
		return $response_GET;
	} else {
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
}

function toggle($access_token, $whitelist_identifier, $id, $type, $on) {
	echo "toggle ";
	echo $id;

	$url = "";
	if($type == "room") {
		$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups/" . $id . "/action";
	} else {
		$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/lights/" . $id . "/state";
	}

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

function change_color($access_token, $whitelist_identifier, $id, $type, $xy) {
	$url = "";
	if($type == "room") {
		$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups/" . $id . "/action";
	} else {
		$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/lights/" . $id . "/state";
	}

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

function set_brightness($access_token, $whitelist_identifier, $id, $type, $brightness) {
	$url = "";
	if($type == "room") {
		$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups/" . $id . "/action";
	} else {
		$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/lights/" . $id . "/state";
	}

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