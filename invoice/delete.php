<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="bill_details";
$primaryKey="bill_id";
$db = $db->where($primaryKey,$_POST['billId']);
echo $db->delete($tableName);
?>