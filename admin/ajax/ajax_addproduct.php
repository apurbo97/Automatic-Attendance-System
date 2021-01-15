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
include '../../../helpers/connection.php'; 
include '../../../helpers/suid.php';

  $img_path="";
  $target_dir = "../../img/products/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".";
      $uploadOk = 1;
    } else {
      $uploadOk = 0;
      $response['status'] = "failed";
      $response['message'] = "File is not an image.";
      echo json_encode($response);
      exit();
      
    }
  }

  // Check if file already exists
  if (file_exists($target_file)) {
      $uploadOk = 0;
      $response['status'] = "failed";
      $response['message'] = "Sorry, file already exists.";
      echo json_encode($response);
      exit();
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {
      $uploadOk = 0;
      $response['status'] = "failed";
      $response['message'] = "Sorry, your file is too large.";
      echo json_encode($response);
      exit();
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
      $uploadOk = 0;
      $response['status'] = "failed";
      $response['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      echo json_encode($response);
      exit();
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
      $response['status'] = "failed";
      $response['message'] = "Sorry, your file was not uploaded.";
      echo json_encode($response);
      exit();
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      $img_path = "img/products/". basename( $_FILES["fileToUpload"]["name"]);
    } else {
      $response['status'] = "failed";
      $response['message'] = "Sorry, there was an error uploading your file.";
      echo json_encode($response);
      exit();
    }
  }

$data = array(  
    'name' => $_POST['name'],
    'category_id' => $_POST['category'],
    'price' => $_POST['price'],
    'discount' => $_POST['discount'],
    'rating' => $_POST['rating'],
    'description' => $_POST['description'],
    'search_keyword' => $_POST['search_keyword'],
    'imp_path' => $img_path
);

if(strlen($data['name']) < 3 ){
  $response['status'] = "failed";
  $response['message'] = "Name should be atleast 3 character.";
  echo json_encode($response);
  exit();
}
if(!isset($data['category_id'])){
  $response['status'] = "failed";
  $response['message'] = "Please Select a category.";
  echo json_encode($response);
  exit();
}
if(isset($data['name'])){
  $sql = "SELECT id FROM `tbl_product` WHERE name ='".$data['name']."' AND category_id = ".$data['category_id'].";";
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0) {
    $response['status'] = "failed";
    $response['message'] = "Product already exist.";
    echo json_encode($response);
    exit();
  }
  
}
try{
  $suid_obj = new suid('tbl_product',$conn);
  $result = $suid_obj->store($data);   
    if($result)
    {
      $response['status'] = "success";
      $response['message'] = "Added Successfully";
      $response['err']=$result;
    }
    else
    {
      $response['status'] = "failed";
      $response['message'] = "Could not add"; 
      $response['err']=$result;
    }


                  
echo json_encode($response);
}
catch(Exception $e){
  $response['status'] = "failed";
  $response['message'] = "There may be some internal error, please try again."; 
  $response['err']=$e;
  echo json_encode($response);
}

?>