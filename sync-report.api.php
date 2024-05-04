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
$msg = "";
$data = [];

$json = file_get_contents('php://input');
// Converts json data into a PHP object
$jsonData = json_decode($json, true);

// $jsonUserId = $jsonData['userId'];
// $jsonData = $jsonData['data'];



if (isset($jsonData)) {







   $array = json_decode($jsonData, 1); // convert data string to php array
   $userId = $array[0]['id']; // convert data string to php array

   for ($i = 0; $i < count($array); $i++) {

      $reportId = $array[$i]['id'];
      $title = $array[$i]['title'];
      $comment = $array[$i]['comment'];
      $dateTime = $array[$i]['dateTime'];

      //check if reminder exist
      $sql_ud = "UPDATE reports SET status = 0 WHERE reportId='$reportId' AND userId='$userId'";
      $result = $db->query($sql_ud);

      $stmt = $db->prepare("INSERT INTO reports(reportId, userId, title, comment, dateTime)VALUES(?,?,?,?,?)");
      if (!$stmt) {
         $msg = (mysqli_error($db));
         die();
      }
      $stmt->bind_param("iisss", $reportId, $userId, $title, $comment, $dateTime);

      if ($stmt->execute()) {
         $state = 1;
         $msg = "success";
      } else $msg = mysqli_error($db);
   }
   $state = 1;
   $msg = "Operation successful";
}
//userID=2&data=[{"id":1,"title":"Title","note":"Note ","date":"2023-07-18","time":"15:24","repeatType":"d","regDate":"15:21","readDate":null,"status":1},{"id":2,"title":"Butterfly ","note":"Network ","date":"2023-07-18","time":"12:20","repeatType":"d","regDate":"12:16","readDate":null,"status":1},{"id":3,"title":"Standards ","note":"Regal","date":"2023-07-18","time":"16:38","repeatType":"d","regDate":"16:35","readDate":null,"status":1},{"id":4,"title":"Torah","note":"Barrah","date":"2023-07-18","time":"12:27","repeatType":"d","regDate":"12:22","readDate":null,"status":1},{"id":5,"title":"Banner","note":"Task","date":"2023-07-18","time":"15:40","repeatType":"d","regDate":"15:36","readDate":null,"status":1},{"id":6,"title":"The times","note":"Note ","date":"2023-07-18","time":"17:10","repeatType":"d","regDate":"17:07","readDate":null,"status":1}]


echo json_encode(array('state' => $state, 'msg' => $msg));
