<?php
ob_start();

$id=$_GET['id'];
        include '../includes/MysqliDb.php';
        include('../includes/General.php');
        include ('../includes/tcpdf/tcpdf.php');
        define('UPLOAD_PATH','../uploads');
        $general = new General();
        class MYPDF extends TCPDF {

        }
        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('INVOICE');
        $pdf->SetTitle('INVOICE');
        $pdf->SetSubject('INVOICE');
        $pdf->SetKeywords('TCPDF, PDF, INVOICE');


        // set margins
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        // remove default footer
        $pdf->setPrintFooter(false);

        // set auto page breaks
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
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
        $pdf->setFontSubsetting(false);
        // remove default header
        $pdf->setPrintHeader(false);
        $pdf->SetMargins(10, 5, 10, true);
        // add a page
        $pdf->AddPage();


        // -- set new background ---
        // $pdf->SetAutoPageBreak(false, 0);
        
        // set bacground image
        //$img_file = dirname(__CLASS__) . 'public/assets/images/microsoft-word-certificate-borders.png';
        //$pdf->Image($img_file, 0, 0, 295, 209, '', '', '', false, 300, '', false, false, 0);
        // set the starting point for the page content
        //$pdf->setPageMark();
//invoice number generation
$bQuery="SELECT * FROM bill_details where bill_id='".$id."'";
$bResult = $db->rawQuery($bQuery);

$bdQuery="SELECT * FROM bill_product_details where bill_id='".$id."'";
$bdResult = $db->rawQuery($bdQuery);

//client name
$cliQuery="SELECT * FROM client_details where client_name='".$bResult[0]['client_name']."'";
$cliResult = $db->rawQuery($cliQuery);

