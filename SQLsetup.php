<?php

$servername = "localhost"; // default server name
$username = "testUser"; // user name that you created
$password = "badpassword%%"; // password that you created
$dbname = "Reversi";

echo "A user with the name $username must exist <br>";
echo "and their password must be $password";

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error ."<br>");
}
echo "Connected successfully <br>";
// Creation of the database
$sql = "CREATE DATABASE ". $dbname;
if ($conn->query($sql)) {
	echo "Database ". $dbname ." created successfully<br>";
} else {
	echo "Error creating database ". $dbname ." : " . $conn->error ."<br>";
}

$conn = new mysqli($servername, $username, $password, $dbname);

$sql = "CREATE TABLE IF NOT EXISTS users (login VARCHAR(50) NOT NULL PRIMARY KEY, password VARCHAR(128) NOT NULL, firstName VARCHAR(50), lastName VARCHAR(50), Age INT, gender ENUM('male','female','other'), avatar VARCHAR(255));";
echo $sql . '<br>';
if ($conn->query($sql) === TRUE) {
  echo "Table ". "users" ." created successfully<br>";
} else {
  echo "Error creating table: " . $conn->error ."<br>";
}


$sql = "CREATE TABLE IF NOT EXISTS games (login VARCHAR(50) NOT NULL, id VARCHAR(14), gameBoard VARCHAR(512), duration TIME, turn INT, complete BOOLEAN, whiteScore INT, blackScore INT, ai BOOLEAN, winner VARCHAR(1), FOREIGN KEY(login) REFERENCES users(login));";
echo $sql . '<br>';
if ($conn->query($sql) === TRUE) {
  echo "Table ". "games" ." created successfully<br>";
} else {
  echo "Error creating table: " . $conn->error ."<br>";
}

// close the connection
$conn->close();

 ?>
