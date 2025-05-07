<?php
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$general = new General();
$customerId = "";
$invoiceDate = "";
$customerId = isset($_POST['customerId']) ? $_POST['customerId'] : '';
$invoiceDate = isset($_POST['invoiceDate']) ? $_POST['invoiceDate'] : '';
if (!isset($customerId) && $customerId == "") {
    $cQuery = "SELECT b.invoice_no, b.invoice_date, b.invoice_due_date, b.client_name, b.total_amount, b.paid_amount, b.bill_added_on FROM bill_details where ";
} else {
    $cQuery = "SELECT b.invoice_no, b.invoice_date, b.invoice_due_date, b.client_name, b.total_amount, pd.pay_details, pd.paid_amount, b.bill_added_on FROM paid_details AS pd LEFT JOIN bill_details AS b ON pd.bill_id = b.bill_id WHERE pd.client_id = " . $customerId;
}
$start_date = '';
$end_date = '';
if (isset($_POST['invoiceDate']) && trim($_POST['invoiceDate']) != '') {
    $s_c_date = explode("to", $_POST['invoiceDate']);
    if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
        $start_date = $general->dateFormat(trim($s_c_date[0]));
    }
    if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
        $end_date = $general->dateFormat(trim($s_c_date[1]));
    }
    $cQuery = $cQuery . ' AND  DATE(invoice_date) >= "' . $start_date . '" AND DATE(invoice_date) <= "' . $end_date . '"';
    if (!isset($customerId) && $customerId == "") {
        $cQuery = $cQuery . ' AND  DATE(invoice_date) >= "' . $start_date . '" AND DATE(invoice_date) <= "' . $end_date . '"GROUP BY client_name"';
    }
}
if (isset($_POST['customerId']) && trim($_POST['customerId']) != '')
    $cQuery = $cQuery . ' ORDER BY pd.paid_on ASC';
$_SESSION['buyerListQry'] = $cQuery;
$cResult = $db->rawQuery($cQuery);
//get all pending payment
$pQuery = "SELECT SUM(paid_amount) AS paid_amount,SUM(total_amount) as total_amount FROM bill_details where ";
if (isset($_POST['customerId']) && trim($_POST['customerId']) != '') {
    $pQuery = $pQuery . "client_id like " . $customerId . "";
}
$start_date = '';
$end_date = '';
if (isset($_POST['invoiceDate']) && trim($_POST['invoiceDate']) != '') {
    $s_c_date = explode("to", $_POST['invoiceDate']);
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

//get all paid history
$dQuery = "SELECT DISTINCT DATE(invoice_date) as invoice_date FROM bill_details where ";
if (isset($_POST['customerId']) && trim($_POST['customerId']) != '') {
    $dQuery = $dQuery . "client_id like " . $customerId . "";
}
$start_date = '';
$end_date = '';
if (isset($_POST['invoiceDate']) && trim($_POST['invoiceDate']) != '') {
    $s_c_date = explode("to", $_POST['invoiceDate']);
    if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
        $start_date = $general->dateFormat(trim($s_c_date[0]));
    }
    if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
        $end_date = $general->dateFormat(trim($s_c_date[1]));
    }
    $dQuery = $dQuery . 'AND DATE(invoice_date) >= "' . $start_date . '" AND DATE(invoice_date) <= "' . $end_date . '" order by invoice_date';
}


$dResult = $db->query($dQuery);
$where = [];
$pdQuery = "
SELECT DISTINCT 
    DATE(paid_on) as paid_on, 
    paid_id, 
    invoice_date, 
    (b.total_amount) AS total_amount, 
    (pd.paid_amount) AS paid_amount
FROM 
    paid_details AS pd 
LEFT JOIN 
    bill_details AS b 
ON 
    b.bill_id = pd.bill_id ";
if (isset($_POST['customerId']) && trim($_POST['customerId']) != '') {
    $where[] = " pd.client_id like " . $customerId . "";
}
$start_date = '';
$end_date = '';
if (isset($_POST['invoiceDate']) && trim($_POST['invoiceDate']) != '') {
    $s_c_date = explode("to", $_POST['invoiceDate']);
    if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
        $start_date = $general->dateFormat(trim($s_c_date[0]));
    }
    if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
        $end_date = $general->dateFormat(trim($s_c_date[1]));
    }
    $where[] = ' DATE(paid_on) >= "' . $start_date . '" AND DATE(paid_on) <= "' . $end_date . '" order by paid_on';
}
if (isset($where) && !empty($where))
    $pdQuery .= "WHERE " . implode(" AND ", $where);
$pdResult = $db->rawQuery($pdQuery);



