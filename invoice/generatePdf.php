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
$bResult = $db->rawQueryOne($bQuery);

// $bdQuery="SELECT * FROM bill_product_details where bill_id='".$id."'";
$bdQuery = "SELECT bpd.sold_qty,bpd.rate,bpd.tax,bpd.cgst_rate,bpd.cgst_amount,bpd.sgst_rate,bpd.sgst_amount,bpd.igst_rate,bpd.igst_amount,bpd.discount,bpd.net_amount,pd.product_name,pd.product_description,pd.hsn_code FROM bill_product_details AS bpd JOIN product_details as pd ON bpd.product_name=pd.product_id where bill_id='" . $id . "'";
$bdResult = $db->rawQuery($bdQuery);
//client name
$cliQuery = "SELECT * FROM client_details where client_name='" . $bResult['client_name'] . "'";
$cliResult = $db->rawQueryOne($cliQuery);

$cQuery = "SELECT * FROM company_profile";
$cResult = $db->rawQueryOne($cQuery);

class MYPDF extends TCPDF
{
	private $invNo = "";
	private $cmyName = "";
	private $logo = "";
	//Page header
	public function setHeading($invNo, $cmyName, $logo)
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
				$this->Image($image_file, 5, 5, 18, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
			}
		}

		$this->SetFont('', 'B', 25);
		$this->writeHTMLCell(0, 0, 22, 8, $this->cmyName . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small style="color:gray;">Tax Invoice Original</small>', 0, 0, 0, true, 'L', true);
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
		$this->Cell(0, 17,  strtoupper($this->cmyName) . ' TAX INVOICE', 0, false, 'R', 0, '', 0, false, 'T', 'M');
	}
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeading($bResult['invoice_no'], ucwords($cResult['company_name']), $cResult['company_logo']);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(ucwords($cResult['company_name']));
$pdf->SetTitle('INVOICE -' . $bResult['invoice_no']);
$pdf->SetSubject('INVOICE -' . $bResult['invoice_no']);
$pdf->SetKeywords('TCPDF, PDF,' . ucwords($cResult['company_name']));


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
$html .= '<span><b>TAX INVOICE #' . $bResult['invoice_no'] . '</b></span><br>';
$html .= '<table border="1" style="padding:3px;">';

$html .= '<tr>';
$html .= '<td style="padding:2px;line-height:15px;background-color:#ecf0f5;">';
$html .= '<address><b>' . ucwords($cResult['company_name']) . '</b><br/>' . ucwords($cResult['address_line_one']) . '<br/>' . ucwords($cResult['address_line_two']) . '</address>';
if (isset($cResult['company_phone']) && !empty($cResult['company_phone']))
	$html .= '<br><span>Mobile: ' . $cResult['company_phone'] . '</span><br/>';
$html .= '<br><span><strong>GSTIN: ' . $cResult['gst_number'] . '</strong></span><br/>';
if (isset($cResult['company_email']) && !empty($cResult['company_email']))
	$html .= '<span>E-Mail: ' . $cResult['company_email'] . '</span>';
$html .= '</td>';

$html .= '<td style="background-color:#ecf0f5;">';
$html .= '<b>Buyer</b><br/>';
$html .= '<address><b>' . ucwords($cliResult['client_name']) . '</b><br/>';
$html .= ucwords($bResult['shipping_address']) . '</address>';
if (isset($cliResult['client_mobile_no']) && !empty($cliResult['client_mobile_no']))
	$html .= '<br><span>Mobile: ' . $cliResult['client_mobile_no'] . '</span><br/>';
$html .= '<br><span><strong>GSTIN: ' . $cliResult['gst_no'] . '</strong></span><br/>';
if (isset($cliResult['client_email_id']) && !empty($cliResult['client_email_id']))
	$html .= '<span>E-Mail: ' . $cliResult['client_email_id'] . '</span>';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td>';
