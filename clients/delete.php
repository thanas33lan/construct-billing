<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="client_details";
$primaryKey="client_id";
$db = $db->where($primaryKey,$_POST['clientId']);
echo $db->delete($tableName);
?>