$btotalQuery = "SELECT * FROM bill_details as b INNER JOIN paid_details as pd ON pd.bill_id=b.bill_id where ";
if (isset($_POST['customerId']) && trim($_POST['customerId']) != '') {
    $btotalQuery = $btotalQuery . "b.client_id like " . $customerId . "";
}
if (isset($_POST['invoiceDate']) && trim($_POST['invoiceDate']) != '') {
    $s_c_date = explode("to", $_POST['invoiceDate']);
    if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
        $start_date = $general->dateFormat(trim($s_c_date[0]));
    }
    if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
        $end_date = $general->dateFormat(trim($s_c_date[1]));
    }
    $btotalQuery = $btotalQuery . 'AND DATE(paid_on) >= "' . $start_date . '" AND DATE(paid_on) <= "' . $end_date . '"';
}
// die($btotalQuery);
$btotalResult = $db->rawQuery($btotalQuery);
$amount = 0;
$paidArray = array();
foreach ($btotalResult as $amt) {
    if (!in_array($amt['paid_id'], $paidArray)) {
        $paidArray[] = $amt['paid_id'];
        $amount += $amt['paid_amount'];
    }
}
$totalAmount = $paidAmount = $openingBalance =  0;
foreach ($pdResult as $row) {
    if (!$openingBalance && empty($row['invoice_date']))
        $openingBalance = $row['paid_amount'];
}
$totalPendingAmount = (($pdResult[0]['total_amount'] - $pdResult[0]['paid_amount']) + $amount);

$invoiceDate = array_column($dResult, 'invoice_date');
$paidDate = array_column($pdResult, 'paid_on');

$mergeAry = array_merge($invoiceDate, $paidDate);

$orderByDate = array_unique($mergeAry);

function date_sort($a, $b)
{
    return strtotime($a) - strtotime($b);
}
usort($orderByDate, "date_sort");

