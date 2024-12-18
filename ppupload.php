<?php
session_start();
$user_id = $_SESSION['user_id'];
$target_dir = __DIR__ . "/../uploads/userProfiles/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$target_file1 = $target_dir .$_SESSION['user_id']."pp".".".$imageFileType;

$uploadOk = 1;


// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {

  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

// Check if file already exists
// if (file_exists($target_file)) {
//   echo "Sorry, file already exists.";
//   $uploadOk = 0;
// }

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
  echo "Sorry, only JPG, JPEG ,PNG  files are allowed.";
  $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
  if(file_exists(__DIR__ . "/../uploads/userProfiles/".$user_id."pp.png") || file_exists(__DIR__ . "/../uploads/userProfiles/".$user_id."pp.jpg") || file_exists(__DIR__ . "/../uploads/userProfiles/".$user_id."pp.jpeg")){
    unlink(__DIR__ . "/../uploads/userProfiles/".$user_id."pp.jpg");
    unlink(__DIR__ . "/../uploads/userProfiles/".$user_id."pp.png");
    unlink(__DIR__ . "/../uploads/userProfiles/".$user_id."pp.jpeg");
  }
   if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file1)) {
    header('Location:/MeroPizza/pages/user.php?upload_success=1');
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>