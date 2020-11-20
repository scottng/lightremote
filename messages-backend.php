<?php 

require 'config/config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno) {
	// echo $mysqli->connect_errno;
	exit();
}
$mysqli->set_charset("utf-8");
$sql = "";

if(!isset($_GET['category_id']) || empty($_GET['category_id'])) {
	$sql = "SELECT messages.content, users.email
		FROM messages
		JOIN users
			ON messages.users_id = users.id;";
} else {
	$category_id = $_GET['category_id'];

	$sql = "SELECT messages.content, users.email
		FROM messages
		JOIN users
			ON messages.users_id = users.id
		WHERE messages.categories_id =" . $category_id . ";";
}

$results = $mysqli->query($sql);
if(!$results) {
	// echo $mysqli->error;
	exit();
}

$rows = mysqli_fetch_all($results, MYSQLI_ASSOC);

echo json_encode($rows);

$mysqli->close();


 ?>