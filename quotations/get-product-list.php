<?php
include('../includes/MysqliDb.php');
$text = '';
if(isset($_GET['q']) && $_GET['q'] != ""){
    $text = $_GET['q'];
    $cQuery="SELECT * FROM product_details where product_status='active' and product_name like '%".$text."%'";
    $cResult = $db->rawQuery($cQuery);
    $echoResult = array();
    if(count($cResult)>0){
        foreach ($cResult as $row) {
            $echoResult[] = array("id" => $row['product_id'],"text" => ucwords($row['product_name']),"data-description"=>$row['product_description'],"data-id"=>$row['product_id'],"data-hsn"=>$row['hsn_code'],"data-price"=>$row['product_price'],"data-gst"=>$row['product_tax'],"data-qty"=>$row['qty_available'],"data-mini-qty"=>$row['minimum_qty']);
        }
    }else{
        $echoResult[] = array("id" => $text,"text" => $text,"data-description"=>'',"data-id"=>'',"data-hsn"=>'',"data-price"=>'',"data-gst"=>'',"data-qty"=>'',"data-mini-qty"=>'');
    }
    
    $result = array("result" => $echoResult);
    echo json_encode($result);
}
?>