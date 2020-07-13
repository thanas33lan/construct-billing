<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="purchase_details";
$primaryKey="purchase_id";
$db = $db->where($primaryKey,$_POST['purchaseId']);
echo $db->delete($tableName);
?>