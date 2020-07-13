<?php
ob_start();
session_start();

include('../base-url.php');
include('../includes/MysqliDb.php');
$tableName="product_details";
$stockTableName="stock_details";
$productId=base64_decode($_POST['productId']);
echo "<pre>";
print_r($_POST);die;
try {
    if(trim($_POST['productName'])!='' && trim($_POST['productPrice'])!='' && trim($_POST['hsnCode'])!=''){
        $data=array(
            'product_name'  => $_POST['productName'],
            'product_description'   => $_POST['prdDesc'],
            'supplier_id'   => $_POST['supplier'],
            'hsn_code'      => $_POST['hsnCode'],
            'product_price' => $_POST['productPrice'],
            'qty_available' => $_POST['actualQty'],
            'minimum_qty'   => $_POST['minimumQty'],
            'product_tax'   => $_POST['tax'],
            'product_status'=> $_POST['status']
        );
        $db=$db->where('product_id',$productId);
        $id = $db->update($tableName,$data);
        $sQuery="SELECT * FROM stock_details WHERE product_id=".$productId;
        $stockData = array(
            'product_id'    => $productId,
            'actual_qty'    => $_POST['actualQty'],
            'actual_price'  => $_POST['productPrice'],
            'minimum_qty'   => $_POST['minimumQty'],
            'stock_status'  => $_POST['stockStatus']
        );
        if(count($db->rawQuery($sQuery)) == 0){
            $stockId = $db->insert($stockTableName,$stockData);
        }else{
            $db=$db->where('product_id',$productId);
            $stockId = $db->update($stockTableName,$stockData);
        }
        if($id>0 || $stockId > 0){
            $_SESSION['alertMsg']="Product details updated successfully";
        }
    }
    header("location:".BASE_URL."products/product-list.php");
  
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}