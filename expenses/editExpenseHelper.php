<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
$tableName="expense_details";
$expenseId=base64_decode($_POST['expenseId']);

try {
    if(trim($_POST['particulars'])!='' && trim($_POST['particulars'])!=''){
    
        if(isset($_POST['particulars']) && trim($_POST['particulars'])!=""){
            $data['particulars'] = $_POST['particulars'];
        }
        if(isset($_POST['purchasedFrom']) && trim($_POST['purchasedFrom'])!=""){
            $data['purchased_from'] = base64_decode($_POST['purchasedFrom']);
        }
        if(isset($_POST['purchasedBy']) && trim($_POST['purchasedBy'])!=""){
            $data['purchased_by'] = base64_decode($_POST['purchasedBy']);
        }
        if(isset($_POST['quantity']) && trim($_POST['quantity'])!=""){
            $data['quantity'] = $_POST['quantity'];
        }
        if(isset($_POST['price']) && trim($_POST['price'])!=""){
            $data['price'] = $_POST['price'];
        }
        if(isset($_POST['amount']) && trim($_POST['amount'])!=""){
            $data['amount'] = $_POST['amount'];
        }
        if(isset($_POST['paymentStatus']) && trim($_POST['paymentStatus'])!=""){
            $data['payment_status'] = $_POST['paymentStatus'];
        }
        if(isset($_POST['remarks']) && trim($_POST['remarks'])!=""){
            $data['remarks'] = $_POST['remarks'];
        }
        
        $db=$db->where('expense_id',$expenseId);
        $id = $db->update($tableName,$data);
        if($id>0){
            $_SESSION['alertMsg']="Expense details updated successfully";
        }
    }
    header("location:expense-list.php");
  
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}