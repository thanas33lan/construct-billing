<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName="user_details";
try {
    if(trim($_POST['userName'])!='' && trim($_POST['loginId'])!='' && trim($_POST['password'])!=''){
    $passwordSalt = '0This1Is2A3Real4Complex5And6Safe7Salt8With9Some10Dynamic11Stuff12Attched13later';
    $password = sha1($_POST['password'].$passwordSalt);
    $data=array(
    'user_name'=>$_POST['userName'],
    'login_id'=>$_POST['loginId'],
    'user_phone'=>$_POST['phoneNo'],
    'password'=>$password,
    'user_status'=>'active'
    );
    $id = $db->insert($tableName,$data);
        if($id>0){
            $_SESSION['alertMsg']="User details added successfully";
        }else{
            $_SESSION['alertMsg']="Please try again!";
        }
    }
    header("location:user-list.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}