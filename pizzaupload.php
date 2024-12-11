<?php
require 'C:\xampp\htdocs\MeroPizza\db_connect.php';
$query1 = "SELECT * FROM meropizza.items";
$result1 = $conn->query($query1);

$data = array();
while ($row1 = $result1->fetch_assoc()) {
  $data[] = $row1;
  
}
foreach($data as $items){
    $itemId=$items['id']+1;
}



$target_dir = __DIR__ . "/../uploads/pizza/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

$target_file1 = $target_dir .$itemId."img".".".$imageFileType;

$uploadOk = 1;


// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {

   $name=$_POST['pizzaName'];
   $price=$_POST['pizzaPrice'];
   $des=$_POST['description'];
   echo $name;
   $img="/meropizza/uploads/pizza/".$itemId."img.".$imageFileType;
    
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
if ($_FILES["fileToUpload"]["size"] > 5000000) {
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
    if(!empty($name) && !empty($price) && !empty($des)){
        if (preg_match('/^\d+(\.\d{1,2})?$/', $price)){
            $sql = "INSERT INTO meropizza.items (name, price,description,img) VALUES (?, ?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("siss", $name, $price,$des, $img);
            $stmt->execute();
        }
        else{
            echo "invalid";
        }
    }
   if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file1)) {
    header('Location:/MeroPizza/pages/admin.php?upload_success=1');
    //echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}
?>