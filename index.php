<?php
require 'C:\xampp\htdocs\MeroPizza\db_connect.php';
require 'C:\xampp\htdocs\MeroPizza\pages\profile.php';
if (isset($_POST['logout'])) {
  header('Location:/MeroPizza/pages/login.php');
}
if (isset($_POST['profile'])) {
  header('Location:/MeroPizza/pages/user.php');
}

$sql = "SELECT  *FROM  meropizza.review";
$result = $conn->query($sql);

$data = array();
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

// Convert PHP array to JSON
$jsonData = json_encode($data);

$sql1 = "SELECT  id,name from  meropizza.users";
$result1 = $conn->query($sql1);


$sql = "SELECT id, name, img, description FROM meropizza.items";
$result = $conn->query($sql);


$data1 = array();
while ($row1 = $result1->fetch_assoc()) {
  $data1[] = $row1;
}

// Convert PHP array to JSON
$jsonData1 = json_encode($data1);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mero Pizza</title>
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style/main.css">
  <script>
    const review = <?php echo $jsonData; ?>;
    const user = <?php echo $jsonData1; ?>;
  </script>
  <style>
    .cards {
    display: flex;
    flex-wrap: wrap;
    justify-content: center; /* Center items horizontally */
    gap: 20px; /* Add space between cards */
    padding: 20px; /* Optional padding for better layout */
}
      
    .card-link {
        text-decoration: none; /* Remove underline */
        color: inherit;        /* Inherit text color */
    }

    .card {
    background-color: #000; /* Black background */
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    border: 2px solid white;
    width: 300px;
    height: 400px; /* Ensure equal card height */
}

.card:hover {
    /* transform: scale(1.05); Slight zoom effect on hover */
    box-shadow: 0 8px 16px rgba(255, 255, 255, 0.2); /* Highlight card */
}

.pizza-img {
    /* width: 100%;  */
    height: 200px; 
    /* overflow: hidden; Crop image if necessary */
}

.pizza-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.card:hover .pizza-img img {
    transform: scale(1.2); /* Overflowing zoom effect */
    z-index: 2; /* Bring the image to the foreground */
    border-radius: 10px; /* Maintain smooth corners */
}

.pizzades {
    text-align: center;
    color: white;
    padding: 10px;
}

.pizzades h1 {
    font-size: 20px;
    margin-bottom: 10px;
}

    .pizzades button {
        margin-top: 10px;
        padding: 10px 20px;
        font-size: 16px;
        color: #fff;
        background-color: #ff4500; /* Bright orange color */
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .pizzades button:hover {
        background-color: #e03e00; /* Slightly darker shade on hover */
    }

    
    .addtocart {
    margin-top: auto;
    padding: 10px;
}

.addtocart a {
    color: #000;
    background-color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.addtocart a:hover {
    background-color: #ff4500;
    color: white;
}

  </style>
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
    <div class="logo"><img src="img/logo.png" alt=""></div>
    <div class="menu">
      <ul>
        <li><a href="#">Home</a></li>
      </ul>
      <ul>
        <li><a href="pages/about.php">About Us</a></li>
      </ul>
      <ul>
        <li><a href="pages/order.php">Order</a></li>
      </ul>
      <ul id='login'>

        <?php

        if (isset($_SESSION['user_id'])) {
          // User is logged in

          echo '<li><a href="pages/user.php"><img src="' . $image_path . '" alt="User Image" id="pic"><span>' . $_SESSION['username'] . '</span></a></li>';

          echo '
           
          <form action="index.php" method="POST">
          <button name="profile">Profile</button>
            <button name="logout">Log Out</button>
          </form>
          ';
        } else {
          // User is not logged in
          echo '<li><a href="pages/login.php"><img src="img/User-avatar.svg.png" alt="">Log In</a></li>';
        }
        ?>

      </ul>

    </div>
    <div class="cart">
      <a href="pages/caart.php"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
          <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </svg></a>
    </div>
  </div>

  <!-- Header -->

  <!-- banner video -->
  <div class="banner-video">
    <video autoplay loop muted id="myVideo">
      <source src="video/pizzavideo.mp4" type="video/mp4">

    </video>
  </div>
  <!-- banner video -->

  <!-- banner-text -->
  <div class="banner-text">
    <h1>Order Online Your Pizza</h1>
    <div class="call-us">
      <h1>Call Us</h1><a href="tel: 9877665431">9877665431</a>
    </div>

    <div class="pizza">
      <img src="img/pizza.png" alt="">
    </div>
    <!-- <div class="buy-now-button">
      <a href="pages/caart.php">My Cart</a>
    </div> -->
  </div>
  <!-- banner-text -->

  <!-- Service -->
  <div class="service">
    <div class="delivery"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
        <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7zm1.294 7.456A1.999 1.999 0 0 1 4.732 11h5.536a2.01 2.01 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456zM12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12v4zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z" />
      </svg>
      <h3>Free Delivery</h3>
    </div>
    <div class="discount">
      <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-coin" viewBox="0 0 16 16">
        <path d="M5.5 9.511c.076.954.83 1.697 2.182 1.785V12h.6v-.709c1.4-.098 2.218-.846 2.218-1.932 0-.987-.626-1.496-1.745-1.76l-.473-.112V5.57c.6.068.982.396 1.074.85h1.052c-.076-.919-.864-1.638-2.126-1.716V4h-.6v.719c-1.195.117-2.01.836-2.01 1.853 0 .9.606 1.472 1.613 1.707l.397.098v2.034c-.615-.093-1.022-.43-1.114-.9H5.5zm2.177-2.166c-.59-.137-.91-.416-.91-.836 0-.47.345-.822.915-.925v1.76h-.005zm.692 1.193c.717.166 1.048.435 1.048.91 0 .542-.412.914-1.135.982V8.518l.087.02z" />
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
        <path d="M8 13.5a5.5 5.5 0 1 1 0-11 5.5 5.5 0 0 1 0 11zm0 .5A6 6 0 1 0 8 2a6 6 0 0 0 0 12z" />
      </svg>
      <h3>Up to 20% Discount</h3>
    </div>
    <div class="packing">
      <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-box-fill" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.004-.001.274-.11a.75.75 0 0 1 .558 0l.274.11.004.001 6.971 2.789Zm-1.374.527L8 5.962 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339Z" />
      </svg>
      <h3>Standard Packing</h3>
    </div>

  </div>
  <!-- Service -->



  <!-- Popular Our Food -->

  <div class="popularpizza" id='popularpizza'>
  <center> <h1>Our Popular Pizzas </h1></center>
  <center> <p>Choose what you like</p></center>
    <div class="cards">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<a href="/MeroPizza/pages/productDescription.php?id=' . $row['id'] . '" class="card-link">';
            echo '<div class="card">';
            echo '<div class="pizza-img">';
            echo '<img src="' . htmlspecialchars($row['img']) . '" alt="' . htmlspecialchars($row['name']) . '">';
            echo '</div>';
            echo '<div class="pizzades">';
            echo '<h1>' . htmlspecialchars($row['name']) . '</h1>';
            echo '</div>';
            echo '</div>';
            echo '</a>';
        }
    } else {
        echo '<p>No pizzas available</p>';
    }
    ?>
