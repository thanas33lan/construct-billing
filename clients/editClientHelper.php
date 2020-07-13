<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName="client_details";
$clientId=base64_decode($_POST['clientId']);

try {
    if(trim($_POST['clientName'])!=''){
        $data=array(
        'client_name'=>$_POST['clientName'],
        'client_mobile_no'=>$_POST['phoneNo'],
        'alter_phone_number'=>$_POST['alterPhoneNo'],
        'client_email_id'=>$_POST['clientEmail'],
        'gst_no'=>$_POST['gstIn'],
        'client_address'=>$_POST['address'],
        'client_shipping_address'=>$_POST['shipAddress'],
        'client_status'=>$_POST['status'],
        );
    
    $db=$db->where('client_id',$clientId);
    $id = $db->update($tableName,$data);
    if($id>0){
        $_SESSION['alertMsg']="Client details updated successfully";
    }
    }
    header("location:client-list.php");
  
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}