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

session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}else{
  die(json_encode(["error" => "Please login to create an online game."]));
}

$eightBoard = '[["e","e","e","e","e","e","e","e"],["e","e","e","e","e","e","e","e"],["e","e","e","e","e","e","e","e"],["e","e","e","w","b","e","e","e"],["e","e","e","b","w","e","e","e"],["e","e","e","e","e","e","e","e"],["e","e","e","e","e","e","e","e"],["e","e","e","e","e","e","e","e"]]';

$sixBoard = '[["e","e","e","e","e","e"],["e","e","e","e","e","e"],["e","e","w","b","e","e"],["e","e","b","w","e","e"],["e","e","e","e","e","e"],["e","e","e","e","e","e"]]';

$fourBoard = '[["e","e","e","e"],["e","w","b","e"],["e","b","w","e"],["e","e","e","e"]]';

$gameID = uniqid();
if ($object["boardSize"] == 8) {
  $gameboard = $eightBoard;
}else if($object["boardSize"] == 6){
  $gameboard = $sixBoard;
}else if($object["boardSize"] == 4){
  $gameboard = $fourBoard;
}

$ai = $object["ai"];

$sqlString = "INSERT INTO games VALUES ('$username','$gameID','$gameboard',0,0,false,2,2,$ai,NULL);";

if($conn->query($sqlString)) {
  echo json_encode(["id" => $gameID]);
}else{
  echo json_encode(["error" => 'Error: unable to query SQL :' . $sqlString]);
}

$conn->close();
}else{
  die(json_encode(["error" => "Could Not Retreive Form Info From Array." ]));
}
?>
