<?php

    $item_id = $_POST['item_id'];
    echo $item_id;
  
  // Create a new PDF instance
  $pdf = new TCPDF();
  $pdf->AddPage();
  
  // Set font
  $pdf->SetFont('times', '', 12);
  
  // Add content to the PDF
  $content = 'Your receipt content here...';
  $pdf->Write(0, $content);
  
  // Output the PDF as a download
  $pdf->Output('receipt.pdf', 'D');
  
?>  


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