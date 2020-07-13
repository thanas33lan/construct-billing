<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$billTable="bill_details";
$general = new General();

try {

    $data=array(
    'delivery_note'=>$_POST['deliveryNote'],
    'term_payment'=>$_POST['termPayment'],
    'supplier_ref'=>$_POST['supplierRef'],
    'other_ref'=>$_POST['otherRef'],
    'buyer_order_no'=>$_POST['buyerOrderNo'],
    'buyer_date'=>$_POST['buyerDate'],
    'dispatch_doc_no'=>$_POST['docNo'],
    'delivery_note_date'=>$_POST['deliveryNoteDate'],
    'dispatch_through'=>$_POST['disThrough'],
    'destination'=>$_POST['destination'],
    'term_delivery'=>$_POST['termDelivery'],
    );
    
$billId = $_POST['billId'];
    $db=$db->where('bill_id',$billId);
    $id = $db->update($billTable,$data);

    $_SESSION['alertMsg']="PDF details updated successfully";
    
    header("location:invoice-list.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
