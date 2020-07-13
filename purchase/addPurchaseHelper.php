<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$purchaseTable="purchase_details";
$purchaselPrdTable="purchase_product_details";
$general = new General();

try {
    
    $data=array(
        'supplier_id'=>$_POST['supplierName'],
        'purchase_no'=>$_POST['purchaseNo'],
        'purchase_on'=>($_POST['purchaseDate']!='') ? $general->dateFormat($_POST['purchaseDate']):NULL,
        'purchase_amount'=>$_POST['grandTotal'],
        'added_by'=>$_SESSION['userId'],
        'added_on'=>date('Y-m-d H:i:s')
    );
    
    $id = $db->insert($purchaseTable,$data);
    
    if($id>0){
        $c = count($_POST['prdName']);
        $lastId = $db->getInsertId();
        for($k = 0; $k < $c; $k++){
            $prdDetails = array(
                'purchase_id'=>$lastId,
                'product_id'=>$_POST['prdName'][$k],
                'purchase_qty'=>$_POST['prdQty'][$k],
                'purchase_prd_amount'=>$_POST['prdPrice'][$k],
                'purchase_line_total'=>$_POST['lineTotal'][$k],
            );
            $db->insert($purchaselPrdTable,$prdDetails);
            $lastPrdId = $db->getInsertId();
            //update product qty
            if($lastPrdId>0){
                $pQuery="SELECT * FROM product_details where product_id=".$_POST['prdName'][$k];
                $pResult = $db->rawQuery($pQuery);
                $sQuery="SELECT * FROM stock_details where product_id=".$_POST['prdName'][$k];
                $sResult = $db->rawQuery($sQuery);
                $totalQty = $pResult[0]['qty_available'] + $_POST['prdQty'][$k];
                $totalStockQty = $sResult[0]['actual_qty'] + $_POST['prdQty'][$k];

                $db=$db->where('product_id',$_POST['prdName'][$k]);
                $id = $db->update('product_details',array('qty_available'=>$totalQty));

                $db=$db->where('product_id',$_POST['prdName'][$k]);
                $stockid = $db->update('stock_details',array('actual_qty'=>$totalStockQty));
            }
            //update product qty
        }
        $_SESSION['alertMsg']="Purchase details added successfully";
    }else{
        $_SESSION['alertMsg']="Please try again!";
    }
    header("location:purchaseList.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
