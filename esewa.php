
<?php
require 'C:\xampp\htdocs\MeroPizza\db_connect.php';
session_start();
$query = "SELECT MAX(id) AS last_id FROM meropizza.orders";
$result = $conn->query($query);

// Check if the query was successful
if ($result) {
    // Fetch the result row
    $row = $result->fetch_assoc();

    // Access the value using the alias "last_id"
    $lastId = $row['last_id'];
}
if($lastId<0 || !$lastId){
    $lastId=0;
}
$lastId++;
$orderDateTime = date('Y-m-d') . "-" . date('H:i:s');
$id='orderId'.$lastId.$orderDateTime;
$amt=$_SESSION['totalAmount'];


$url = "https://uat.esewa.com.np/epay/main";
$data =[
    'amt'=>$amt,
    'pdc'=> 0,
    'psc'=> 0,
    'txAmt'=> 0,
    'tAmt'=>$amt,
    'pid'=>$id,
    'scd'=> 'EPAYTEST',
    'su'=>'http://localhost/MeroPizza/pages/cart.php?q=su',
    'fu'=>'http://localhost/MeroPizza/pages/cart.php?q=fu'
];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    header("Location: https://uat.esewa.com.np/epay/main?" . http_build_query($data));
   exit();
?>