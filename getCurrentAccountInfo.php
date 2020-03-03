<?php

$servername = "localhost"; // default server name
$sqlusername = "testUser"; // user name that you created
$sqlpassword = "badpassword%%"; // password that you created
$dbname = "reversi";

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

$sqlString = "SELECT * FROM `users` WHERE `login` = '$username';";

$result = $conn->query($sqlString);
$output = ["error"=> "Something Went Wrong"];
if($result->num_rows == 1){  
  $row = $result->fetch_array(MYSQLI_NUM);
  
  $output = ["username" => $row[0],"firstName" => $row[2],"lastName" => $row[3],"age" => $row[4],"gender" => $row[5],"avatar" => $row[6]];
  
}else if($result->num_rows == 0){
  die(json_encode(["error" => "User does not exist"]));
}else{
  die(json_encode(["error" => "User Table Corrupted"]));
}

$sqlString = "SELECT * FROM `games` WHERE `login` = '$username';";

$result = $conn->query($sqlString);
$gameList = [];
while($row = $result->fetch_array(MYSQLI_NUM)){
  $temp = ['id' => $row[1],'duration' => $row[3],'complete' => $row[5],'whiteScore' => $row[6],'blackScore' => $row[7],'ai' => $row[8],'winner' => $row[9]];
  array_push($gameList, $temp);
}

$output["games"] = $gameList;

echo json_encode($output);

$conn->close();
?>