</div>


  </div>
  <!-- Popular Our Food -->

  <!-- review slider -->
  <div id="slider-container">
    <h1>What Our Clients Say</h1>
    <div class="review">
    <span  class="star">&#9733;</span>
    <span  class="star">&#9733;</span>
    <span  class="star">&#9733;</span>
    <span  class="star">&#9733;</span>
    <span  class="star">&#9733;</span>
      <p>
     
        <span>"</span>Great pizza. Tastes so fresh and original. Beats any of the chains hands
        down.<span>"</span><br>
        <span>"</span>I was so glad to hear Mike’s was opening in Oregon and now it is a wonderful
        reality.<span>"</span><br>
        
      <h1>Sital Khatri</h1>
      <h3>customer</h3>
      </p>
    </div>

  </div>

  <!-- review slider -->

  <!-- location -->
  <div class="map">

  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3533.020970918079!2d85.2806539!3d27.6931052!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39eb187a97f390b1%3A0xec3f47092df0d4ca!2sKalanki%2C%20Kathmandu!5e0!3m2!1sen!2snp!4v1700397361258!5m2!1sen!2snp" 
    width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade"></iframe>
  </div>
  <!-- location -->

  <!-- footer -->
  <footer>
    <div class="footer1">
      <div>
        <div class="title">
          <h1>Quick Links </h1>
        </div>
        <div>
          <ul>
            <li><a href="pages/about.php">About Us</a></li>
          </ul>
          <ul>
            <li><a href="#">Feq</a></li>
          </ul>
          <ul>
            <li><a href="#">Contacts</a></li>
          </ul>
          <ul>
            <?php
            if (isset($_SESSION['user_id'])) {
              // User is logged in
              echo '<li><a href="pages/login.php"><img src="img/User-avatar.svg.png" alt="" width="30px">Log Out</a></li>';
            } else {
              // User is not logged in
              echo '<li><a href="pages/login.php"><img src="img/User-avatar.svg.png" alt="" width="30px">Log In</a></li>';
            }
            ?>
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
        <a href="https://esewa.com.np/"><img src="img/esewa.png" alt="" width="50px"></a>
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

  <!-- footer -->
  <script src="js/main.js"></script>
  <script src="js/navbar.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

</body>

</html>
<?php


if (isset($_GET['login_success']) && $_GET['login_success'] === '1') {
  echo "<script>Swal.fire({
    position: 'center',
    icon: 'success',
    title: 'Log In Successfully',
    showConfirmButton: false,
    timer: 2500
  });</script>";
}

if (isset($_GET['logout_success']) && $_GET['logout_success'] === '1') {
  echo "<script>Swal.fire({
    position: 'center',
    icon: 'success',
    title: 'Log Out Successfully',
    showConfirmButton: false,
    timer: 2500
  });</script>";
}

if (!isset($_SESSION['username'])) {
  $_SESSION['username'] = 'Log In';
}


?>