<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
include_once('../includes/General.php');
$general = new General();
$tableName="expense_details";
try {
    if(trim($_POST['particulars'])!='' && trim($_POST['particulars'])!='' && trim($_POST['particulars'])!=''){
    $data=array(
    'particulars'=>$_POST['particulars'],
    'purchased_from'=>base64_decode($_POST['purchasedFrom']),
    'purchased_by'=>base64_decode($_POST['purchasedBy']),
    'quantity'=>$_POST['quantity'],
    'price'=>$_POST['price'],
    'amount'=>$_POST['amount'],
    'payment_status'=>$_POST['paymentStatus'],
    'remarks'=>$_POST['remarks'],
    'expense_date'=>$general->getDate(),
    );
    $id = $db->insert($tableName,$data);
        if($id>0){
            $_SESSION['alertMsg']="Expense details added successfully";
        }else{
            $_SESSION['alertMsg']="Please try again!";
        }
    }
    header("location:expense-list.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}