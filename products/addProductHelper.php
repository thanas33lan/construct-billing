<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName="product_details";
$stockTableName="stock_details";
$optionName="options";
/* echo "<pre>";
print_r($_POST);die; */

try {
    if(trim($_POST['productName'])!='' && trim($_POST['productPrice'])!='' && trim($_POST['hsnCode'])!=''){
        $data=array(
            'product_name'          => $_POST['productName'],
            'product_description'   => $_POST['prdDesc'],
            'supplier_id'           => $_POST['supplier'],
            'hsn_code'              => $_POST['hsnCode'],
            'product_price'         => $_POST['productPrice'],
            'qty_available'         => $_POST['actualQty'],
            'minimum_qty'           => $_POST['minimumQty'],
            'product_tax'           => $_POST['tax'],
            'product_added_by'      => $_SESSION['userId'],
            'product_added_on'      => date('Y-m-d H:i:s'),
            'product_status'        => 'active'
        );
        $id = $db->insert($tableName,$data);
        $lastId = $db->getInsertId();
        $stockData = array(
            'product_id'    => $lastId,
            'actual_qty'    => $_POST['actualQty'],
            'actual_price'  => $_POST['productPrice'],
            'minimum_qty'   => $_POST['minimumQty'],
            'stock_status'  => 'active',
        );
        $stockId = $db->insert($stockTableName,$stockData);
        
        foreach($_POST['name'] as $key=>$option){
            $optionData = array(
                'product_id'    => $lastId,
                'option_name'   => $option,
                'option_value'  => $_POST['value'][$key],
                'option_status' => $_POST['status'][$key]
            );
            $optionId = $db->insert($optionName,$optionData);
        }
        
        if($id>0 || $stockId > 0 || $optionId){
            $_SESSION['alertMsg']="Product details added successfully";
        }else{
            $_SESSION['alertMsg']="Please try again!";
        }
    }
    header("location:product-list.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}