<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "hotelver11";

$conn = mysqli_connect($server,$username,$password,$database);

if(!$conn){
    die("<script>alert('connection Failed.')</script>");
}
// else{
//     echo "<script>alert('connection successfully.')</script>";
// }
?>