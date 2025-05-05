<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName = "supplier_details";
try {
    if (trim($_POST['supplierName']) != ''  && trim($_POST['phoneNo']) != '') {
        $data = array(
            'supplier_name' => $_POST['supplierName'],
            'supplier_address' => $_POST['supplierAddress'],
            'supplier_email' => $_POST['emailId'],
            'supplier_phone' => $_POST['phoneNo'],
            'alter_phone_number' => $_POST['alterPhoneNo'],
            'supplier_status' => 'active'
        );
        $id = $db->insert($tableName, $data);
        if ($id > 0) {
            $_SESSION['alertMsg'] = "Supplier details added successfully";
        } else {
            $_SESSION['alertMsg'] = "Please try again!";
        }
    }
    header("location:supplierList.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
