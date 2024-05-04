<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Access-Control-Allow-Origin, Accept");

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$database = "surveil";

$db = new mysqli($servername, $dbusername, $dbpassword, $database);

if ($db->connect_error)
	die("Connection failed");

$state = 0;
$msg = "Login Failed!";
$data = [];

$json = file_get_contents('php://input');
// Converts json data into a PHP object
$jsonData = json_decode($json, true);

$jsonUsername = $jsonData['username'];
$jsonPassword = $jsonData['password'];

if (isset($jsonUsername) && isset($jsonPassword)) {
	$msg = 'Post is set!';
	$username = $jsonUsername;
	$password = $jsonPassword;

	$authQuery = $db->query("SELECT * FROM users WHERE username = '$username' AND status = 1");

	if (mysqli_num_rows($authQuery) > 0) {
		$collection = mysqli_fetch_assoc($authQuery);
		if ($password == $collection['password']) {
			$msg = "Login successful!";
			$data = array('uid' => $collection['id'], 'firstame' => $collection['firstname'], 'lastname' => $collection['lastname']);
			$state = 1;
		} else {
			$msg = 'Invalid password!';
		}
	} else {
		$msg = "Invalid username!";
	}
}


echo json_encode(array('state' => $state, 'msg' => $msg, 'data' => $data));
