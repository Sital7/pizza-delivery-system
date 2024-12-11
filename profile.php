<?php
session_start();
if(isset($_SESSION['user_id'])){
$user_id = $_SESSION['user_id'];
        $user_id = $_SESSION['user_id'];
        $image_path_png = __DIR__ . "/../uploads/userProfiles/".$user_id."pp.png";
        $image_path_jpg = __DIR__ . "/../uploads/userProfiles/".$user_id."pp.jpg";
        $image_path_jpeg = __DIR__ . "/../uploads/userProfiles/".$user_id."pp.jpeg";
   
         if(file_exists($image_path_png)){
          $image_path ="/MeroPizza/uploads/userProfiles/".$user_id."pp.png";
         }
         else if(file_exists($image_path_jpg)){
          $image_path ="/MeroPizza/uploads/userProfiles/".$user_id."pp.jpg";
         }
         else if(file_exists($image_path_jpeg)){
          $image_path ="/MeroPizza/uploads/userProfiles/".$user_id."pp.jpeg";
         }
        else{
          $image_path="/MeroPizza/uploads/userProfiles/person.svg";
        }
      }
      ?>
      
      