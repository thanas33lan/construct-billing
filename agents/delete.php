<?php
session_start();
include('../includes/MysqliDb.php');
$tableName="agent_details";
$primaryKey="agent_id";
$db = $db->where($primaryKey,$_POST['agentId']);
echo $db->delete($tableName);
?>