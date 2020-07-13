<?php
ob_start();

$id = base64_decode($_GET['id']);
include '../includes/MysqliDb.php';
include('../includes/General.php');
include('../includes/tcpdf/tcpdf.php');
define('UPLOAD_PATH', '../uploads');
$general = new General();

//invoice number generation
$bQuery = "SELECT * FROM bill_details where bill_id='" . $id . "'";
$bResult = $db->rawQuery($bQuery);

// $bdQuery="SELECT * FROM bill_product_details where bill_id='".$id."'";
$bdQuery = "SELECT bpd.sqft,bpd.sold_qty,bpd.rate,bpd.tax,bpd.cgst_rate,bpd.cgst_amount,bpd.sgst_rate,bpd.sgst_amount,bpd.igst_rate,bpd.igst_amount,bpd.discount,bpd.net_amount,pd.product_name,pd.product_description,pd.hsn_code FROM bill_product_details AS bpd JOIN product_details as pd ON bpd.product_name=pd.product_id where bill_id='" . $id . "'";
$bdResult = $db->rawQuery($bdQuery);

//client name
$cliQuery = "SELECT * FROM client_details where client_name='" . $bResult[0]['client_name'] . "'";
$cliResult = $db->rawQuery($cliQuery);

$cQuery = "SELECT * FROM company_profile";
$cResult = $db->rawQuery($cQuery);


class MYPDF extends TCPDF
{
	//Page header
	public function setHeading($invNo, $cmyName,$logo)
	{
		$this->invNo = $invNo;
		$this->cmyName = $cmyName;
		$this->logo = $logo;
	}

	public function Header()
	{
		if (trim($this->logo) != '') {
			if (file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . $this->logo)) {
				$image_file = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . $this->logo;
				$this->Image($image_file, 40, 5, 12, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
			}
		}
		$this->SetFont('helvetica', 'B,U', 16);
		$this->writeHTMLCell(0, 0, 10, 8, $this->cmyName, 0, 0, 0, true, 'C', true);
	}

