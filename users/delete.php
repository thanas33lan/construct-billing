<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="user_details";
$primaryKey="user_id";
$db = $db->where($primaryKey,$_POST['userId']);
echo $db->delete($tableName);
?>