$cQuery="SELECT * FROM company_profile";
$cResult = $db->rawQuery($cQuery);
$html = '';
$html .= '<h2>Tax Invoice</h2>';
$html .= '<table border="1" style="padding:3px;">';

    $html .= '<tr>';
        $html .= '<td style="padding:2px;line-height:15px;">';
            $html .= '<address><b>'.ucwords($cResult[0]['company_name']).'</b><br/>'.ucwords($cResult[0]['address_line_one']).',<br/>'.ucwords($cResult[0]['address_line_two']).'</address><br/>';
            $html .= '<span>Contact: '.$cResult[0]['company_phone'].'</span><br/>';
            $html .= '<span>GSTIN: '.$cResult[0]['gst_number'].'</span><br/>';
            $html .= '<span>E-Mail: '.$cResult[0]['company_email'].'</span>';
        $html .= '</td>';

        $html .='<td>';
            $html .='<table border="1" style="padding:3px">';
                $html .='<tr>';
                    $html .='<td>';
                        $html .='Invoice No - <b>'.$bResult[0]['invoice_no'].'</b>';
                    $html .='</td>';
                    $html .='<td>';
                        $html .='Invoice Dated - <b>'.$general->humanDateFormat($bResult[0]['invoice_date']).'</b>';
                    $html .='</td>';
                $html .='</tr>';
                $html .='<tr>';
                    $html .='<td>';
                        $html .='Delivery Note - <b>'.$bResult[0]['delivery_note'].'</b>';
                    $html .='</td>';
                    $html .='<td>';
                        $html .='Mode/Terms Of Payment - <b>'.$bResult[0]['term_payment'].'</b>';
                    $html .='</td>';
                $html .='</tr>';
                $html .='<tr>';
                    $html .='<td>';
                        $html .='Supplier"s Ref - <b>'.$bResult[0]['supplier_ref'].'</b>';
                    $html .='</td>';
                    $html .='<td>';
                        $html .='Other Reference(s) - <b>'.$bResult[0]['other_ref'].'</b>';
                    $html .='</td>';
                $html .='</tr>';
            $html .='</table>';
        $html .='</td>';

    $html .='</tr>';


    $html .='<tr>';
        $html .='<td>';
            $html .='<h4>Buyer</h4>';
            $html .='<address><b>'.ucwords($cliResult[0]['client_name']).'</b><br/>';
            $html .=ucwords($bResult[0]['shipping_address']).'</address><br/>';
            $html .='<span>Contact: '.$cliResult[0]['client_mobile_no'].'</span><br/>';
            $html .='<span>GSTIN: '.$cliResult[0]['gst_no'].'</span><br/>';
            $html .='<span>E-Mail: '.$cliResult[0]['client_email_id'].'</span>';
        $html .='</td>';

        $html .='<td>';
            $html .='<span class="row">';
                $html .='<span class="col-md-12">';
                    $html .='<span class="col-md-6">';
                        $html .='Buyer Order No. - <b>'.$bResult[0]['buyer_order_no'].'</b>';
                    $html .='</span>';
                    $html .='&nbsp;&nbsp;&nbsp;<span class="col-md-6">';
                        $html .='Dated - <b>'.$bResult[0]['buyer_date'].'</b>';
                    $html .='</span>';
                    $html .='<br/>';
                    $html .='<span class="col-md-6">';
                    $html .='Dispatch Document No.  - <b>'.$bResult[0]['dispatch_doc_no'].'</b>';
                    $html .='</span>';
                    $html .='<br/>';
                    $html .='<span class="col-md-6">';
                        $html .='Delivery Note Date - <b>'.$bResult[0]['delivery_note_date'].'</b>';
                    $html .='</span>';
                    $html .='<br/>';
                    $html .='<span class="col-md-6">';
                    $html .='Dispatch through  - <b>'.$bResult[0]['dispatch_through'].'</b>';
                    $html .='</span>';
                    $html .='<br/>';
                    $html .='<span class="col-md-6">';
                        $html .='Destination - <b>'.$bResult[0]['destination'].'</b>';
                    $html .='</span>';
                    $html .='<br/>';
                    $html .='<span class="col-md-6">';
                        $html .='Terms of Delivery - <b>'.$bResult[0]['term_delivery'].'</b>';
                    $html .='</span>';
                $html .='</span>';
            $html .='</span>';
        $html .='</td>';

    $html .='</tr>';


    $html .='<tr>';
        $html .='<td colspan="2">';
            $html .='<table border="1" style="padding:3px">';
                $html .='<tr>';
                    $html .='<th style="width:7%;">S.No</th><th style="width:26.5%;">Description Of Goods and Services</th><th>HSN/SAC</th><th>Quantity</th><th>Rate</th><th>Amount</th>';
                $html .='</tr>';
            
                $i = 1;
                $qty = 0;
                $total = 0;
                $netAmount = 0;
                $html .='<tr>';
                $n = count($bdResult);
                foreach($bdResult as $billDetails){
                    $qty += $billDetails['sold_qty'];
                    $total += $billDetails['rate'] * $billDetails['sold_qty'];
                    $netAmount += $billDetails['net_amount'];
                    
                        $html .='<td>'.$i.'</td>';
                        $html .='<td><b>'.ucwords($billDetails['product_name']).'</b></td>';
                        $html .='<td>'.$billDetails['hsn_code'].'</td>';
                        $html .='<td style="float:right;"><b>'.$billDetails['sold_qty'].' No</b></td>';
                        $html .='<td style="float:right;">'.number_format($billDetails['rate'],2).'</td>';
                        $html .='<td style="float:right;"><b>'.number_format($billDetails['rate'] * $billDetails['sold_qty'],2).'</b></td>';
                        if($i == $n){
                            $html .='<br><br><br>';
                        }
                        $i++;
                }
                $html .='<br><br><br></tr>';
                $html .='<tr>';
                    $html .='<td style="float:right;" colspan="3"><b>Total</b></td><td class="text-right"><b>'.$qty.' No</b></td><td></td><td><b>'.number_format($total,2).'</b></td>';
                $html .='</tr>';
            $html .='</table>';
        $html .='</td>';
    $html .='</tr><br/><br/>';


    $html .='<tr>';
        $html .='<td colspan="2">';
            $html .='<table border="1" style="padding:3px;">';
                $html .='<tr>';
                    $html .='<th rowspan="2" style="vertical-align:middle">HSN/SAC</th>';
                    $html .='<th rowspan="2" style="vertical-align:middle">Taxable Value</th>';
                    $html .='<th colspan="4" style="text-align:center;">Tax Rate</th>';
                    $html .='<th colspan="2" style="text-align:center;">Tax Rate</th>';
                    $html .='<th rowspan="2" style="vertical-align:middle">Amount</th>';
                $html .='</tr>';
                $html .='<tr>';
                    $html .='<th>CGST <i class="fa fa-percent"></i></th><th>Amount </th>';
                    $html .='<th>SGST <i class="fa fa-percent"></i></th><th>Amount </th>';
                    $html .='<th>IGST <i class="fa fa-percent"></i></th><th>Amount </th>';
                $html .='</tr>';
            
                $i = 1;
                $sgst = 0;
                $cgst = 0;
                $igst = 0;
                $taxTotal = 0;
                $html .='<tr>';
                $m = count($bdResult);
                foreach($bdResult as $billDetails){
                    $sgst += $billDetails['sgst_amount'];
                    $cgst += $billDetails['sgst_amount'];
                    $igst += $billDetails['igst_amount'];
                    $taxTotal += $billDetails['sgst_amount'] + $billDetails['sgst_amount'] + $billDetails['igst_amount'];
                    
                        $html .='<td>'.$billDetails['hsn_code'].'</td>';
                        $html .='<td style="text-align: right;">'.number_format($billDetails['rate'] * $billDetails['sold_qty'],2).'</td>';
                        $html .='<td>'.$billDetails['cgst_rate'].'</td>';
                        $html .='<td style="text-align: right;">'.$billDetails['cgst_amount'].'</td>';
                        $html .='<td>'.$billDetails['sgst_rate'].'</td>';
                        $html .='<td style="text-align: right;">'.$billDetails['sgst_amount'].'</td>';
                        $html .='<td>'.$billDetails['igst_rate'].'</td>';
                        $html .='<td style="text-align: right;">'.$billDetails['igst_amount'].'</td>';
                        $html .='<td style="text-align: right;">'.number_format($billDetails['cgst_amount'] + $billDetails['sgst_amount'] + $billDetails['igst_amount'],2).'</td>';
                        if($i == $m){
                            $html .='<br><br><br>';
                        }
                        $i++;
                }
                $html .='<br><br><br></tr>';
                $html .='<tr>';
                    $html .='<td colspan="3"><b>Total</b></td><td class="text-right"><b>'.number_format($sgst,2).'</b></td><td></td><td class="text-right"><b>'.number_format($sgst,2).'</b></td><td colspan="2" class="text-right"><b>'.number_format($igst,2).'</b></td><td colspan="" class="text-right"><b>'.number_format($taxTotal,2).'</b></td>';
                $html .='</tr>';
                




                $number = $netAmount;
   $no = round($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';

$value = ucwords($result) . "Rupees";




                $html .='<tr>';
                    $html .='<td colspan="8"><b>Grand Total</b></td>';
                    //$html .='<td colspan="7" ><b>Amount Chargable(In Words): '.$value.'</b></td>';
                    $html .='<td style="text-align: right;"><b> '. number_format($netAmount,2).' </b></td>';
                $html .='</tr>';
            $html .='</table>';
        $html .='</td>';
    $html .='</tr>';

    $html .='<tr>';
        $html .='<td colspan="2">';
        $html .='<table>';

        $html .='<b>Amount Chargable(In Words): '.$value.'</b><br/><br/><br/>';
        $html .='<tr><td rowspan="2">
                        <b>Declaration</b><br/>        
                        <span style="font-size:10px;">
                            '.$cResult[0]['declaration'].'
                        </span>
                    </td>
                    <td>
                    <tr>
                        <td>
                            <b>Company"s Bank Details</b><br/>
                            <span style="font-size:10px;float:left;">
                                Accounter Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<b>'.$cResult[0]['accounter_name'].'</b><br/>
                                A/C No.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;<b>'.$cResult[0]['accounte_no'].'</b><br/>
                                Branch & IFSC Code&nbsp;&nbsp;&nbsp;:&nbsp;<b>'.$cResult[0]['accounte_branch'].' & '.$cResult[0]['accounte_ifsc'].'</b><br/>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #333;">
                            <span>for '.$cResult[0]['company_name'].'</span><br/><br/><br/><br/>
                            <span>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authorised Signatory
                            </span>
                        </td>
                    </tr>
                    </td>
                    
                </tr>
                ';
        $html .='</table>';
        $html .='</td>';
    $html .='</tr>';

$html .='</table>';
        


$pdf->writeHTML($html);
$pdf->lastPage();
$filename = 'invoice' . date('d-M-Y-H-i-s') . '.pdf';
$pdf->Output(UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename,"F");
echo $filename;
?>