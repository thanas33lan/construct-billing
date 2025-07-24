<?php
$id = base64_decode($_GET['id']);
include('../base-url.php');
include '../includes/MysqliDb.php';
include('../includes/General.php');
include('../includes/tcpdf/tcpdf.php');
define('UPLOAD_PATH', '../uploads');

$general = new General();

$bQuery = "SELECT * FROM quotations WHERE q_id='" . $id . "'";
$bResult = $db->rawQueryOne($bQuery);

$bdQuery = "SELECT qpm.p_price, qpm.p_qty, qpm.discount, qpm.line_total, p.product_name,p.product_description FROM quotations_products_map AS qpm JOIN product_details AS p ON qpm.product_id=p.product_id WHERE qpm.q_id='" . $id . "'";
$bdResult = $db->rawQuery($bdQuery);

//client name
$cliQuery = "SELECT * FROM client_details WHERE client_id='" . $bResult['q_customer'] . "'";
$cliResult = $db->rawQueryOne($cliQuery);

$cQuery = "SELECT * FROM company_profile";
$cResult = $db->rawQueryOne($cQuery);

class MYPDF extends TCPDF
{
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
        $this->writeHTMLCell(0, 0, 22, 8, $this->cmyName . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small style="color:gray;">Quotation</small>', 0, 0, 0, true, 'L', true);
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
        $this->Cell(0, 17,  strtoupper($this->cmyName) . ' QUOTATION', 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeading($bResult['q_code'], ucwords($cResult['company_name']), $cResult['company_logo']);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($cResult['company_name']);
$pdf->SetTitle($cResult['company_name'] . '-Quotation');
$pdf->SetSubject($cResult['company_name'] . '-quotation-to-' . ucwords($cliResult['client_name']));
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
<span>&nbsp;&nbsp;<b>QUOTATION #' . $bResult['q_code'] . ' ON ' . $general->humanDateFormat($bResult['enquiry_date']) . '</b></span><br>
<table>
    <tr>
        <td>
            <table border="1" style="padding:3px">
                <tr>
                    <td><address>
                        From<br>
                        <b>' . strtoupper($cResult['company_name']) . '</b><br>
                        Mobile No : ' . $cResult['company_phone'] . ' / ' . $cResult['alt_number'] . '<br>
                        e-mail: ' . $cResult['company_email'] . '<br>
                        Website: ' . $cResult['website'] . '
                    </address></td>
                    <td><address>
                        To<br>
                        <b>' . ucwords($cliResult['client_name']) . ',</b><br>
                        ' . $cliResult['client_address'] . ',<br>
                    </address></td>
                </tr>
                <tr><td colspan="2"><span>
                        Kind Attention:&nbsp;<b>' . ucwords($cliResult['client_name']) . '</b><br>
                        <i>Dear Sir,<br>
                        We thank you very much for your enquiry at ' . $general->humanDateFormat($bResult['enquiry_date']) . ' , and we are very pleased to quote our most competitive <br>&nbsp;&nbsp;rates for the following items.</i>
                    </span>
                </td></tr>
                <tr><td colspan="2"></td></tr>
                <tr>
                    <td colspan="2" style="padding:0;"><table border="1" style="width:100%;padding:3px">
                            <tr>
                                <th align="left" style="font-weight:bold;" width="7%">S:No</th>
                                <th align="left" style="font-weight:bold;" width="40%">Goods</th>
                                <th align="left" style="font-weight:bold;" width="13%">Price</th>
                                <th align="left" style="font-weight:bold;" width="10%">Quantity</th>
                                <th align="left" style="font-weight:bold;" width="15%">DISC in (%)</th>
                                <th align="left" style="font-weight:bold;" width="15%">Total</th>
                            </tr>';
foreach ($bdResult as $key => $val) {
    $html .= '<tr>
                <td align="left" width="7%">' . ($key + 1) . '</td>
                <td align="left" width="40%"><b>' . ucwords($val['product_name']) . '</b><br><code><span align="justify" style="font-size:10px;"><i>' . $val['product_description'] . '</i></span></code></td>
                <td align="left" width="13%">' . number_format($val['p_price'], 2) . '</td>
                <td align="left" width="10%">' . $val['p_qty'] . '</td>
                <td align="left" width="15%">' . $val['discount'] . '</td>
                <td align="left" width="15%">' . number_format($val['line_total'], 2) . '</td>
            </tr>';
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
if (isset($bResult['additional_charges']) && !empty($bResult['additional_charges'])) {
    $html .= '<tr>';
    if (isset($bResult['additional_charges_reason']) && !empty($bResult['additional_charges_reason']))
        $html .= '<td colspan="5" align="right">' . $bResult['additional_charges_reason'] . '</td>';
    $html .= '<td align="left"><b>' . number_format($bResult['additional_charges'], 2) . '</b></td>
            </tr>';
    $bResult['grand_total'] += $bResult['additional_charges'];
}
$html .= '
                                <tr>
                                    <td colspan="5" align="right"><b>Grand Total</b></td>
                                    <td align="left"><b>' . number_format($bResult['grand_total'], 2) . '</b></td>
                                </tr>
                        </table>
                    </td>
                </tr>
                <tr><td colspan="2"></td></tr>
                <tr>
                    <td colspan="2">
                        We would be looking forward to receive your valued order.<br>
                        Thanking you for business.<br><br><br>&nbsp;&nbsp;Regards,<br>
                        <b>' . strtoupper($cResult['company_name']) . '</b>
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
