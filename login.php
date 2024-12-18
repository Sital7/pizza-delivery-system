<?php
require 'C:\xampp\htdocs\MeroPizza\db_connect.php';
session_start();


// login form 
if (isset($_POST['checkbox'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM meropizza.admin WHERE email = ? AND password = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    header('Location:/MeroPizza/pages/admin.php?login_success=1');

    //   echo "<script>window.location.href = '/MeroPizza/index.php';</script>";

    exit();
  } else {


    echo "<script>alert('Invalid email or password.');</script>";
  }
} else {
  if (isset($_POST['loginBtn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM meropizza.users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {


      //   echo "<script>window.location.href = '/MeroPizza/index.php';</script>";

      $row = $result->fetch_assoc();

      $_SESSION['username'] = $row['name'];
      $_SESSION['email'] = $row['email'];
      $_SESSION['address'] = $row['address'];
      $_SESSION['phone'] = $row['phone'];
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['password'] = $row['password'];


      header('Location:/MeroPizza/index.php?login_success=1');
      exit();
    } else {


      echo "<script>alert('Invalid email or password.');</script>";
    }
  }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/MeroPizza/style/main.css">
  <link rel="stylesheet" href="/MeroPizza/style/login.css">
</head>

<body>

  <!-- Header -->
  <div class="icon">
    <div id="show"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
      </svg></div>
    <div id="hide"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z" />
      </svg></div>
  </div>
  <div class="head" id="head">
    <div class="logo"><img src="/MeroPizza/img/logo.png" alt=""></div>
    <div class="menu">
      <ul>
        <li><a href="/MeroPizza/index.php">Home</a></li>
      </ul>
      <ul>
        <li><a href="about.php">About Us</a></li>
      </ul>
      <ul>
        <li><a href="order.php">Order</a></li>
      </ul>
      <ul id='login'>

        <?php
        session_destroy();
        // User is not logged in
        echo '<li><a href="login.php"><img src="/MeroPizza/img/User-avatar.svg.png" alt="">Log In</a></li>';

        ?>
      </ul>

    </div>
    <div class="cart">
      <a href="cart.php"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
          <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </svg></a>
    </div>
  </div>
  <!-- Header -->





  <!-- login banner -->
  <div class="loginBanner">
    <h1>
      <center>Login / Register</center>
    </h1>
  </div>

  <div class="loginRegister">

    <div class="loginForm">
      <h1>Login</h1>
      <form action="login.php" method="POST">
        <label for="admin" class="revert">I am Admin</label>
        <input type="checkbox" class="revert" name="checkbox"><br><br>
        <label for="email">Email:</label><br>
        <input type="email" placeholder="Your email address" id="formEmail" name="email"><br>
        <label for="password">Password:</label><br>
        <input type="password" placeholder="Your password" id="loginPsw" name="password"><span onclick="showhide()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
          </svg></span><br><br>

        <a href="">Forget Password ?</a><br>
        <button class="loginBtn" id="formBtn" name="loginBtn">Log In</button>
      </form>
    </div>

    <div class="registerForm">
      <h1>Create New Accout</h1>
      <form action="#" method="post">
        <label for="name">Full Name:</label><br>
        <input type="text" placeholder="Your full name" id="regName" name="name"><br>
        <label for="email">Email:</label><br>
        <input type="email" placeholder="Your email address" id="formEmail2" name="email2"><br>
        <label for="address">Address:</label><br>
        <input type="text" placeholder="Your delivery address" id="formaddress" name="address"><br>
        <label for="email">Phone:</label><br>
        <input type="text" placeholder="Your phone number" id="regPhone" name="phone"><br>
        <label for="password">New Password:</label><br>
        <input type="password" placeholder="Your password" id="regPsw" name="password2"><span onclick="showhide()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
          </svg></span><br>

        <button class="loginBtn" id="formBtn2" name="formBtn2">Register</button>
      </form>
    </div>

  </div>







  <!-- footer -->
  <footer>
    <div class="footer1">
      <div>
        <div class="title">
          <h1>Quick Links </h1>
        </div>
        <div>
          <ul>
            <li><a href="about.php">About Us</a></li>
          </ul>
          <ul>
            <li><a href="#">Feq</a></li>
          </ul>
          <ul>
            <li><a href="#">Contacts</a></li>
          </ul>
          <ul>
            <li><a href="login.php">Log In</a></li>
          </ul>
          <ul>
            <li><a href="login.php">Register</a></li>
          </ul>
        </div>
      </div>

      <div>
        <div class="title">
          <h1>Our Location </h1>
        </div>
        <div>
          <p>Kalanki,Kathmandu</p>
          <p>Mon - Fri: 08:00 am - 10:00 pm</p>
          <p>Sat - Sun: 10:00 am - 11:00 pm</p>
          <a href="telto:9877665431">9877665431</a>
        </div>
      </div>

      <div>
        <div class="title">
          <h1>Subscribe Us</h1>
        </div>
        <p>Subscribe to the our newsletter to
          get regular update about offers</p>
        <div>
          <input type="email" placeholder="Enter Your Email" id="newsLetterEmail">
          <div class="button" id="newsLetterBtn">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
              <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z" />
            </svg>
          </div>
        </div>

      </div>

    </div>
    <div class="footer2">
      <div class="payment">
        <h3>Payment Opations</h3>
        <a href="https://esewa.com.np/"><img src="/MeroPizza/img/esewa.png" alt="" width="50px"></a>
      </div>
      <div class="socialMedia">
        <a href="https://fb.com/"><img src="" alt=""><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
          </svg></a>
        <a href="https://instagram.com/"><img src="" alt=""><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
          </svg></a>
        <a href="https://twitter.com/"><img src="" alt=""><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
            <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
          </svg></a>
        <a href="https://tiktok.com/"><img src="" alt=""><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-tiktok" viewBox="0 0 16 16">
            <path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3V0Z" />
          </svg></a>

      </div>
    </div>

  <script src="/MeroPizza/js/navbar.js"></script>
  <script src="/MeroPizza/js/formValid.js"></script>
  <script src="/MeroPizza/js/login.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
</body>

</html>


<?php

//  registration form

function validateInput($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function isValidName($name) {
  return preg_match('/^[a-zA-Z ]+$/u', $name);
}

function isValidEmail($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate phone number format
function isValidPhoneNumber($phone) {
  
  return preg_match('/^[0-9]{10}$/', $phone);
}


if (isset($_POST['formBtn2'])) {
  $name = validateInput($_POST['name']);
  $email = validateInput($_POST['email2']);
  $address = validateInput($_POST['address']);
  $phone = validateInput($_POST['phone']);
  $password = validateInput($_POST['password2']);
//check validation 
if (!empty($name) && !empty($email) && !empty($address) && !empty($phone) && !empty($password) &&(isValidEmail($email)) && (isValidPhoneNumber($phone)) && (isValidName($name))) {


  $sql1 = "SELECT email FROM meropizza.users";
  $result = $conn->query($sql1);
  $emailExist = false;


  if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

      $data = $row['email'];

      if ($email == $data) {

        $emailExist = true;
        echo "<script>Swal.fire({
              position: 'center',
              icon: 'info',
              title: 'Email is already registered',
              html: '<p>Please use different email or log in with the registered email</p>',
              showConfirmButton: true
            });</script>";
        break;
      }
    }
  }
  if (!$emailExist) {

    $sql = "INSERT INTO meropizza.users (name, email,phone, password, address) VALUES (?, ?, ?, ?,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $password, $address);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
      echo "<script>Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Registered Successfully',
        showConfirmButton: true,
        timer: 5500
      });</script>";
    } else {

      echo "<script>alert('Registration failed!');</script>";
    }
  }
}

else{
  echo "<script>Swal.fire({
    position: 'center',
    icon: 'error',
    title: 'Please provide valid data',
    showConfirmButton: true,
  
  });</script>";
}
}
$conn->close(); ?>