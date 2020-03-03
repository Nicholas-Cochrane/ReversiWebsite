<?php

$servername = "localhost"; // default server name
$sqlusername = "testUser"; // user name that you created
$sqlpassword = "badpassword%%"; // password that you created
$dbname = "reversi";

$array_str = (isset($_POST['array'])) ? $_POST['array'] : "Error";
if($array_str != "Error"){
$object = json_decode($array_str,true);




$conn = new mysqli($servername, $sqlusername, $sqlpassword, $dbname);
// Check connection
if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// check if user is logged in
session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}else{
  die(json_encode(["error" => "Not Logged In"]));
}

$sqlString = "SELECT * FROM `games` WHERE `id` = '{$object["id"]}';";

$result = $conn->query($sqlString);
$output = [];
if($result->num_rows == 1){  
  $row = $result->fetch_array(MYSQLI_NUM);
  if($row[0] != $username){ 
    die(json_encode(["error" => "User does not have access to that game"]));
  }
  //figure out who's turn it is
  
  $output = ['gameBoard' => $row[2],'duration' => $row[3],'complete' => $row[5],'ai' => $row[8], 'turn' => $row[4]];
}else if($result->num_rows == 0){
  die(json_encode(["error" => "Game does not exist"]));
}else{
  die(json_encode(["error" => "Game Table Corrupted"]));
}

echo json_encode($output);

$conn->close();
}else{
  die(json_encode(["error" => "Could Not Retreive Form Info From Array." ]));
}
?>
