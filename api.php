<?php

require 'config/config.php';

$access_token = get_user_access_token()[0];
$whitelist_identifier = whitelist($access_token);
update_user_whitelist_identifier($whitelist_identifier);

// echo get_room_card_info(get_all_rooms($access_token, $whitelist_identifier));
echo get_all_rooms($access_token, $whitelist_identifier);

// If access_token is null return false
// Else return true	
function did_user_connect_hue() {

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

	// return access token
	return $results->fetch_row();
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

	return $whitelist_identifier;
}

// Update user's whitelist identifier in database
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

// Return user's whitelist identifier in database
function get_user_whitelist_identifier() {
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

function turn_off($access_token, $whitelist_identifier, $id) {
	$url =  "https://api.meethue.com/bridge/" . $whitelist_identifier . "/groups/" . $id . "/action";

	$data_PUT = array(
		"on" => false
	);

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => json_encode($data_PUT),
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $access_token, 'Content-Type: application/json')
	));

	curl_exec($curl);
}

 ?>