<?php 
$servername = "localhost"; // default server name
$sqlusername = "testUser"; // user name that you created
$sqlpassword = "badpassword%%"; // password that you created
$db = "Reversi";

$conn = new mysqli($servername, $sqlusername, $sqlpassword, $db);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


if(isset($_POST)){
  
  $query = "SELECT * FROM  `users`  WHERE `login`='{$_POST['username']}' ";
    $result = mysqli_query($conn,$query);
  
  if(mysqli_num_rows($result)){
    die("That Username/Email is taken");
  }

  if($_POST['passwordOne'] != $_POST['passwordTwo']){
    die("Passwords do not match");
  }
  
  if(empty(trim($_POST['passwordOne']))){
      die("Please enter a password");     
  } elseif(strlen(trim($_POST['passwordOne'])) < 6){
      die("Password must have atleast 6 characters");
  } else{
      $password = trim($_POST['passwordOne']);
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  $gender = strtolower($_POST['gender']);
  
  $sqlString = "INSERT INTO users VALUES ('{$_POST['username']}', '$hashedPassword', '{$_POST['firstName']}', '{$_POST['lastName']}','{$_POST['age']}','$gender','avatars\\\\default.png')";
  
  if ($conn->query($sqlString)) {
    session_start();
    $_SESSION['username'] = $_POST['username'];
    echo "success";
  }else{
    echo 'Error: unable to query SQL :' . $sqlString;
  }

  $conn->close();
}else{
  
echo "Error: Server Unable to Get Array";
}

 ?>
