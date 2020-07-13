<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName="client_details";
try {
    if(trim($_POST['clientName'])!=''){
    $data=array(
    'client_name'=>$_POST['clientName'],
    'client_mobile_no'=>$_POST['phoneNo'],
    'alter_phone_number'=>$_POST['alterPhoneNo'],
    'client_email_id'=>$_POST['clientEmail'],
    'client_address'=>$_POST['address'],
    'client_shipping_address'=>$_POST['shipAddress'],
    'gst_no'=>$_POST['gstIn'],
    'client_status'=>'active',
    'client_added_by'=>$_SESSION['userId'],
    'client_added_on'=>date('Y-m-d H:i:s')
    );
    //print_r($data);die;
    $id = $db->insert($tableName,$data);
        if($id>0){
            $_SESSION['alertMsg']="Client details added successfully";
        }else{
            $_SESSION['alertMsg']="Please try again!";
        }
    }
    header("location:client-list.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}