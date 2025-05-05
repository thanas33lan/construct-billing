<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$purchaseTable = "purchase_details";
$purchaselPrdTable = "purchase_product_details";
$general = new General();

try {

    $data = array(
        'supplier_id' => $_POST['supplierName'],
        'purchase_no' => $_POST['purchaseNo'],
        'invoice_no' => $_POST['invoiceNo'],
        'purchase_on' => ($_POST['purchaseDate'] != '') ? $general->dateFormat($_POST['purchaseDate']) : NULL,
        'purchase_amount' => $_POST['grandTotal']
    );
    $purchaseId = base64_decode($_POST['purchaseId']);

    $db = $db->where('purchase_id', $purchaseId);
    $id = $db->update($purchaseTable, $data);

    if ($id > 0) {
        $c = count($_POST['prdName']);
        $lastId = $db->getInsertId();

        $prdQuery = "SELECT * FROM purchase_product_details where purchase_id=" . $purchaseId;
        $prdResult = $db->rawQuery($prdQuery);
        if (count($prdResult) > 0) {
            foreach ($prdResult as $prd) {
                $pQuery = "SELECT * FROM product_details where product_id=" . $prd['product_id'];
                $pResult = $db->rawQuery($pQuery);
                $totalQty = $pResult[0]['qty_available'] - $prd['purchase_qty'];
                $db = $db->where('product_id', $prd['product_id']);
                $id = $db->update('product_details', array('qty_available' => $totalQty));
                $db = $db->where('product_id', $prd['product_id']);
                $stockid = $db->update('stock_details', array('actual_qty' => $totalQty, 'actual_price' => $_POST['prdPrice'][$k]));
            }

            $db = $db->where('purchase_id', $purchaseId);
            $id = $db->delete($purchaselPrdTable);
        }

        for ($k = 0; $k < $c; $k++) {
            $prdDetails = array(
                'purchase_id' => $purchaseId,
                'product_id' => $_POST['prdName'][$k],
                'purchase_qty' => $_POST['prdQty'][$k],
                'purchase_prd_amount' => $_POST['prdPrice'][$k],
                'purchase_line_total' => $_POST['lineTotal'][$k],
            );
            $db->insert($purchaselPrdTable, $prdDetails);
            $lastPrdId = $db->getInsertId();
            //update product qty
            if ($lastPrdId > 0) {
                $pQuery = "SELECT * FROM product_details where product_id=" . $_POST['prdName'][$k];
                $pResult = $db->rawQuery($pQuery);
                $totalQty = $pResult[0]['qty_available'] + $_POST['prdQty'][$k];
                $db = $db->where('product_id', $_POST['prdName'][$k]);
                $id = $db->update('product_details', array('qty_available' => $totalQty));
                $db = $db->where('product_id', $_POST['prdName'][$k]);
                $stockid = $db->update('stock_details', array('actual_qty' => $totalQty, 'actual_price' => $_POST['prdPrice'][$k]));
            }
            //update product qty
        }
        $_SESSION['alertMsg'] = "Purchase details updated successfully";
    } else {
        $_SESSION['alertMsg'] = "Please try again!";
    }
    header("location:purchaseList.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
