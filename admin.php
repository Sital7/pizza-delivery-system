<?php
require 'C:\xampp\htdocs\MeroPizza\db_connect.php';
require 'C:\xampp\htdocs\MeroPizza\tcpdf\tcpdf.php';


if (isset($_POST['adminLogOut'])) {
  header('Location:/MeroPizza/pages/login.php?logout_success=1');
}

$query = "SELECT * FROM meropizza.admin WHERE id = 1";
$result = $conn->query($query);

$row = $result->fetch_assoc();

//for pizza items

$query1 = "SELECT * FROM meropizza.items";
$result1 = $conn->query($query1);

$data = array();
while ($row1 = $result1->fetch_assoc()) {
  $data[] = $row1;
}
// Convert PHP array to JSON
$jsonData = json_encode($data);

$query2 = "SELECT * FROM meropizza.orders";
$result2 = $conn->query($query2);

$data1 = array();
while ($row2 = $result2->fetch_assoc()) {
  $data1[] = $row2;
}
// Convert PHP array to JSON
$jsonData1 = json_encode($data1);


$query4 = "SELECT * FROM meropizza.complete_orders";
$result4 = $conn->query($query4);

$data4 = array();
while ($row4 = $result4->fetch_assoc()) {
  $data4[] = $row4;
}
// Convert PHP array to JSON
$jsonData4 = json_encode($data4);


//pdf

if (isset($_POST['receipt_button'])) {
  $item_id = $_POST['item_id'];
 foreach($data1 as $datas1){
  if($datas1['id']==$item_id){
    $productName=$datas1['product'];
    $productPrice=$datas1['price'];
    $productQuantity=$datas1['quantity'];
    $productPay=$datas1['payment'];
    $date=$datas1['date'];
    $userId=$datas1['user_id'];
    break;
  }
 }
 $sql= "SELECT name,phone,address,email FROM meropizza.users WHERE id=$userId";
 $result=$conn->query($sql);
 if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $customer = $row['name'];
  $phone = $row['phone'];
  $address = $row['address'];
  $email = $row['email'];
 }


 // Include the main TCPDF library (search for installation path).
 require_once('../tcpdf/tcpdf.php');
 
 
 // create new PDF document
 $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
 // set document information
 
 $pdf->SetAuthor('Mero Pizza');
 $pdf->SetTitle('order');
 $pdf->SetSubject('order');
 $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 

 
 // set default header data
 $pdf->SetHeaderData('../img/logo1.jpg', PDF_HEADER_LOGO_WIDTH, 'Mero Pizza ', 'Order Slip');

 
 
 
 // set header and footer fonts
 $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 20));
 $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
 
 // set default monospaced font
 $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
 // set margins
 $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
 $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
 $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
 
 // set auto page breaks
 $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
 
 // set image scale factor
 $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
 
 // set some language-dependent strings (optional)
 if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
     require_once(dirname(__FILE__) . '/lang/eng.php');
     $pdf->setLanguageArray($l);
 }
 
 // ---------------------------------------------------------
 
 // set font
 $pdf->SetFont('helvetica', '', 10);
 
 // add a page
 $pdf->AddPage();
 $pdf->Image('../img/logo1.jpg', 20, 5, 0, 18);
 
 
 // define some HTML content with style
 $content = "
 <ul>
 <li><b>Customer: </b>  " .$customer. "</li>
 <li><b>Phone: </b>  " . $phone . "</li>
 <li><b>Email: </b>  " . $email . "</li>
 <li><b>Address: </b>  " . $address . "</li>
 <li><b>Order ID:</b>  " . $item_id . "</li>
 <li><b>Order Item:</b>  " . $productName. "</li>
 <li><b>Quantity:</b>  " . $productQuantity . "</li>
 <li><b>Price RS:</b>  " . $productPrice . "</li>
 <li><b>Total Amount RS:</b>  " .( $productPrice * $productQuantity) . "</li>
 <li><b>Payment: </b>" . $productPay . "</li>
 <li><b>Order Date:</b>  " . $date . "</li>
 </ul>
 ";
 $centerX = ($pdf->getPageWidth() - $pdf->GetStringWidth($html)) / 2;
 $centerY = 30; // Adjust the vertical position as needed
 $pdf->writeHTMLCell(0, 0, $centerX, $centerY, $html, 0, 1, false, true, 'C');
 $pdf->writeHTML($content);
 
 
 // - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
 
 
 // reset pointer to the last page
 $pdf->lastPage();
 

 ob_end_clean();
 //Close and output PDF document
 $pdf->Output('receipt.pdf', 'D');
 
  
}


