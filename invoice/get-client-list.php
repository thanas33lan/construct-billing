<?php
include('../includes/MysqliDb.php');
$text = '';
if(isset($_GET['q']) && $_GET['q'] != ""){
    $text = $_GET['q'];
    $cQuery="SELECT * FROM client_details where client_status='active' and client_name like '%".$text."%'";
    $cResult = $db->rawQuery($cQuery);
    $echoResult = array();
    if(count($cResult)>0){
        foreach ($cResult as $row) {
            $echoResult[] = array("id" => $row['client_name'],"text" => ucwords($row['client_name']),"billing_address"=>$row['client_address'],"shipping_address"=>$row['client_shipping_address'],"mobile"=>$row['client_mobile_no']);
        }
    }else{
        $echoResult[] = array("id" => $text,'text'=>$text,"billing_address"=>'',"shipping_address"=>'');
    }
    
    $result = array("result" => $echoResult);
    echo json_encode($result);
}
?>