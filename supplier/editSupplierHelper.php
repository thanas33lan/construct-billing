<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName="supplier_details";
$supplierId=base64_decode($_POST['supplierId']);

try {
    if(trim($_POST['supplierName'])!=''  && trim($_POST['phoneNo'])!=''){
    $data=array(
        'supplier_name'=>$_POST['supplierName'],
        'supplier_address'=>$_POST['supplierAddress'],
        'supplier_email'=>$_POST['emailId'],
        'supplier_phone'=>$_POST['phoneNo'],
        'alter_phone_number'=>$_POST['alterPhoneNo'],
        'supplier_status'=>$_POST['status']
    );
    
    $db=$db->where('supplier_id',$supplierId);
    $id = $db->update($tableName,$data);
    if($id>0){
        $_SESSION['alertMsg']="Supplier details updated successfully";
    }
    }
    header("location:supplierList.php");
  
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}