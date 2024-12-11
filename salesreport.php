<?php
  
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
foreach($dataCompleteOrder as $data){
  
    foreach($data1 as $orderData){
    if($orderData['id']==$data['product_id'])
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
    }
   }

$centerX = ($pdf->getPageWidth() - $pdf->GetStringWidth($content)) / 2;
$centerY = 30; // Adjust the vertical position as needed
$pdf->writeHTMLCell(0, 0, $centerX, $centerY, $content, 0, 1, false, true, 'C');
$pdf->writeHTML($content);


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


// reset pointer to the last page
$pdf->lastPage();


ob_end_clean();
//Close and output PDF document
$pdf->Output('receipt.pdf', 'D');

?>