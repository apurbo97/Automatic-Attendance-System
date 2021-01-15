<?php 
session_start();
if(!isset($_SESSION['valid_user']))
{
  	$response['status'] = "failed";
	$response['message'] = "Session expired, please login again.";
	echo json_encode($response);
	exit();	
}
$user = $_SESSION['valid_user'];
 ?>
<?php
if (isset($_GET['dn']))	 {
	
	// $path = ;
	if(unlink("../../labeled_images/".$_GET['dn']."/1.JPG")){
		if(!rmdir("../../labeled_images/".$_GET['dn'])) {
	  	echo ("Could not remove");
	}
	else{
		echo "<script type='text/javascript'>location.replace('../removeemp.php');</script>";
		}
	}
		
}
?>