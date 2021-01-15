<?php 

include 'connection.php';

if (isset($_POST['name'])) {
	$name = $_POST['name'];
	$select_sql = "SELECT * FROM `attendance_log` WHERE `name` = '$name' and date(`creation_date`) = CURDATE()";
	$result = $conn->query($select_sql);
	if ($result && $result->num_rows >0) {
		$response['status'] = "failed";
		$response['message'] = "Attendance has been taken already.";	
		$response['err']="Error";
		echo json_encode($response);
		exit();
	}

	try{
		$sql = "insert into attendance_log (`name`, `type`) VALUES ('$name','IN')";
		if ($conn->query($sql) === TRUE) {
  				$response['status'] = "success";
				$response['message'] = "$name yor attendance is marked";
			} 
			else {
  				$response['status'] = "failed";
				$response['message'] = "Could not add attendance";	
				$response['err']=$sql;
			}

		echo json_encode($response);
	}
	catch(Exception $e){
		$response['status'] = "failed";
		$response['message'] = "There may be some internal error, please try again.";	
		$response['err']=$e;
		echo json_encode($response);
	}
}


// print_r($profile_data);
?>