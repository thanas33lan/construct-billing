<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="supplier_details";
$primaryKey="supplier_id";
$db = $db->where($primaryKey,$_POST['supplierId']);
echo $db->delete($tableName);
?>