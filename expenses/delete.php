<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="expense_details";
$primaryKey="expense_id";
$db = $db->where($primaryKey,$_POST['expenseId']);
echo $db->delete($tableName);
?>