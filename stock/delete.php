<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="stock_details";
$primaryKey="stock_id";
$db = $db->where($primaryKey,$_POST['stockId']);
echo $db->delete($tableName);
?>