if(isset($_POST['salesReport'])){
  $from=$_POST['from_date'];
  $to=$_POST['to_date'];
  
// SQL query to fetch data between the selected date range
$sql = "SELECT * FROM meropizza.complete_orders WHERE date BETWEEN ? AND ?";

$stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $from, $to);


$stmt->execute();
$result = $stmt->get_result();
$dataCompleteOrder=array();
while ($row = $result->fetch_assoc()) {
  // Process each row of data
$dataCompleteOrder[]=$row;
}


// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information

$pdf->SetAuthor('Mero Pizza');
$pdf->SetTitle('order');
$pdf->SetSubject('order');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');



// set default header data
$pdf->SetHeaderData('../img/logo1.jpg', PDF_HEADER_LOGO_WIDTH, 'Mero Pizza ', 'Sales Report');




// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 20));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
  require_once(dirname(__FILE__) . '/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();
$pdf->Image('../img/logo1.jpg', 20, 5, 0, 18);

$totalAmount=0;
// define some HTML content with style
foreach($dataCompleteOrder as $data){

  foreach($data1 as $orderData){
  if($orderData['id']==$data['product_id']){
    $totalAmount=($orderData['quantity'])*($orderData['price'])+$totalAmount;
  $content = "
  <ul>
  <li><b>Date: </b>  " . $data['date'] . "</li>
  <li><b>Item: </b>  " .$orderData['product']. "</li>
  <li><b>Price: </b>  " . $orderData['price'] . "</li>
  <li><b>Quantity: </b>  " . $orderData['quantity'] . "</li>
  <li><b>Amount RS:</b>  " .( $orderData['quantity']* $orderData['price']) . "</li>
   <br>
  </ul>
  ";
  $pdf->writeHTML($content);
  }
  }
 }
$content1="
  <center><h1>Total Sales: ".$totalAmount."</h1></center>
";
 $pdf->writeHTML($content1);

$centerX = ($pdf->getPageWidth() - $pdf->GetStringWidth($content)) / 2;
$centerY = 30; // Adjust the vertical position as needed
$pdf->writeHTMLCell(0, 0, $centerX, $centerY, $content, 0, 1, false, true, 'C');



// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


// reset pointer to the last page
$pdf->lastPage();


ob_end_clean();
//Close and output PDF document
$pdf->Output('report.pdf', 'D');


}



?>
<script>
  const items = <?php echo $jsonData; ?>;
  const orderItems = <?php echo $jsonData1; ?>;
  const complete_orders = <?php echo $jsonData4; ?>;
</script>
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
  <link rel="stylesheet" href="/MeroPizza/style/admin.css">
</head>

