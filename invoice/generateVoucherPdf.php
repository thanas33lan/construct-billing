<?php
ob_start();
include '../includes/MysqliDb.php';
include('../includes/General.php');
include('../includes/tcpdf/tcpdf.php');
define('UPLOAD_PATH', '../uploads');
$general = new General();

$customerName = $_GET['customerId'];
$bQuery = "SELECT * FROM bill_details where ";
if (isset($_GET['customerId']) && trim($_GET['customerId']) != '') {
    $bQuery = $bQuery . "client_name like '%" . $customerName . "%'";
}
$bResult = $db->rawQuery($bQuery);

//client name
$cliQuery = "SELECT * FROM client_details where client_name='" . $bResult[0]['client_name'] . "'";
$cliResult = $db->rawQueryOne($cliQuery);

$cQuery = "SELECT * FROM company_profile";
$cResult = $db->rawQuery($cQuery);
//get all pending payment
$pQuery = "SELECT SUM(paid_amount) AS paid_amount,SUM(total_amount) as total_amount FROM bill_details where ";
if (isset($_GET['customerId']) && trim($_GET['customerId']) != '') {
    $pQuery = $pQuery . "client_name like '%" . $customerName . "%'";
}
$start_date = '';
$end_date = '';
$s_c_date = array();
if (isset($_GET['invoiceDate']) && trim($_GET['invoiceDate']) != '') {
    $s_c_date = explode("to", $_GET['invoiceDate']);
    if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
        $start_date = $general->dateFormat(trim($s_c_date[0]));
    }
    if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
        $end_date = $general->dateFormat(trim($s_c_date[1]));
    }
    $pQuery = $pQuery . 'AND DATE(invoice_date) >= "' . $start_date . '" AND DATE(invoice_date) <= "' . $end_date . '"';
}

// die($pQuery);
$paidResult = $db->query($pQuery);

$totalPendingAmount = $paidResult[0]['total_amount'] - $paidResult[0]['paid_amount'];
//get all paid history
$dQuery = "SELECT DISTINCT DATE(invoice_date) as invoice_date FROM bill_details where ";
if (isset($_GET['customerId']) && trim($_GET['customerId']) != '') {
    $dQuery = $dQuery . "client_name like '%" . $customerName . "%'";
}
$start_date = '';
$end_date = '';

if (isset($_GET['invoiceDate']) && trim($_GET['invoiceDate']) != '') {
    $s_c_date = explode("to", $_GET['invoiceDate']);
    if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
        $start_date = $general->dateFormat(trim($s_c_date[0]));
    }
    if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
        $end_date = $general->dateFormat(trim($s_c_date[1]));
    }
    $dQuery = $dQuery . 'AND DATE(invoice_date) >= "' . $start_date . '" AND DATE(invoice_date) <= "' . $end_date . '" order by invoice_date';
}


$dResult = $db->query($dQuery);


$pdQuery = "SELECT DISTINCT DATE(paid_on) as paid_on,paid_id FROM paid_details as pd JOIN bill_details as b ON b.bill_id=pd.bill_id  where ";
if (isset($_GET['customerId']) && trim($_GET['customerId']) != '') {
    $pdQuery = $pdQuery . "client_name like '%" . $customerName . "%'";
}
$start_date = '';
$end_date = '';
if (isset($_GET['invoiceDate']) && trim($_GET['invoiceDate']) != '') {
    $s_c_date = explode("to", $_GET['invoiceDate']);
    if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
        $start_date = $general->dateFormat(trim($s_c_date[0]));
    }
    if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
        $end_date = $general->dateFormat(trim($s_c_date[1]));
    }
    $pdQuery = $pdQuery . 'AND DATE(paid_on) >= "' . $start_date . '" AND DATE(paid_on) <= "' . $end_date . '" order by paid_on';
}


$pdResult = $db->query($pdQuery);

$invoiceDate = array_column($dResult, 'invoice_date');
$paidDate = array_column($pdResult, 'paid_on');

$mergeAry = array_merge($invoiceDate, $paidDate);

$orderByDate = array_unique($mergeAry);

function date_sort($a, $b)
{
    return strtotime($a) - strtotime($b);
}
usort($orderByDate, "date_sort");

$customerName = $_GET['customerId'];
$invoiceDate = $_GET['invoiceDate'];

class MYPDF extends TCPDF
{
    private $customerName = "";
    private $cmyName = "";
    private $logo = "";
    //Page header
    public function setHeading($customerName, $cmyName, $logo)
    {
        $this->customerName = $customerName;
        $this->cmyName = $cmyName;
        $this->logo = $logo;
    }

