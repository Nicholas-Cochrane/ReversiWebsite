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
  
  session_start();
  if(isset($_SESSION['username'])){
    die("Error, You Are Currently Logged In. Please Logout First.");
  }
  
  if(empty(trim($_POST["username"]))){
      die('Please enter username.');
  } else{
      $username = trim($_POST["username"]);
  }
  
  // Check if password is empty
  if(empty(trim($_POST['password']))){
      die('Please enter your password.');
  } else{
      $password = trim($_POST['password']);
  }
  
  // Prepare a select statement
  $sql = "SELECT login, password FROM users WHERE login = ?";
  if($stmt = mysqli_prepare($conn, $sql)){
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_username);
      // Set parameters
      $param_username = $username;
// echo $param_username;
      // Attempt to execute the prepared statement
      if(mysqli_stmt_execute($stmt)){
          // Store result
          mysqli_stmt_store_result($stmt);  
          // Check if username exists, if yes then verify password
          if(mysqli_stmt_num_rows($stmt) == 1){                    
              // Bind result variables
    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
              if(mysqli_stmt_fetch($stmt)){
      //echo $password ."<br>";
      //echo $hashed_password ."<br>";
                  if(password_verify($password, $hashed_password)){
                      /* Password is correct, so start a new session and
                      save the username to the session */
                      $_SESSION['username'] = $username;
                      echo("success");
                  } else{
                      // Display an error message if password is not valid
                      echo 'The password you entered was not valid.';
                  }
              }
          } else{
              // Display an error message if username doesn't exist
              echo 'No account found with that username.';
          }
      } else{
          echo "Oops! Something went wrong. Please try again later.";
      }
  }
  // Close statement
  mysqli_stmt_close($stmt);

  $conn->close();
}else{
  
echo "Error: Server Unable To Get Form Info";
}

 ?>
