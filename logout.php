<?php 
  session_start();
  if(isset($_SESSION['username'])){
    session_unset();
    // destroy the session
    session_destroy();
  }
  
  echo "logout complete";
 ?>
