<?php 
require 'config/config.php';

if(!isset($_GET['code']) || empty($_GET['code'])) {
    echo "No code provided. Your redirect uri is printed below.";
    echo "<hr />";
    echo 'http://'. $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
    exit(0);
}

$code = $_GET['code'];

$data = array(
    "grant_type" => "authorization_code",
    "code" => $code
);
$url = "https://api.meethue.com/oauth2/token";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($data),
    CURLOPT_HTTPHEADER => array(
        "Authorization: Basic " . base64_encode($client_id . ":" . $client_secret)
    )
));

$response = curl_exec($curl);
$response = json_decode($response, true);

// Set session variables
$_SESSION['access_token'] = $response['access_token'];
$_SESSION['access_token_expires_in'] = $response['access_token_expires_in'];
$_SESSION['refresh_token'] = $response['refresh_token'];
$_SESSION['refresh_token_expires_in'] = $response['refresh_token_expires_in'];
$_SESSION['token_type'] = $response['token_type'];

// Add tokens to SQL database
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno) {
    echo $mysqli->connect_errno;
    exit();
}
$sql = "UPDATE users SET access_token='" . $response['access_token'] . "', 
        access_token_expires_in=" . $response['access_token_expires_in'] . ",
        refresh_token='" . $response['refresh_token'] . "',
        refresh_token_expires_in=". $response['refresh_token_expires_in'] . ",
        token_type='" . $response['token_type'] . "' 
        WHERE email='" . $_SESSION["email"] . "';";
$results = $mysqli->query($sql);
if(!$results) {
    echo $mysqli->error;
    exit();
}

$mysqli->close(); 

header("Location: rooms.php");

 ?>