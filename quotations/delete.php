<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="quotations";
$primaryKey="q_id";
$table2Name="quotations_products_map";
$primary2Key="q_id";
$db = $db->where($primary2Key,$_POST['quotationsId']);
$db->delete($table2Name);
$db = $db->where($primaryKey,$_POST['quotationsId']);
echo $db->delete($tableName);
?>