<?php 
include '../../helpers/connection.php'; 
session_start();

if(isset($_SESSION['invalid_pass']))
{
	if($_SESSION['invalid_pass'] == 5)
		{
			$response = array('status'=>'failed', 'message'=>"Login Attempt Exceeded");
			echo json_encode($response);
			die();
		}
}

	$username = $_POST['email'];
	$password = $_POST['pass'];

	if(!filter_var($username, FILTER_VALIDATE_EMAIL))
	{
		$response = array('status'=>'failed', 'message'=>"Please enter a valid email.");
	}
	else if($password === "")
	{
		$response = array('status'=>'failed', 'message'=>"Please enter password.");	
	}
	else
	{
		
		$sql = "select * from tbl_user_account where email_id='".$username."'";

		$result = $conn->query($sql);

		if($result && $result->num_rows > 0)
		{
			$sql1 = "select * from tbl_user_account where email_id='".$username."' and password='".md5($password)."'";
			$result1 = $conn->query($sql1);
			if($result1 && $result1->num_rows > 0)
			{
				$logged_in_user = $result1->fetch_assoc();
				
				$_SESSION['valid_user'] = $logged_in_user;
				$response = array('status'=>'success', 'message'=>"Logged in successfully. Redirecting...");
			}
			else
			{
				$response = array('status'=>'failed', 'message'=>"Invalid password.");
				if(isset($_SESSION['invalid_pass']))
				{
					if($_SESSION['invalid_pass'] == 5)
					{
						$response = array('status'=>'failed', 'message'=>"Login Attempt Exceeded");
					}
					else
					{
						$invalid_pass = $_SESSION['invalid_pass'];
						$invalid_pass = $invalid_pass + 1;
						$_SESSION['invalid_pass'] = $invalid_pass;
					}	
				}
				else
				{
					$_SESSION['invalid_pass'] = 1;
				}
				
			}	
		}
		else
		{
			
			$response = array('status'=>'failed', 'message'=>"Invalid Email");	
		}
	}
echo json_encode($response);

 ?>