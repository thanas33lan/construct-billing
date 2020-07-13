<?php
session_start();
include('../includes/MysqliDb.php');


$suplierQry = "select * from supplier_details";
$rResult = $db->rawQuery($suplierQry);
$compareResult = "<h3>Compare Details</h3>";
    $compareResult .= "<table class='table table-bordered table-striped'>";
    $compareResult .= "<tr><th>Supplier Name</th><th>Purchase Amount</th><th>Purchased On</th></tr>";
    if(count($rResult)>0){
        foreach($rResult as $val)
        {

            $sQuery="SELECT sd.supplier_name,sd.supplier_id,prd.product_name,pd.purchase_on,prd.product_id,pd.purchase_id,pd.supplier_id,ppd.purchase_prd_amount FROM supplier_details as sd INNER JOIN purchase_details as pd ON pd.supplier_id=sd.supplier_id INNER JOIN purchase_product_details as ppd ON ppd.purchase_id=pd.purchase_id INNER JOIN product_details as prd ON prd.product_id=ppd.product_id where prd.product_id='".$_POST['prdId']."' AND pd.supplier_id='".$val['supplier_id']."' order by pd.purchase_on DESC limit 1";
            $prdResult = $db->rawQuery($sQuery);

            if(isset($prdResult[0]['purchase_on'])){
            $compareResult .="<tr>";
            $compareResult .= "<td>".ucwords($val['supplier_name'])."</td>";
            $compareResult .= "<td><i class='fa fa-inr'></i>&nbsp;&nbsp;".$prdResult[0]['purchase_prd_amount']."</td>";
            $compareResult .= "<td>".date('d-M-Y',strtotime($prdResult[0]['purchase_on']))."</td>";
            $compareResult .="</tr>";
            }
        }
    }else{
        $compareResult .= "<td>No Data Available!</td>";
    }

    echo $compareResult;
?>