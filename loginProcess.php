<?php
session_start();
ob_start();
include('includes/MysqliDb.php');
include('includes/General.php');

$general=new General($db);

try {
    if(isset($_POST['loginId']) && trim($_POST['loginId'])!="" && isset($_POST['password']) && trim($_POST['password'])!=""){
        $passwordSalt = '0This1Is2A3Real4Complex5And6Safe7Salt8With9Some10Dynamic11Stuff12Attched13later';
        $password = sha1($_POST['password'].$passwordSalt);
        
        if($_POST['loginId'] == 'tile-admin' && $password == '340257a7b31f401b2174e8ed51bf87385d8a6d16'){
            $_SESSION['userLoginId'] = 'tile-admin';
            $_SESSION['userId'] = 1;
            $_SESSION['loginId'] = 'Tile Shop';
            $redirect = 'invoice/create-invoice.php';
            header("location:".$redirect);
        }else{
            $adminloginId=$db->escape($_POST['loginId']);
            $adminPassword=$db->escape($password);
            $adminQuery = "SELECT * FROM user_details as ud WHERE ud.login_id = '$adminloginId' AND ud.password = '$adminPassword'";
            $admin = $db->rawQuery($adminQuery);
            if(count($admin)>0){
                if($admin[0]['user_status']=='active'){
                    $_SESSION['userId']=$admin[0]['user_id'];
                    $_SESSION['loginId']=ucwords($admin[0]['user_name']);
                    $_SESSION['userLoginId']=$admin[0]['login_id'];
                    $redirect = 'invoice/create-invoice.php';
                    header("location:".$redirect);
                }else{
                    header("location:login.php");
                    $_SESSION['alertMsg']="Your status is inactive.Please contact admin.";    
                }
                
            }else{
                header("location:login.php");
                $_SESSION['alertMsg']="Please check your login credential";
            }
        }
    }else{
        header("location:login.php");
    }
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
