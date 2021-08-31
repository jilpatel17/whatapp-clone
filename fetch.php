<?php
session_start();
include_once "php/config.php";
$name1 = $_SESSION['unique_id'];

$sql = "select * from video where call_to='$name1'";
$run = mysqli_query($conn,$sql);
$c = mysqli_num_rows($run);
if($c)
{
    
    $row = mysqli_fetch_assoc($run);
    $caller = $row['call_from'];
    echo $row['call_to'];
 }
else
{
    echo 'not fetch';

}


?>