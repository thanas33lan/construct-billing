<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName="agent_details";
$agentId=base64_decode($_POST['agentId']);

try {
    if(trim($_POST['agentName'])!=''  && trim($_POST['phoneNo'])!=''){
    $data=array(
        'agent_name'=>$_POST['agentName'],
        'agent_email'=>$_POST['emailId'],
        'agent_phone'=>$_POST['phoneNo'],
        'alter_phone_number'=>$_POST['alterPhoneNo'],
        'agent_status'=>$_POST['status']
    );
    
    $db=$db->where('agent_id',$agentId);
    $id = $db->update($tableName,$data);
    if($id>0){
        $_SESSION['alertMsg']="Agent details updated successfully";
    }
    }
    header("location:agentList.php");
  
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}