    public function Header()
    {
        if (trim($this->logo) != '') {
            if (file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . $this->logo)) {
                $image_file = UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . $this->logo;
                $this->Image($image_file, 98, 2, 15, '', '', '', 'C', false, 300, '', false, false, 0, false, false, false);
            }
        }
        $this->SetFont('helvetica', 'B', 16);
        $this->writeHTMLCell(0, 0, 10, 15, $this->cmyName, 0, 0, 0, true, 'C', true);
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', '', 8);
        // Page number
        $this->Cell(0, 17,  strtoupper($this->customerName) . ' | Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 17,  strtoupper($this->cmyName) . ' LEDGER STATEMENT', 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeading($cliResult['client_name'], ucwords($cResult[0]['company_name']), $cResult[0]['company_logo']);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor($cResult[0]['company_name']);
$pdf->SetTitle($cResult[0]['company_name'] . '- Buyer History');
$pdf->SetSubject($cResult[0]['company_name'] . '-Buyer History-' . ucwords($cliResult['client_name']));
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
$pdf->SetMargins(10, 25, 10, true);
$pdf->AddPage();

$html = '';
$html .= '<table style="padding:3px;">';
$html .= '<tr>';
$html .= '<td style="text-align:center;">';
$html .= '<h4>Ledger Statement From ' . $invoiceDate . '</h4>';
$html .= '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="text-align:center;">';
$html .= '<address><b>' . ucwords($cliResult['client_name']) . '</b><br/>';
$html .= ucwords($bResult[0]['shipping_address']) . '</address><br>';
if (isset($cliResult['client_mobile_no']) && trim($cliResult['client_mobile_no']) != '') {
    $html .= '<span>Mobile Number: ' . $cliResult['client_mobile_no'] . '</span><br/>';
}
if (isset($cliResult['client_email_id']) && trim($cliResult['client_email_id']) != '') {
    $html .= '<span>E-Mail: ' . $cliResult['client_email_id'] . '</span>';
}
$html .= '</td>';
$html .= '</tr>';
$html .= '</table><br/><hr>';
$scdate = '';
if ((isset($s_c_date) && count($s_c_date) > 0)) {
    $scdate = $s_c_date[0];
}
// $html .= '<b>' . $invoiceDate . '</b>';
$html .= '<br/><table border="1" style="padding:3px;">
            <thead>
                <tr>
                    <th><b>Date</b></th>
                    <th><b>Particulars</b></th>
                    <th><b>Vch Type</b></th>
                    <th><b>Vch No.</b></th>
                    <th><b>Debit</b></th>
                    <th><b>Credit</b></th>
                </tr>
            </thead>';
$html .= '<tbody>';
$html .= '<tr>';
$html .= '<td>' . $paidResult[0]['invoice_date'] . '</td>';
$html .= '<td><b>Opening Balance</b></td>';
$html .= '<td></td>
          <td></td>';
$html .= '<td><b>' . number_format(0, 2) . '</b></td>';
$html .= '<td></td>
            </tr>';
$totalPendingForPay = 0;
$totalPendingForPay += 0;
$totalCollection = 0;
$paidArray = array();
foreach ($orderByDate as $date) {
    if (!in_array($date, $paidArray)) {
        $paidArray[] = $date;
        $cQuery = "SELECT * FROM bill_details where ";
        if (isset($_GET['customerId']) && trim($_GET['customerId']) != '') {
            $cQuery = $cQuery . "client_name like '%" . $customerName . "%'";
        }
        if (isset($_GET['invoiceDate']) && trim($_GET['invoiceDate']) != '') {
            $cQuery = $cQuery . 'AND DATE(invoice_date) >= "' . $date . '" AND DATE(invoice_date) <= "' . $date . '"';
        }
        $cResult = $db->rawQuery($cQuery);
        foreach ($cResult as $invoice) {
            $html .= '<tr>';
            $html .= '<td>' . date("d-M-Y", strtotime($date)) . 'To</td>';
            $html .= '<td>Gst @ 18%</td>
                            <td>Customer get sales</td>';
            $html .= '<td>' . $invoice['invoice_no'] . '</td>';
            $html .= '<td>' . number_format($invoice['total_amount'], 2) . '</td>
                            <td></td>
                        </tr>';
        }
        $totalPendingForPay += isset($invoice['total_amount']) ? $invoice['total_amount'] : 0;

        $bQuery = "SELECT * FROM bill_details as b INNER JOIN paid_details as pd ON pd.bill_id=b.bill_id where ";
        if (isset($_GET['customerId']) && trim($_GET['customerId']) != '') {
            $bQuery = $bQuery . "client_name like '%" . $customerName . "%'";
        }
        if (isset($_GET['invoiceDate']) && trim($_GET['invoiceDate']) != '') {
            $bQuery = $bQuery . 'AND DATE(invoice_date) >= "' . $date . '" AND DATE(invoice_date) <= "' . $date . '"';
        }
        $bResult = $db->rawQuery($bQuery);
        foreach ($bResult as $invoice) {
            $html .= '<tr>';
            $html .= '<td>' . date("d-M-Y", strtotime($date)) . ' By</td>';
            $html .= '<td>' . $invoice['pay_option'] . '</td>';
            $html .= '<td>Receipt</td>';
            $html .= '<td>' . $invoice['invoice_no'] . '</td>';
            $html .= '<td></td>';
            $html .= '<td>' . number_format($invoice['paid_amount'], 2) . '</td>';
            $html .= '</tr>';
        }

        $totalCollection += $invoice['paid_amount'];
    }
}
$html .= '<tr>';
$html .= '<td></td><td><b>Closing Balance</b></td><td></td><td></td>';
$html .= '<td style="color:red;"><b>' . number_format(($totalPendingForPay - $totalCollection), 2) . '</b></td>';
$html .= '<td style="color:darkgreen;">' . number_format($totalCollection, 2) . '</td>';
$html .= '</tr>';

$html .= '</tbody>';
$html .= '</table>';

$pdf->writeHTML($html);
$pdf->lastPage();
$filename = 'voucher_for_' . $cliResult['client_name'] . '-' . date('d-M-Y-H-i-s') . '.pdf';
$pdf->Output($filename, 'I');
exit;
