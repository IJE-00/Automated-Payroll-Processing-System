<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "auto_pay";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
	die("CONNECTION FAILED: ") . mysqli_connect_error();
}
?>