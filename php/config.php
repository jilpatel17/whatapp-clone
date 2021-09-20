<?php
  $hostname = "sql5.freesqldatabase.com";
  $username = "sql5438305";
  $password = "Fn66l1sWfz";
  $dbname = "sql5438305";

  $conn = mysqli_connect($hostname, $username, $password, $dbname);
  if(!$conn){
    echo "Database connection error".mysqli_connect_error();
  }
?>
