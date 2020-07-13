<?php
$id = base64_decode($_GET['id']);
include('../base-url.php');
include '../includes/MysqliDb.php';
include('../includes/General.php');
include('../includes/tcpdf/tcpdf.php');
define('UPLOAD_PATH', '../uploads');

$general = new General();

$bQuery = "SELECT * FROM quotations WHERE q_id='" . $id . "'";
$bResult = $db->rawQuery($bQuery);

$bdQuery = "SELECT qpm.p_price, qpm.sqft, qpm.p_qty, qpm.discount, qpm.line_total, p.product_name,p.product_description FROM quotations_products_map AS qpm JOIN product_details AS p ON qpm.product_id=p.product_id WHERE qpm.q_id='" . $id . "'";
$bdResult = $db->rawQuery($bdQuery);

//client name
$cliQuery = "SELECT * FROM client_details WHERE client_id='" . $bResult[0]['q_customer'] . "'";
$cliResult = $db->rawQuery($cliQuery);

//client name
$uQuery = "SELECT user_name FROM user_details WHERE user_id='" . $bResult[0]['q_added_by'] . "'";
$uResult = $db->rawQuery($uQuery);

$cQuery = "SELECT * FROM company_profile";
$cResult = $db->rawQuery($cQuery);

class MYPDF extends TCPDF {
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
				$this->Image($image_file, 45, 5, 10, '', '', '', 'C', false, 300, '', false, false, 0, false, false, false);
			}
		}
		$this->SetFont('helvetica', 'B', 16);
		$this->writeHTMLCell(0, 0, 10, 6, $this->cmyName, 0, 0, 0, true, 'C', true);
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
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeading($bResult[0]['q_code'], ucwords($cResult[0]['company_name']),$cResult[0]['company_logo']);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($cResult[0]['company_name']);
$pdf->SetTitle($cResult[0]['company_name'].'-Quotation');
$pdf->SetSubject($cResult[0]['company_name'].'-quotation-to-'.ucwords($cliResult[0]['client_name']));
$pdf->SetKeywords('TCPDF, PDF, INVOICE');

$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);
// set auto page breaks
$pdf->SetAutoPageBreak(false, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont('helvetica', '', 10);
$pdf->setFontSubsetting(false);
$pdf->SetMargins(10, 20, 10, true);
$pdf->AddPage();

$html = '';
$html .= '
<hr style="height:3px;border-width:0;color:gray;background-color:cmyk(0%, 0%, 0%, 10%)"><br>
<span align="left">Quotation #'.$bResult[0]['q_code'].' - ('.$general->humanDateFormat($bResult[0]['q_date']).')</span><br>
<table border="1" cellpadding="0" style="padding:3px;">
    <tr>
        <td width="100%">
            <table border="o" cellpadding="0">
                <tr>
                    <td width="50%"><address>
                        From<br>
                        <b>'.strtoupper($cResult[0]['company_name']).'</b><br>
                        Mobile No : '.$cResult[0]['company_phone'].' / '.$cResult[0]['alt_number'].'<br>
                        e-mail: '.$cResult[0]['company_email'].'<br>
                        Website: '.$cResult[0]['website'].'
                    </address></td>
                    <td width="50%"><address>
                        To<br>
                        <b>Mr/Mrs/Ms.'.ucwords($cliResult[0]['client_name']).',</b><br>
                        '.$cliResult[0]['client_address'].',<br>
                        Tamilnadu.
                    </address></td>
                </tr>
                <hr>
                <tr>
                    <td colspan="2" width="100%">
                        <table border="1" cellpadding="2">
                            <thead>
                                <tr>
                                    <th align="center" style=" font-weight:bold; " width="10%">S:No</th>
                                    <th align="center" style=" font-weight:bold; " width="30%">Product</th>
                                    <th align="center" style=" font-weight:bold; " width="15%">Price</th>
                                    <th align="center" style=" font-weight:bold; " width="10%">Sqft</th>
                                    <th align="center" style=" font-weight:bold; " width="10%">Quantity</th>
                                    <th align="center" style=" font-weight:bold; " width="10%">DISC in (%)</th>
                                    <th align="center" style=" font-weight:bold; " width="15%">Total</th>
                                </tr>
                            </thead>
                            <tbody border="0" >';
                                foreach ($bdResult as $key => $val) {
                                    $html.='<tr>
                                        <td align="center" width="10%" style="border-left:1px solid transparent;border-right:1px solid transparent">'.($key + 1).'</td>
                                        <td align="left" width="30%" style="border-left:1px solid transparent;border-right:1px solid transparent"><b>'.ucwords($val['product_name']).'</b><br><code><span align="justify" style="font-size:10px;"><i>'.$val['product_description'].'</i></span></code></td>
                                        <td align="right" width="15%" style="border-left:1px solid transparent;border-right:1px solid transparent">Rs. '.number_format($val['p_price'], 2).'</td>
                                        <td align="right" width="10%" style="border-left:1px solid transparent;border-right:1px solid transparent">'.$val['sqft'].'</td>
                                        <td align="right" width="10%" style="border-left:1px solid transparent;border-right:1px solid transparent">'.$val['p_qty'].'</td>
                                        <td align="right" width="10%" style="border-left:1px solid transparent;border-right:1px solid transparent">'.$val['discount'].'</td>
                                        <td align="right" width="15%" style="border-left:1px solid transparent;border-right:1px solid transparent">Rs. '.number_format($val['line_total'], 2).'</td>
                                    </tr>';
                                }
                            $html.='</tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" align="right"><b>Grand Total</b></td>
                                    <td align="right"><b>Rs.'. number_format($bResult[0]['grand_total'], 2).'</b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
                <hr>
                <tr>
                    <td colspan="2">
                        &nbsp;&nbsp;We would be looking forward to receive your valued order.<br><br><br>
                        &nbsp;&nbsp;Thanking You.<br>
                        &nbsp;&nbsp;For, <b>'.strtoupper($cResult[0]['company_name']).'</b><br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
';
$pdf->writeHTML($html);
$pdf->lastPage();
$filename = 'quotation' . date('d-M-Y-H-i-s') . '.pdf';
$pdf->Output($filename, 'I');
exit;
/* $pdf->Output(UPLOAD_PATH . DIRECTORY_SEPARATOR . $filename,"F");
echo $filename; */
