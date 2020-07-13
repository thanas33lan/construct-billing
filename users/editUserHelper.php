<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName="user_details";
$userId=base64_decode($_POST['userId']);

try {
    if(trim($_POST['userName'])!='' && trim($_POST['loginId'])!=''){
    $data=array(
    'user_name'=>$_POST['userName'],
    'user_phone'=>$_POST['phoneNo'],
    'login_id'=>$_POST['loginId'],
    'user_status'=>$_POST['status']
    );
    
    if(isset($_POST['password']) && trim($_POST['password'])!=""){
        $passwordSalt = '0This1Is2A3Real4Complex5And6Safe7Salt8With9Some10Dynamic11Stuff12Attched13later';
        $data['password'] = sha1($_POST['password'].$passwordSalt);
    }
    
    $db=$db->where('user_id',$userId);
    $id = $db->update($tableName,$data);
    if($id>0){
        $_SESSION['alertMsg']="User details updated successfully";
    }
    }
    header("location:user-list.php");
  
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}