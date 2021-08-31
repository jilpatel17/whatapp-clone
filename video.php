<?php
session_start();
include_once "php/config.php";
$from = $_SESSION['unique_id'];
$to = $_POST['uid'];

$insert = "insert into `video`(`call_to`,`call_from`) values('$to','$from')";
$run = mysqli_query($conn,$insert);
if($run)
{
    echo "Calling........";
}
else
{
    echo "Network problem";
}




?>