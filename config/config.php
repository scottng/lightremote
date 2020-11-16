<?php

session_start();

define('DB_HOST', '303.itpwebdev.com');
define('DB_USER', 'scottng_db_user');
define('DB_PASS', 'uscitp2020');
define('DB_NAME', 'scottng_project_db');

$client_id = "WB6cibpWtgJMAx00WNOWEAG57EAHtRjF";
$client_secret = "BayvuQigceVfrpGx";
$redirect_uri = "http://localhost:8887/lightremote/hue-oauth.php";
$appid = "lightremote";
$deviceid = "web";
$devicename = "web";

$state = "oT7FlAVFcxPXr5nloFYj1mKEWjZz7g";

?>