<body>

  <!-- Header -->

  <div class="head" id="head">
    <div class="logo"><img src="/MeroPizza/img/logo.png" alt=""></div>
  </div>
  <!-- Header -->



  <div class="welcome">
    <h1>
      <center>Welcome to admin dashboard</center>
    </h1>

  </div>

  <div class="admin">

    <div class="adminInfo">
      <img src="/MeroPizza/img/User-avatar.svg.png" alt="">
      <label for="name">Name:</label><span name="adminName">
        <?php echo $row['name']; ?>
      </span><br>
      <label for="email">Email:</label><span name="adminEmail">
        <?php echo $row['email']; ?>
      </span><br><br>
      <form action="" method="post">
        <label for="password">Change password:</label><input type="password" name="password" id="psw"><span onclick="showhide()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z" />
            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z" />
          </svg></span><br><br>
      <PDF_MARGIN_LEFT><button name="passwordChange">Change</button></F_MARGIN_LEFT>
      </form>


    </div>
    <div> <h3><center>Items:</center></h3>
    <div id="pizza-items">
   
      <!-- Cart items will be dynamically added here -->

    </div></div>
    

    <form action="admin.php" method="POST">
      <h3><center>Edit Items:</center></h3>
      <div id="edit-items">
        <!-- Cart items will be dynamically added here -->

      </div>
      <div class="flex" style="justify-content: space-between;">
      <center><button name="editItems" id="editItem">Update</button></center>
      <center><button name="deleteItems" id="deleteItem">Delete</button></center></div>
    </form>


    <div class="addItems">
    <h3><center>Add Items:</center></h3><br>
       <form action="pizzaupload.php" method="post" enctype="multipart/form-data">
        
      <label for="">Image:</label><input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" placeholder="Choose Image" required >
       <br><label for="">Pizza Name:</label><input type="text" name="pizzaName" placeholder="Enter Name" class="inputItem" required>
       <br><label for="">Price:</label><input type="Number" name="pizzaPrice" placeholder="Enter PICE" class="inputItem" min="0" required>
       <br><label for="des"></label><textarea name="description" id="description" cols="30" rows="10" placeholder="Description" style="padding:5px;" require></textarea>
      <br><br> <input type="submit" name="submit" value="Add Pizza" style="padding:5px;">
      </form>
    </div>
  </div>
  


  <form action="admin.php" method="POST" class="table-container" id="myForm">
    <div class="table-wrapper">
      <table class="orderManage" id="orderManage">
        <tr>
          <th colspan="14">
            <h1>Orders</h1>
          </th>
        </tr>
        <tr>
          <th>Customer</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Order ID</th>
          <th>Order Item</th>
          <th>Quantity</th>
          <th>Rate</th>
          <th>Amount</th>
          <th>Payment</th>
          <th>Date:</th>
          <th colspan="4">Operations</th>
        </tr>


        <?php
        foreach ($data1 as $item) {
          $query3 = "SELECT * FROM meropizza.users where id= " . $item['user_id'] . "";
          $result3 = $conn->query($query3);

          $data3 = array();
          while ($row1 = $result3->fetch_assoc()) {
            $data3[] = $row1;
          }
          foreach ($data3 as $datas) {
            echo '<tr class="order" id="data' . $item['id'] . '">';
            echo '<td>' . $datas['name'] . '</td>';
            echo "<td>" . $datas['phone'] . "</td>";
            echo "<td>" . $datas['address'] . "</td>";
          }
          echo "<td>" . $item['id'] . "</td>";
          echo "<td>" . $item['product'] . "</td>";
          echo "<td>" . $item['quantity'] . "</td>";
          echo "<td>" . $item['price'] . "</td>";
          echo "<td>" . $item['price'] * $item['quantity'] . "</td>";
          echo "<td>" . $item['payment'] . "</td>";
          echo "<td>" . $item['date'] . "</td>";
          echo '<td><lable>Accept:</lable><input type="checkbox" name="execute' . $item['id'] . '" id="execute' . $item['id'] . '" class="checkbox"></td>';
          echo '<td><lable>Out For Delivery:</lable><input type="checkbox" name="out' . $item['id'] . '" id="out' . $item['id'] . '" class="checkbox"></td>';
          echo '<td><lable>Delivered:</lable><input type="checkbox" name="delivered' . $item['id'] . '" id="delivered' . $item['id'] . '" class="checkbox"></td>';
          echo '<td>
                <form method="post">
                    <input type="hidden" name="item_id" value="' . $item['id'] . '">
                    <input type="submit" value="Receipt" name="receipt_button" class="receipt">
                </form>
            </td>';
          echo "</tr>";
        }

        ?>
      </table>
    </div>
    <input type="submit" name="update" value="Update" class="receipt" id="update">
  </form>

  <br><br>
  <hr><br>

  <form action="admin.php" method="POST" class="logoutbtn">
    <center><button name="adminLogOut">Log Out</button></center>
  </form>


  <div class="salesReport">
  <h2>Sales Report</h2>
    <form method="post" action="admin.php">
        <label for="from_date">From Date:</label>
        <input type="date" name="from_date" required>

        <label for="to_date">To Date:</label>
        <input type="date" name="to_date" required>

        <input type="submit" value="Download Report" name="salesReport" class="btn">
    </form>
  </div>

  <!-- footer -->
  <footer>
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


  <script src="/MeroPizza/js/admin.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>

</body>

</html>

<?php


