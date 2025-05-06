<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$billTable = "bill_details";
$general = new General();

try {

    $data = array(
        'term_payment' => $_POST['termPayment'],
        'supplier_ref' => $_POST['supplierRef'],
        'invoice_due_date' => $general->dateFormat($_POST['invoiceDueDate'])
    );

    $billId = $_POST['billId'];
    $db = $db->where('bill_id', $billId);
    $id = $db->update($billTable, $data);

    $_SESSION['alertMsg'] = "PDF details updated successfully";

    header("location:invoice-list.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
