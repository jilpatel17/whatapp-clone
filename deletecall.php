<?php
session_start();
include_once "php/config.php";
$name1 = $_SESSION['unique_id'];
$decline = $_POST['calling_id'];

if($name1===$decline){
  $sql = "delete from video where call_to='$decline'";
  $run = mysqli_query($conn,$sql);
  if($run)
  {
    echo "Call cut";
  }
  else
  {
    echo "Not cut";
  }
}


?>
