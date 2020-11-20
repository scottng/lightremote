<?php 

require 'config/config.php';

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli->connect_errno) {
	// echo $mysqli->connect_errno;
	exit();
}
$mysqli->set_charset("utf-8");
$sql = "";

$category_id = $_POST['category_id'];
$content = $_POST['content'];
$sender = $_SESSION['email'];

// Get user id
$sql_get_user_id = "SELECT users.id FROM users
	WHERE email='" . $sender . "';";
$user_id_result = $mysqli->query($sql_get_user_id);
$user_id = intval($user_id_result->fetch_row()[0]);


// Insert to db
$mysqli_insert = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($mysqli_insert->connect_errno) {
	// echo $mysqli->connect_errno;
	exit();
}
$mysqli_insert->set_charset("utf-8");

$sql = "INSERT INTO messages (content, categories_id, users_id)
	VALUES ('" . $content . "', " . $category_id . ", " . $user_id . ");";

$results = $mysqli_insert->query($sql);
if(!$results) {
	exit();
}

$mysqli->close();


 ?>