$html .= 'Invoice No - <b>' . $bResult['invoice_no'] . '</b>';
$html .= '</td>';
$html .= '<td>';
$html .= 'Invoice Dated - <b>' . $general->humanDateFormat($bResult['invoice_date']) . '</b>';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td>';
$html .= 'Mode/Terms Of Payment - ' . $bResult['term_payment'];
$html .= '</td>';
$html .= '<td>';
$html .= 'Supplier - ' . $bResult['supplier_ref'];
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td colspan="2">';
$html .= '<table border="1" style="padding:3px">';
$html .= '<tr>';
$html .= '<th style="width:7%;">S.No</th>
			<th style="width:31%;">Description Of Goods</th>
			<th style="width:14%;">HSN/SAC</th>
			<th style="width:10%;">Rate</th>
			<th style="width:14%;">Quantity</th>
			<th style="width:10%;">GST</th>
			<th style="width:14%;">Amount</th>';
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
	$html .= '<td>' . $i . '</td>';
	$html .= '<td>' . ucwords($billDetails['product_name']) . '<br><code><span align="justify" style="font-size:10px;"><i>' . $billDetails['product_description'] . '</i></span></code></td>';
	$html .= '<td>' . $billDetails['hsn_code'] . '</td>';
	$html .= '<td>' . number_format($billDetails['rate'], 2) . '</td>';
	$html .= '<td>' . $billDetails['sold_qty'] . '</td>';
	$html .= '<td>' . $billDetails['tax'] . '%</td>';
	$html .= '<td>' . number_format($billDetails['rate'] * $billDetails['sold_qty'], 2) . '</td>';
	$html .= '</tr>';
	$i++;
}
$n = count($bdResult);
if ($n <= 5) {
	$html .= '<tr>
				<td>
					' . str_repeat('<br/>', (int)($n * (6 - $n))) . '
				</td>
				<td>
					' . str_repeat('<br/>', (int)($n * (6 - $n))) . '
				</td>
				<td>
					' . str_repeat('<br/>', (int)($n * (6 - $n))) . '
				</td>
				<td>
					' . str_repeat('<br/>', (int)($n * (6 - $n))) . '
				</td>
				<td>
					' . str_repeat('<br/>', (int)($n * (6 - $n))) . '
				</td>
				<td>
					' . str_repeat('<br/>', (int)($n * (6 - $n))) . '
				</td>
				<td>
					' . str_repeat('<br/>', (int)($n * (6 - $n))) . '
				</td>
			</tr>';
}
$html .= '<tr>';
$html .= '<td colspan="4">
				<b>Total</b>
			</td>
			<td class="text-right">
				<b>' . $qty . ' No</b>
			</td>
			<td></td>
			<td>
				<b>' . number_format($total, 2) . '</b>
			</td>';
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
	$html .= '<td>' . $billDetails['hsn_code'] . '</td>';
	$html .= '<td>' . number_format($billDetails['rate'] * $billDetails['sold_qty'], 2) . '</td>';
	$html .= '<td>' . $billDetails['cgst_rate'] . '%</td>';
	$html .= '<td>' . number_format($billDetails['cgst_amount'], 2) . '</td>';
	$html .= '<td>' . $billDetails['sgst_rate'] . '%</td>';
	$html .= '<td>' . number_format($billDetails['sgst_amount'], 2) . '</td>';
	$html .= '<td>' . number_format($billDetails['igst_rate'], 2) . '%</td>';
	$html .= '<td>' . number_format($billDetails['igst_amount'], 2) . '</td>';
	$html .= '<td>' . number_format($billDetails['cgst_amount'] + $billDetails['sgst_amount'] + $billDetails['igst_amount'], 2) . '</td>';
	$html .= '</tr>';

	$i++;
}

$html .= '<tr>';
$html .= '<td colspan="3" class="text-right">
			Tax Total
		</td>
		<td class="text-right">
			' . number_format($sgst, 2) . '
		</td>
		<td></td>
		<td class="text-right">
		' . number_format($sgst, 2) . '
		</td>
		<td></td>
		<td class="text-right">
			' . number_format($igst, 2) . '
		</td>
		<td colspan="" class="text-right">
			' . number_format($taxTotal, 2) . '
		</td>';
$html .= '</tr>';

$number = $netAmount;
$no = round($number);
$point = round($number - $no, 2) * 100;
$hundred = null;
$digits_1 = strlen($no);
$i = 0;
$str = array();
$words = array(
	'0' => '',
	'1' => 'one',
	'2' => 'two',
	'3' => 'three',
	'4' => 'four',
	'5' => 'five',
	'6' => 'six',
	'7' => 'seven',
	'8' => 'eight',
	'9' => 'nine',
	'10' => 'ten',
	'11' => 'eleven',
	'12' => 'twelve',
	'13' => 'thirteen',
	'14' => 'fourteen',
	'15' => 'fifteen',
	'16' => 'sixteen',
	'17' => 'seventeen',
	'18' => 'eighteen',
	'19' => 'nineteen',
	'20' => 'twenty',
	'30' => 'thirty',
	'40' => 'forty',
	'50' => 'fifty',
	'60' => 'sixty',
	'70' => 'seventy',
	'80' => 'eighty',
	'90' => 'ninety'
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
$html .= '<td colspan="8" class="text-left">Purchased Total</td>';
$html .= '<td style="text-align: left;" class="text-left">' . number_format($total, 2) . '</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td colspan="8" class="text-left">Sub Total</td>';
$html .= '<td style="text-align: left;" class="text-left">' . number_format($netAmount, 2) . '</td>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td style="font-size:15px;" colspan="8" class="text-left"><b>Grand Total</b></td>';
$html .= '<td style="font-size:15px;text-align: left;" class="text-left"><b>' . round($netAmount) . ' </b></td>';
$html .= '</tr>';
$html .= '</table>';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td colspan="2">';
$html .= '<table>';

$html .= 'Amount(In Words): ' . $value . '<br/><br/><br/>';
$signText = "";
if (file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'sign' . DIRECTORY_SEPARATOR . "signature.png")) {
	$signText = '<img src="/uploads/sign/signature.png" width="150px";/>';
}
$html .= '<tr>
                    <td style="text-align:left;width:55%">
						<span style="text-align:left">
								<span style="font-size:15px;"><u>Declaration</u></span><br>
								1) Received in good condition and as per order. <br>
								2) Once sold, goods are not returnable; <br>
								3) Warranty, if any, is per manufacturer policy.
							</span>
							<br><br><br>
							<i>CUSTOMER SIGNATURE</i>
                    </td>
                    <td style="text-align:right;width:45%;"><br>
                        <span>for ' . $cResult['company_name'] . '</span><br/><br/>
                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							' . $signText . '
                        </span>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<i>AUTHORISED SIGNATURE</i>
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
