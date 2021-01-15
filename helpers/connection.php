<?php
date_default_timezone_set('Asia/Kolkata');
$server="localhost";
$username="root";
$password="";
$database="face_rec_att_db";
// $username="uhfdimio_user";
// $password="SfF%ln0]st=p";
// $database="uhfdimio_db";

$conn=new mysqli($server,$username,$password,$database);
if($conn->connect_error)
{
	die("Connection Failed ".$conn->connect_error);
}
//echo "Connected Successfully ";
?>