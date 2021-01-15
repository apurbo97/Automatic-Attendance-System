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

$name = $_POST['name'];
if (!file_exists('../../labeled_images/'.$name)) {
    mkdir('../../labeled_images/'.$name, 0777, true);
    $target_dir = "../../labeled_images/$name/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	// Check if image file is a actual image or fake image
	  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
	  if($check !== false) {
	    $uploadOk = 1;
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../../labeled_images/".$name."/1.jpg")) {
	    	$response['status'] = "success";
			$response['message'] = "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
			echo json_encode($response);
			exit();
		  } else {
		  	$response['status'] = "failed";
			$response['message'] = "Sorry, there was an error uploading your file.";
			echo json_encode($response);
			exit();
		  }
	  } 
	  else {
	  	$uploadOk = 0;
	  	$response['status'] = "failed";
		$response['message'] = "File is not an image.";
		echo json_encode($response);
		exit();
	  }
}
else{
	$response['status'] = "failed";
	$response['message'] = "User name Already Exist.";
	echo json_encode($response);
	exit();
}


?>