if (isset($_POST['update'])) {
  $insert = true;
  $outUpdate = true;
  $delUpdate = true;
  foreach ($data1 as $item) {
    $exe = isset($_POST['execute' . $item['id']]) ? 1 : 0;
    $out = isset($_POST['out' . $item['id']]) ? 1 : 0;
    $del = isset($_POST['delivered' . $item['id']]) ? 1 : 0;
    $DateTime = date('Y-m-d') . "-" . date('H:i:s');


    if ($exe == 1) {
      foreach ($data4 as $item4) {
        if ($item['id'] == $item4['product_id']) {
          $insert = false;
        }
      };
      if ($insert == true) {
        $sql = "INSERT INTO MeroPizza.complete_orders (product_id, out_for_delivery, delivery, date, user_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisi", $item['id'], $out, $del, $DateTime, $item['user_id']);

        if ($stmt->execute()) {
          echo "<script>Swal.fire({
              position: 'center',
              icon: 'success',
              title: 'Order Accepted',
              showConfirmButton: false,
              timer: 2500
            });
            
            setTimeout(function() {
              window.location.reload();
          }, 2500);
            </script>";
        } else {
          echo 'Error inserting data: ' . $stmt->error;
        }
      }
    }

    if ($out == 1) {
      foreach ($data4 as $item4) {
        if ($item4['out_for_delivery'] == 1 && $item['id'] == $item4['product_id']) {
          $outUpdate = false;
        }
      };
      if ($outUpdate == true) {
        $sql = "UPDATE MeroPizza.complete_orders  SET out_for_delivery=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $out, $item['id']);

        if ($stmt->execute()) {
          echo "<script>Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Order Now Ready For Delivery',
            showConfirmButton: false,
            timer: 2500
          });
      
          setTimeout(function() {
            window.location.reload();
        }, 2500);
          </script>";
        } else {
          echo 'Error inserting data: ' . $stmt->error;
        }
      }
    }

    if ($del == 1) {
      foreach ($data4 as $item4) {
        if ($item4['delivery'] == 1 && $item['id'] == $item4['product_id']) {
          $delUpdate = false;
        }
      };
      if ($delUpdate == true) {
        $sql = "UPDATE MeroPizza.complete_orders  SET delivery=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $del, $item['id']);

        if ($stmt->execute()) {
          echo "<script>Swal.fire({
            position: 'center',
            icon: 'success',
            title: 'Order Has Been Sucessfully Delivered',
            showConfirmButton: false,
            timer: 2500
          });
           
          setTimeout(function() {
            window.location.reload();
        }, 2500);
          </script>";
        } else {
          echo 'Error inserting data: ' . $stmt->error;
        }
      }
    }
  }
}




if (isset($_GET['login_success']) && $_GET['login_success'] === '1') {
  echo "<script>Swal.fire({
        position: 'center',
        icon: 'success',
        title: 'Log In Successfully',
        showConfirmButton: false,
        timer: 2500
      });</script>";
}
if (isset($_POST['passwordChange'])) {
  $newPsw = $_POST['password'];
  if ($newPsw != null) {
    $query = "UPDATE meropizza.admin SET password = '$newPsw' WHERE id = 1";
    $result = $conn->query($query);
  }
  if ($result === TRUE) {
    echo "<script>Swal.fire({
      position: 'center',
      icon: 'success',
      title: 'Password Changed Successfully',
      showConfirmButton: false,
      timer: 2500
    });</script>";
  } else {
    echo "<script>Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'Failed',
      showConfirmButton: false,
      timer: 2500
    });</script>";
  }
}

if (isset($_POST['editItems'])) {
  
  foreach ($data as $item) {
    $id = $item['id'];
    

    $itemId = $id;
    $itemName = $_POST['itemName' . $id];
    $des = $_POST['itemDes' . $id];
    if ($_POST['itemPrice' . $id] >= 1) {
      $itemPrice = $_POST['itemPrice' . $id];
  } else {
      $itemPrice = $item['price'];
  }
    $sql = "UPDATE MeroPizza.items SET name = ?, price = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $itemName, $itemPrice, $des, $itemId);

    // Execute the statement
    if ($stmt->execute()) {
      // Data updated successfully
      echo "Data updated successfully.";
      // echo '<script>window.location.reload();</script>';
      echo "<meta http-equiv='refresh' content='0'>";
    } else {
      // Error updating data
      echo "Error: " . $stmt->error;
    }
  }
}


if (isset($_POST['deleteItems'])) {
  foreach ($data as $item) {
    $last=$item['id'];
   
  }
  
  if($last==1){
    $sql="ALTER TABLE meropizza.items AUTO_INCREMENT=2";
    $stmt=$conn->prepare($sql);
    $stmt->execute();

    echo "<script>Swal.fire({
      position: 'center',
      icon: 'error',
      title: 'One Item Required',
      showConfirmButton: false,
      
      timer: 2500
    });</script>";
  }
  else{
    $sql="DELETE FROM meropizza.items WHERE id=$last";
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
      echo "<meta http-equiv='refresh' content='0'>";
    }
  }

}








if (isset($_GET['upload_success']) && $_GET['upload_success'] === '1') {
 
  echo "<script>Swal.fire({
    position: 'center',
    icon: 'success',
    title: 'Pizza Added Successfully',
    showConfirmButton: false,
    
    timer: 2500
  });</script>";
}

?>