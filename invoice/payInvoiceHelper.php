<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$billTable="bill_details";
$payTable = "paid_details";
$general = new General();

try {

    $db=$db->where('bill_id',$_POST['billId']);
    $id = $db->update($billTable,array('paid_amount'=>$_POST['paidGrandTotal']));

    //delete all paid detas
    $db=$db->where('bill_id',$_POST['billId']);
    $id = $db->delete($payTable);

    //insert paid details
    $c = count($_POST['payAmt']);
    for($l = 0; $l < $c; $l++)
    {
        $paidDetails = array('bill_id'=>$_POST['billId'],
                                'pay_option'=>$_POST['payOption'][$l],
                                'paid_amount'=>$_POST['payAmt'][$l],
                                'pay_details'=>$_POST['payDetails'][$l],
                                'paid_on'=>($_POST['paidOn'][$l]!='')?$general->dateFormat($_POST['paidOn'][$l]):NULL,
                                'agent_id'=>($_POST['agentName'][$l]!='')?$_POST['agentName'][$l]:NULL
                            );
        $db->insert($payTable,$paidDetails);
    }
    $_SESSION['alertMsg']="Paid details updated successfully";
    header("location:invoice-list.php");
}
catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}