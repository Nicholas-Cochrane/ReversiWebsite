<?php 
$servername = "localhost"; // default server name
$sqlusername = "testUser"; // user name that you created
$sqlpassword = "badpassword%%"; // password that you created
$dbname = "reversi";

$conn = new mysqli($servername, $sqlusername, $sqlpassword, $dbname);
// Check connection
if ($conn->connect_error) {
  die("NULL");
}

// check if user is logged in
session_start();
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}else{
  die("NULL");
}

$sqlString = "SELECT * FROM `users` WHERE `login` = '$username';";

$result = $conn->query($sqlString);
$output = "NULL";
if($result->num_rows == 1){  
  $row = $result->fetch_array(MYSQLI_NUM);
  
  $output = ["username" => $row[0],"avatar" => $row[6]];
  
}else if($result->num_rows == 0){
  die(json_encode(["error" => "User does not exist"]));
}else{
  die(json_encode(["error" => "User Table Corrupted"]));
}

echo json_encode($output);

$conn->close();
?>
