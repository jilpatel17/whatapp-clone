<?php
  $hostname = "remotemysql.com";
  $username = "oEBLrW5Q8N";
  $password = "Xtij1gtDvq";
  $dbname = "oEBLrW5Q8N";

  $conn = mysqli_connect($hostname, $username, $password, $dbname);
  if(!$conn){
    echo "Database connection error".mysqli_connect_error();
  }
?>
