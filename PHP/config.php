<?php
  $db_host = 'localhost';
  $db_user = 'root';
  $db_password = 'root';
  $db_db = 'demo';
 
  $mysqli = @new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
  );
	
  if ($mysqli->connect_error) {
    // echo 'Errno: '.$mysqli->connect_errno;
    // echo '<br>';
    // echo 'Error: '.$mysqli->connect_error;
    exit();
  }

  $conn = new PDO("mysql:host=$db_host; dbname=$db_db", $db_user, $db_password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // echo 'Success: A proper connection to MySQL was made.';
  // echo '<br>';
  // echo 'Host information: '.$mysqli->host_info;
  // echo '<br>';
  // echo 'Protocol version: '.$mysqli->protocol_version;

//   $mysqli->close();
?>