?>
<div id="myTabContent" class="tab-content">
    <div class="tab-pane fade in active" id="purchaseHistory"><br />
        <table id="ClientDataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Invoice Date</th>
                    <th>Invoice Due Date</th>
                    <th>Client Name</th>
                    <th>Total</th>
                    <th>Collection Amount</th>
                    <th>Pending Amount</th>
                    <th>Added On</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($cResult) > 0) {
                    foreach ($cResult as $aRow) {
                        $addedDate = explode(" ", $aRow['bill_added_on']);
                        $inNo = $aRow['invoice_no'];
                        if (isset($aRow['pay_details']) && !empty($aRow['pay_details']))
                            $inNo = $aRow['invoice_no'] ?? $aRow['pay_details'];
                ?>
                        <tr>
                            <td><?php echo $inNo; ?></td>
                            <td><?php echo $general->humanDateFormat($aRow['invoice_date']); ?></td>
                            <td><?php echo $general->humanDateFormat($aRow['invoice_due_date']); ?></td>
                            <td><?php echo $aRow['client_name']; ?></td>
                            <td><?php echo $aRow['total_amount']; ?></td>
                            <td><?php echo $aRow['paid_amount']; ?></td>
                            <?php if (0 > $aRow['paid_amount']) {
                                $total = ($aRow['paid_amount'] - $aRow['total_amount']);
                                if (0 > $total) { ?>
                                    <td>0</td>
                                <?php } else { ?>
                                    <td><?php echo ($aRow['total_amount'] - $aRow['paid_amount']); ?></td>
                                <?php }
                            } else { ?>
                                <td><?php echo ($aRow['total_amount'] - $aRow['paid_amount']); ?></td>
                            <?php } ?>
                            <td><?php echo $general->humanDateFormat($addedDate[0]) . " " . $addedDate[1]; ?></td>
                        </tr>
                <?php
                    }
                } else {
                    echo "<tr style='background-color: antiquewhite;'><td colspan='8' align='center'><h4>There is no purchase history matched you are searching</h4></td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <div class="tab-pane fade" id="paymentHistory"><br />
        <?php if ($customerId != "") { ?>
            <a style="float:right;" href="javascript:void(0);" class="generatePDF btn btn-info" onclick="generatePDF()"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>
        <?php } ?>
        <table id="paidData" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Invoice Date</th>
                    <th>Particulars</th>
                    <th>Vch Type</th>
                    <th>Vch No.</th>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo ucwords($cResult[0]['client_name']); ?></td>
                    <td><?php echo $general->humanDateFormat($pdResult['invoice_date']) . ' To'; ?></td>
                    <td><b>Opening Balance</b></td>
                    <td></td>
                    <td></td>
                    <?php if (0 > $openingBalance) { ?>
                        <td></td>
                        <td><b><?php echo number_format($openingBalance, 2); ?></b></td>
                    <?php } else { ?>
                        <td><b><?php echo number_format($openingBalance, 2); ?></b></td>
                        <td></td>
                    <?php } ?>
                </tr>
                <?php
                $totalPendingForPay = 0;
                $totalPendingForPay += $totalPendingAmount;
                $totalCollection = $openingBalance;
                $paidArray = array();
                if (count($orderByDate) > 0) {
                    foreach ($orderByDate as $key => $date) {
                        if (!isset($paidArray) || !in_array($date, $paidArray)) {
                            $paidArray[] = $date;
                            $cQuery = "SELECT * FROM bill_details where ";
                            if (isset($_POST['customerId']) && trim($_POST['customerId']) != '') {
                                $cQuery = $cQuery . "client_id like " . $customerId;
                            }
                            if (isset($_POST['invoiceDate']) && trim($_POST['invoiceDate']) != '') {
                                $cQuery = $cQuery . 'AND DATE(invoice_date) >= "' . $date . '" AND DATE(invoice_date) <= "' . $date . '"';
                            }
                            $cResult = $db->rawQuery($cQuery);
                            foreach ($cResult as $invoice) {
                                if (!isset($paid2Array) || !in_array($invoice['bill_id'], $paid2Array)) {
                                    $paid2Array[] = $invoice['bill_id']; ?>
                                    <tr>
                                        <td><?php echo ucwords($invoice['client_name']); ?></td>
                                        <td><?php echo date("d-M-Y", strtotime($date)); ?> To</td>
                                        <td>Gst @ 18%</td>
                                        <td>Customer get sales</td>
                                        <td><?php echo $invoice['invoice_no']; ?></td>
                                        <td><?php echo number_format($invoice['total_amount'], 2); ?></td>
                                        <td></td>
                                    </tr>
                                <?php
                                    $totalPendingForPay += $invoice['total_amount'];
                                }
                            }
                            $bQuery = "SELECT * FROM bill_details as b INNER JOIN paid_details as pd ON pd.bill_id=b.bill_id where ";
                            $bQuery = "SELECT * FROM paid_details AS pd INNER JOIN bill_details AS b ON pd.bill_id = b.bill_id where ";
                            if (isset($_POST['customerId']) && trim($_POST['customerId']) != '') {
                                $bQuery = $bQuery . "pd.client_id like " . $customerId;
                            }
                            if (isset($_POST['invoiceDate']) && trim($_POST['invoiceDate']) != '') {
                                $bQuery = $bQuery . 'AND DATE(pd.paid_on) >= "' . $date . '" AND DATE(pd.paid_on) <= "' . $date . '"';
                            }
                            $bResult = $db->rawQuery($bQuery);
                            foreach ($bResult as $invoice) {
                                // if (!isset($paid2Array) || !in_array($invoice['paid_id'], $paid2Array)) {
                                if ((isset($invoice['bill_id']) && !empty($invoice['bill_id'])) && (!isset($paid3Array) || !in_array($invoice['bill_id'], $paid3Array))) {
                                    $paid2Array[] = $invoice['paid_id'];
                                    $paid3Array[] = $invoice['bill_id'];
                                ?>
                                    <tr>
                                        <td><?php echo ucwords($invoice['client_name']); ?></td>
                                        <td><?php echo date("d-M-Y", strtotime($date)); ?> By</td>
                                        <td><?php echo $invoice['pay_option']; ?></td>
                                        <td>Receipt</td>
                                        <td><?php echo $invoice['invoice_no']; ?></td>
                                        <td></td>
                                        <td><?php echo number_format($invoice['paid_amount'], 2); ?></td>
                                    </tr>
                <?php
                                    $totalCollection += $invoice['paid_amount'];
                                }
                            }
                        }
                    }
                } else {
                    echo "<tr style='background-color: antiquewhite;'><td colspan='7' align='center'><h4>There is no payment history matched you are searching</h4></td></tr>";
                }
                ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td><b>Closing Balance</b></td>
                    <td></td>
                    <td></td>
                    <?php if (0 > $totalCollection) {
                        $total = ($totalCollection - $totalPendingForPay);
                        if (0 > $total) { ?>
                            <td><b>0.00</b></td>
                        <?php } else { ?>
                            <td><b><?php echo number_format((($totalPendingForPay - $totalCollection)), 2); ?></b></td>
                        <?php }
                    } else { ?>
                        <td><b><?php echo number_format((($totalPendingForPay - $totalCollection)), 2); ?></b></td>
                    <?php } ?>
                    <td><b><?php echo number_format($totalCollection, 2); ?></b></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>