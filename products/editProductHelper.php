<?php
ob_start();
session_start();

include('../base-url.php');
include('../includes/MysqliDb.php');
$tableName="product_details";
$stockTableName="stock_details";
$productId=base64_decode($_POST['productId']);
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
            'product_status'        => $_POST['pStatus']
        );
        $db=$db->where('product_id',$productId);
        $id = $db->update($tableName,$data);
        if(isset($_POST['deletedRow']) && trim($_POST['deletedRow']) != ''){
            foreach(explode(",",$_POST['deletedRow']) as $delete){
                $db=$db->where('id',$delete);
                $db->delete('options');
            }
        }
        if(isset($_POST['name']) && count($_POST['name']) > 0){

            foreach($_POST['name'] as $key=>$name){
                $optionData = array(
                    'product_id'    => $productId,
                    'option_name'   => $name,
                    'option_value'  => $_POST['value'][$key],
                    'option_status' => $_POST['status'][$key]
                );
                if(isset($_POST['optionId'][$key]) && $_POST['optionId'][$key] != ''){
                    $db=$db->where('id',$_POST['optionId'][$key]);
                    $db->update('options',$optionData);
                } else{
                    $optionId = $db->insert('options',$optionData);
                }
            }
        }
        $sQuery="SELECT * FROM stock_details WHERE product_id=".$productId;
        $stockData = array(
            'product_id'    => $productId,
            'actual_qty'    => $_POST['actualQty'],
            'actual_price'  => $_POST['productPrice'],
            'minimum_qty'   => $_POST['minimumQty'],
            'stock_status'  => 'active',
        );
        if(count($db->rawQuery($sQuery)) == 0){
            $stockId = $db->insert($stockTableName,$stockData);
        }else{
            $db=$db->where('product_id',$productId);
            $stockId = $db->update($stockTableName,$stockData);
        }
        $_SESSION['alertMsg']="Product details updated successfully";
    }
    header("location:".BASE_URL."products/product-list.php");
  
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}