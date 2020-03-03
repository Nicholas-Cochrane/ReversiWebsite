<?php

$servername = "localhost"; // default server name
$sqlusername = "testUser"; // user name that you created
$sqlpassword = "badpassword%%"; // password that you created
$dbname = "reversi";

$array_str = (isset($_POST['array'])) ? $_POST['array'] : "Error";
if($array_str != "Error"){
$object = json_decode($array_str,true); // {"gameBoard":"[["e","e","e","e"],["e","w","b","e"],["e","b","w","e"],["e","e","e","e"]]","id":"5dedd5fc32e7d","duration":"00:00:00","turn":0,"whiteScore":2,"blackScore":2,"complete":false}




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
  die(json_encode(["error" => "Error: Not Logged In"]));
}

$sqlString = "SELECT * FROM `games` WHERE `id` = '{$object["id"]}';";

$result = $conn->query($sqlString);
if($result->num_rows == 1){  
  $row = $result->fetch_array(MYSQLI_NUM);
  if($row[0] != $username){ 
    die(json_encode(["error" => "Error: User does not have access to that game"]));
  }
}else if($result->num_rows == 0){
  die(json_encode(["error" => "Error: Game does not exist"]));
}else{
  die(json_encode(["error" => "Error: Game Table Corrupted"]));
}

if($object["complete"] == "true"){
  $winner = 'w';
  if ($object["blackScore"] > $object["whiteScore"]){
    $winner = 'b';
  }else if($object["blackScore"] == $object["whiteScore"]){
    $winner = 't';
  }
  $sqlString = "UPDATE `games`SET gameBoard = '{$object["gameBoard"]}', duration = '{$object["duration"]}', whiteScore = {$object["whiteScore"]}, turn = {$object["turn"]}, blackScore = {$object["blackScore"]}, complete = {$object["complete"]}, winner = '$winner' WHERE id = '{$object["id"]}' AND turn < {$object["turn"]};";
}else{
  $sqlString = "UPDATE `games`SET gameBoard = '{$object["gameBoard"]}', duration = '{$object["duration"]}', whiteScore = {$object["whiteScore"]}, turn = {$object["turn"]}, blackScore = {$object["blackScore"]}, complete = {$object["complete"]} WHERE id = '{$object["id"]}' AND turn < {$object["turn"]};";
}

if($conn->query($sqlString)) {
  echo json_encode(["success" => "true"]);
}else{
  echo json_encode(["error" => 'Error: unable to query SQL :' . $sqlString]);
}

$conn->close();
}else{
  die(json_encode(["error" => "Could Not Retreive Form Info From Array." ]));
}
?>
