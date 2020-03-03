<?php

$servername = "localhost"; // default server name
$sqlusername = "testUser"; // user name that you created
$sqlpassword = "badpassword%%"; // password that you created
$dbname = "reversi";

$typeStr = (isset($_POST['array'])) ? $_POST['array'] : "Error";
if($typeStr != "Error"){
$object = json_decode($typeStr,true);

$conn = new mysqli($servername, $sqlusername, $sqlpassword, $dbname);
// Check connection
if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// gameboardSize
// 4x4 = 73
// 6x6 =157
// 8x8 = 273
$cond = ""; // select all
if($object["type"] == "8"){
  $cond = "AND LENGTH(gameBoard) > 200";
}else if($object["type"] == "6"){
  $cond = "AND LENGTH(gameBoard) > 100 AND LENGTH(gameBoard) < 200";
}else if($object["type"] == "4"){
  $cond = "AND LENGTH(gameBoard) < 100 ";
}

$order = "DESC";
if($object["order"] == true){
  $order = "ASC";
}

$sqlString = "SELECT login, blackScore, duration FROM `games` WHERE `winner` = 'b' $cond ORDER BY blackScore $order;";

if($object["type"] == "time"){ // chage the query if getting the time
  $timeOrder = "ASC"; // for time asc and desc are opposite of for int as we want the shortest time to be best
  if($object["order"] == true){
    $timeOrder = "DESC";
  }
  $sqlString = "SELECT login, blackScore, duration FROM `games` WHERE `winner` = 'b' ORDER BY duration $timeOrder;";
}

$result = $conn->query($sqlString);
$output = [];
$counter = 0;
while($row = $result->fetch_array(MYSQLI_NUM) ){
  $temp = ['login' => $row[0],'score' => $row[1],'duration' => $row[2]];
  array_push($output,$temp);
  $counter++;
  if($counter >= 10){
    break;
  }
}


echo json_encode($output);

$conn->close();

}else{
  die(json_encode(["error" => "Could Not Retreive Form Info From Array." ]));
}
?>