	// Page footer
	public function Footer()
	{
		// Position at 15 mm from bottom
		$this->SetY(-15);
		// Set font
		$this->SetFont('helvetica', '', 8);
		// Page number
		$this->Cell(0, 17,  strtoupper($this->invNo) . ' | Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
		$this->Cell(0, 17,  strtoupper($this->cmyName), 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeading($bResult[0]['invoice_no'], ucwords($cResult[0]['company_name']),$cResult[0]['company_logo']);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(ucwords($cResult[0]['company_name']));
$pdf->SetTitle('INVOICE -' . $bResult[0]['invoice_no']);
$pdf->SetSubject('INVOICE -' . $bResult[0]['invoice_no']);
$pdf->SetKeywords('TCPDF, PDF,' . ucwords($cResult[0]['company_name']));


// set margins
$pdf->SetMargins(5, 5, 5);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// set auto page breaks
$pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
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
$pdf->SetMargins(10, 20, 10, true);
// add a page
$pdf->AddPage();

$html = '<hr style="height:3px;border-width:0;color:gray;background-color:cmyk(0%, 0%, 0%, 10%)"><br>';
$html .= '<span><b>TAX INVOICE #'.$bResult[0]['invoice_no'].'</b></span><br>';
$html .= '<table border="1" style="padding:3px;">';

$html .= '<tr>';
$html .= '<td style="padding:2px;line-height:15px;">';
$html .= '<address><b>' . ucwords($cResult[0]['company_name']) . '</b><br/>' . ucwords($cResult[0]['address_line_one']) . '<br/>' . ucwords($cResult[0]['address_line_two']) . '</address>';
$html .= '<br><span>Contact: ' . $cResult[0]['company_phone'] . '</span><br/>';
$html .= '<span>GSTIN: ' . $cResult[0]['gst_number'] . '</span><br/>';
$html .= '<span>E-Mail: ' . $cResult[0]['company_email'] . '</span>';
$html .= '</td>';

$html .= '<td>';
$html .= '<table border="1" style="padding:3px">';
$html .= '<tr>';
$html .= '<td>';
$html .= 'Invoice No - <b>' . $bResult[0]['invoice_no'] . '</b>';
$html .= '</td>';
$html .= '<td>';
$html .= 'Invoice Dated - <b>' . $general->humanDateFormat($bResult[0]['invoice_date']) . '</b>';
$html .= '</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td>';
$html .= 'Delivery Note - <b>' . $bResult[0]['delivery_note'] . '</b>';
$html .= '</td>';
$html .= '<td>';
$html .= 'Mode/Terms Of Payment - <b>' . $bResult[0]['term_payment'] . '</b>';
$html .= '</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td>';
$html .= 'Supplier"s Ref - <b>' . $bResult[0]['supplier_ref'] . '</b>';
$html .= '</td>';
$html .= '<td>';
$html .= 'Other Reference(s) - <b>' . $bResult[0]['other_ref'] . '</b>';
$html .= '</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</td>';

$html .= '</tr>';


$html .= '<tr>';
$html .= '<td>';
$html .= '<h4>Buyer</h4>';
$html .= '<address><b>' . ucwords($cliResult[0]['client_name']) . '</b><br/>';
$html .= ucwords($bResult[0]['shipping_address']) . '</address>';
$html .= '<br><span>Mobile: ' . $cliResult[0]['client_mobile_no'] . '</span><br/>';
$html .= '<span>GSTIN: ' . $cliResult[0]['gst_no'] . '</span><br/>';
$html .= '<span>E-Mail: ' . $cliResult[0]['client_email_id'] . '</span>';
$html .= '</td>';

$html .= '<td>';
$html .= '<span class="row">';
$html .= '<span class="col-md-12">';
$html .= '<span class="col-md-6">';
$html .= 'Buyer Order No. - <b>' . $bResult[0]['buyer_order_no'] . '</b>';
$html .= '</span>';
$html .= '&nbsp;&nbsp;&nbsp;<span class="col-md-6">';
$html .= 'Dated - <b>' . $bResult[0]['buyer_date'] . '</b>';
$html .= '</span>';
$html .= '<br/>';
$html .= '<span class="col-md-6">';
$html .= 'Dispatch Document No.  - <b>' . $bResult[0]['dispatch_doc_no'] . '</b>';
$html .= '</span>';
$html .= '<br/>';
$html .= '<span class="col-md-6">';
$html .= 'Delivery Note Date - <b>' . $bResult[0]['delivery_note_date'] . '</b>';
$html .= '</span>';
$html .= '<br/>';
$html .= '<span class="col-md-6">';
$html .= 'Dispatch through  - <b>' . $bResult[0]['dispatch_through'] . '</b>';
$html .= '</span>';
$html .= '<br/>';
$html .= '<span class="col-md-6">';
$html .= 'Destination - <b>' . $bResult[0]['destination'] . '</b>';
$html .= '</span>';
$html .= '<br/>';
$html .= '<span class="col-md-6">';
$html .= 'Terms of Delivery - <b>' . $bResult[0]['term_delivery'] . '</b>';
$html .= '</span>';
$html .= '</span>';
$html .= '</span>';
$html .= '</td>';

$html .= '</tr>';


$html .= '<tr>';
$html .= '<td colspan="2">';
$html .= '<table border="1" style="padding:3px">';
$html .= '<tr>';
$html .= '<th style="width:7%;">S.No</th><th style="width:26.5%;">Description Of Goods and Services</th><th>HSN/SAC</th><th>Quantity/Sqft</th><th>Rate</th><th>Amount</th>';
$html .= '</tr>';

$i = 1;
$qty = 0;
$total = 0;
$netAmount = 0;
foreach ($bdResult as $billDetails) {
	$qty += $billDetails['sold_qty'];
	$total += $billDetails['rate'] * $billDetails['sold_qty'];
	$netAmount += $billDetails['net_amount'];

	$html .= '<tr>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $i . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;"><b>' . ucwords($billDetails['product_name']) . '</b><br><code><span align="justify" style="font-size:10px;"><i>' . $billDetails['product_description'] . '</i></span></code></td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $billDetails['hsn_code'] .'</td>';
	$html .= '<td style="float:right;border-right-color: white !important;border-left-color: white !important;"><b>' . $billDetails['sold_qty'] . ' / '.$billDetails['sqft'] . '</b></td>';
	$html .= '<td style="float:right;border-right-color: white !important;border-left-color: white !important;">' . number_format($billDetails['rate'], 2) . '</td>';
	$html .= '<td style="float:right;border-right-color: white !important;border-left-color: white !important;"><b>' . number_format($billDetails['rate'] * $billDetails['sold_qty'], 2) . '</b></td>';
	$html .= '</tr>';
	$i++;
}
$html .= '<tr>';
$html .= '<td style="float:right;" colspan="3"><b>Total</b></td><td class="text-right"><b>' . $qty . ' No</b></td><td></td><td><b>' . number_format($total, 2) . '</b></td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</td>';
$html .= '</tr><br/><br/>';


$html .= '<tr>';
$html .= '<td colspan="2">';
$html .= '<table border="1" style="padding:3px;">';
$html .= '<tr>';
$html .= '<th rowspan="2" style="vertical-align:middle">HSN/SAC</th>';
$html .= '<th rowspan="2" style="vertical-align:middle">Taxable Value</th>';
$html .= '<th colspan="4" style="text-align:center;">Tax Rate</th>';
$html .= '<th colspan="2" style="text-align:center;">Tax Rate</th>';
$html .= '<th rowspan="2" style="vertical-align:middle">Amount</th>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<th>CGST <i class="fa fa-percent"></i></th><th>Amount </th>';
$html .= '<th>SGST <i class="fa fa-percent"></i></th><th>Amount </th>';
$html .= '<th>IGST <i class="fa fa-percent"></i></th><th>Amount </th>';
$html .= '</tr>';

$i = 1;
$sgst = 0;
$cgst = 0;
$igst = 0;
$taxTotal = 0;
foreach ($bdResult as $billDetails) {
	$sgst += $billDetails['sgst_amount'];
	$cgst += $billDetails['sgst_amount'];
	$igst += $billDetails['igst_amount'];
	$taxTotal += $billDetails['sgst_amount'] + $billDetails['sgst_amount'] + $billDetails['igst_amount'];

	$html .= '<tr>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $billDetails['hsn_code'] . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . number_format($billDetails['rate'] * $billDetails['sold_qty'], 2) . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $billDetails['cgst_rate'] . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $billDetails['cgst_amount'] . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $billDetails['sgst_rate'] . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $billDetails['sgst_amount'] . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $billDetails['igst_rate'] . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . $billDetails['igst_amount'] . '</td>';
	$html .= '<td style="border-right-color: white !important;border-left-color: white !important;">' . number_format($billDetails['cgst_amount'] + $billDetails['sgst_amount'] + $billDetails['igst_amount'], 2) . '</td>';
	$html .= '</tr>';

	$i++;
}

$html .= '<tr>';
$html .= '<td colspan="3" class="text-right"><b>Tax Total</b></td><td class="text-right"><b>' . number_format($sgst, 2) . '</b></td><td></td><td class="text-right"><b>' . number_format($sgst, 2) . '</b></td><td colspan="2" class="text-right"><b>' . number_format($igst, 2) . '</b></td><td colspan="" class="text-right"><b>' . number_format($taxTotal, 2) . '</b></td>';
$html .= '</tr>';





$number = $netAmount;
$no = round($number);
$point = round($number - $no, 2) * 100;
$hundred = null;
$digits_1 = strlen($no);
$i = 0;
$str = array();
$words = array(
	'0' => '', '1' => 'one', '2' => 'two',
	'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
	'7' => 'seven', '8' => 'eight', '9' => 'nine',
	'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
	'13' => 'thirteen', '14' => 'fourteen',
	'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
	'18' => 'eighteen', '19' => 'nineteen', '20' => 'twenty',
	'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
	'60' => 'sixty', '70' => 'seventy',
	'80' => 'eighty', '90' => 'ninety'
);
$digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
while ($i < $digits_1) {
	$divider = ($i == 2) ? 10 : 100;
	$number = floor($no % $divider);
	$no = floor($no / $divider);
	$i += ($divider == 10) ? 1 : 2;
	if ($number) {
		$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
		$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
		$str[] = ($number < 21) ? $words[$number] .
			" " . $digits[$counter] . $plural . " " . $hundred
			:
			$words[floor($number / 10) * 10]
			. " " . $words[$number % 10] . " "
			. $digits[$counter] . $plural . " " . $hundred;
	} else $str[] = null;
}
$str = array_reverse($str);
$result = implode('', $str);
//   $points = ($point) ?"." . $words[$point / 10] . " " . $words[$point = $point % 10] : '';

$value = ucwords($result) . "Rupees";




$html .= '<tr>';
$html .= '<td colspan="8" class="text-left"><b>Purchased Total</b></td>';
//$html .='<td colspan="7" ><b>Amount Chargable(In Words): '.$value.'</b></td>';
$html .= '<td style="text-align: left;" class="text-left"><b>' . number_format($total, 2) . ' </b></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td colspan="8" class="text-left"><b>Sub Total</b></td>';
//$html .='<td colspan="7" ><b>Amount Chargable(In Words): '.$value.'</b></td>';
$html .= '<td style="text-align: left;" class="text-left"><b>' . number_format($netAmount, 2) . ' </b></td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td colspan="8" class="text-left"><b>Grand Total</b></td>';
//$html .='<td colspan="7" ><b>Amount Chargable(In Words): '.$value.'</b></td>';
$html .= '<td style="text-align: left;" class="text-left"><b>' . round($netAmount) . ' </b></td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td colspan="2">';
$html .= '<table>';

$html .= '<b>Amount Chargable(In Words): ' . $value . '</b><br/><br/><br/>';
$html .= '<tr>
                    <td>
                        <span style="font-size:10px;">
                        <b>Declaration</b>
                            <p align="justify" style="width:90%;">' . $cResult[0]['declaration'] . '</p>
                            <br/><br/><br/><br/><br/>
                            <i>CUSTOMER SIGNATURE</i>
                        </span>
                    </td>
                    <td style="border:1px solid #333;">
                        <span>for ' . $cResult[0]['company_name'] . '</span><br/><br/><br/><br/>
                        <span>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Authorised Signatory
                        </span>
                    </td>
                    
                </tr>';
$html .= '</table>';
$html .= '</td>';
$html .= '</tr>';

$html .= '</table>';

$pdf->writeHTML($html);
$filename = 'invoice' . date('d-M-Y-H-i-s') . '.pdf';
$pdf->Output($filename, 'I');
exit;
/* $pdf->lastPage();
$pdf->Output(UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename, "F");
echo $filename; */
