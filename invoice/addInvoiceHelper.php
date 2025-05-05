<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$billTable = "bill_details";
$billPrdTable = "bill_product_details";
$clientTable = "client_details";
$productTable = "product_details";
$stockTable = "stock_details";
$payTable = "paid_details";
$general = new General();
// print_r($_POST);die;
try {
    if (trim($_POST['invoiceNo']) != '') {

        //check already have this client
        $cQuery = "SELECT * FROM client_details where client_status='active' and client_name='" . $_POST['clientName'] . "'";
        $cResult = $db->rawQuery($cQuery);
        if (isset($cResult[0]['client_name']) && $cResult[0]['client_name'] != '') {
        } else {
            $clientData = array(
                'client_name' => $_POST['clientName'],
                'client_address' => $_POST['address'],
                'client_shipping_address' => $_POST['shipAddress'],
                'client_mobile_no' => $_POST['clientMobile'],
                'client_status' => 'active',
                'client_added_by' => $_SESSION['userId'],
                'client_added_on' => $general->getDateTime()
            );
            $db->insert($clientTable, $clientData);
        }
        $data = array(
            'invoice_no' => $_POST['invoiceNo'],
            'invoice_date' => $general->dateFormat($_POST['invoiceDate']),
            'invoice_due_date' => ($_POST['invoiceDueDate'] != '') ? $general->dateFormat($_POST['invoiceDueDate']) : NULL,
            'client_name' => $_POST['clientName'],
            'billing_address' => $_POST['address'],
            'shipping_address' => $_POST['shipAddress'],
            'total_amount' => $_POST['grandTotal'],
            'paid_amount' => $_POST['paidGrandTotal'],
            'bill_added_by' => $_SESSION['userId'],
            'bill_status' => 'pending',
            'bill_added_on' => date('Y-m-d H:i:s'),

            'term_payment' => $_POST['termPayment'],
            'supplier_ref' => $_POST['supplierRef']
        );

        $id = $db->insert($billTable, $data);

        if ($id > 0) {
            $c = count($_POST['prdName']);
            $lastId = $db->getInsertId();
            for ($k = 0; $k < $c; $k++) {
                if (!is_numeric($_POST['prdName'][$k])) {
                    $peQuery = "SELECT * FROM product_details where product_name='" . $_POST['prdName'][$k] . "'";
                    $peResult = $db->rawQuery($peQuery);
                    if (empty($peResult[0]['product_name']) && trim($peResult[0]['product_name']) == '') {
                        $pData = array(
                            'product_name'          => $_POST['prdName'][$k],
                            'product_description'   => $_POST['prdDesc'][$k],
                            'product_price'         => $_POST['prdPrice'][$k],
                            'product_tax'           => $_POST['tax'][$k],
                            'minimum_qty'           => '10',
                            'product_added_by'      => $_SESSION['userId'],
                            'product_added_on'      => date('Y-m-d H:i:s'),
                            'product_status'        => 'active'
                        );
                        $id = $db->insert('product_details', $pData);
                        $lastProductId = $db->getInsertId();
                        $stockData = array(
                            'product_id'    => $lastProductId,
                            'actual_price'  => $_POST['prdPrice'][$k],
                            'minimum_qty'   => 10,
                            'stock_status'  => 'active'
                        );
                        $stockId = $db->insert('stock_details', $stockData);
                        $_POST['prdName'][$k] = $lastProductId;
                        $_POST['productId'][$k] = $lastProductId;
                    } else {
                        $_POST['prdName'][$k] = $peResult[0]['product_id'];
                        $_POST['productId'][$k] = $peResult[0]['product_id'];
                    }
                }
                $billDetails = array(
                    'bill_id' => $lastId,
                    'product_name' => $_POST['prdName'][$k],
                    'hsn_code' => $_POST['hsnCode'][$k],
                    'sqft' => $_POST['sqft'][$k],
                    'sold_qty' => $_POST['prdQty'][$k],
                    'rate' => $_POST['prdPrice'][$k],
                    'tax' => $_POST['tax'][$k],
                    'cgst_rate' => ($_POST['cgstTax'][$k] != '') ? $_POST['cgstTax'][$k] : NULL,
                    'cgst_amount' => ($_POST['cgstAmt'][$k]) ? $_POST['cgstAmt'][$k] : NULL,
                    'sgst_rate' => ($_POST['sgstTax'][$k]) ? $_POST['sgstTax'][$k] : NULL,
                    'sgst_amount' => ($_POST['sgstAmt'][$k]) ? $_POST['sgstAmt'][$k] : NULL,
                    'igst_rate' => ($_POST['igstTax'][$k]) ? $_POST['igstTax'][$k] : NULL,
                    'igst_amount' => ($_POST['igstAmt'][$k]) ? $_POST['igstAmt'][$k] : NULL,
                    'discount' => ($_POST['discount'][$k]) ? $_POST['discount'][$k] : NULL,
                    'net_amount' => ($_POST['lineTotal'][$k]) ? $_POST['lineTotal'][$k] : NULL
                );
                $db->insert($billPrdTable, $billDetails);
                // $qtyQuery="UPDATE ".$productTable." SET qty_available = qty_available - ".(int)$_POST['prdQty'][$k].",last_paid = '".$general->getDateTime()."' WHERE product_id = ".$_POST['productId'][$k];
                // $updateResult = $db->query($qtyQuery);
                $stockQtyQuery = "UPDATE " . $stockTable . " SET quantity =  " . (int) $_POST['prdQty'][$k] . " WHERE product_id = " . $_POST['productId'][$k];
                $stockUpdateResult = $db->query($stockQtyQuery);
            }

            //insert paid details
            $p = count($_POST['payAmt']);
            for ($l = 0; $l < $p; $l++) {
                $paidDetails = array(
                    'bill_id' => $lastId,
                    'pay_option' => $_POST['payOption'][$l],
                    'paid_amount' => $_POST['payAmt'][$l],
                    'pay_details' => $_POST['payDetails'][$l],
                    'paid_on' => ($_POST['paidOn'][$l] != '') ? $general->dateFormat($_POST['paidOn'][$l]) : NULL,
                    'agent_id' => ($_POST['agentName'][$l] != '') ? $_POST['agentName'][$l] : NULL
                );
                $db->insert($payTable, $paidDetails);
            }
            $_SESSION['alertMsg'] = "Invoice details added successfully";
            $_SESSION['billId'] = $id;
        } else {
            $_SESSION['alertMsg'] = "Please try again!";
        }
    } else {
        $_SESSION['alertMsg'] = "Please try again!!";
    }
    header("location:invoice-list.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
