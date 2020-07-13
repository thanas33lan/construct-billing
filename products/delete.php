<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="product_details";
$primaryKey="product_id";
$db = $db->where('product_id',$_POST['productId']);
$purPDId = $db->delete('purchase_product_details');
if($purPDId > 0){
    $db = $db->where('product_id',$_POST['productId']);
    $stockId = $db->delete('stock_details');
}
if($stockId > 0){
    $db = $db->where($primaryKey,$_POST['productId']);
    echo $db->delete($tableName);
}
?>