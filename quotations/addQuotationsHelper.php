<?php
ob_start();
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$quotationTable = "quotations";
$quotationPrdTable = "quotations_products_map";
$clientTable = "client_details";
$productTable = "product_details";
$general = new General();
$lastClientId = 0;
$lastQId = 0;
// print_r($_POST);die;
try {
    if (isset($_POST['prdName']) && count($_POST['prdName']) > 0) {

        //check already have this client
        $cQuery = "SELECT * FROM client_details where client_status='active' and client_name='" . $_POST['clientName'] . "'";
        $cResult = $db->rawQuery($cQuery);
        if (isset($cResult[0]['client_name']) && $cResult[0]['client_name'] != '') { } else {
            $clientData = array(
                'client_name' => $_POST['clientName'],
                'client_address' => $_POST['clientAddress'],
                'client_status' => 'active',
                'client_added_by' => $_SESSION['userId'],
                'client_added_on' => $general->getDateTime()
            );
            $db->insert($clientTable, $clientData);
            $lastClientId = $db->getInsertId();
        }
        $data = array(
            'q_customer'    => isset($cResult[0]['client_id'])?$cResult[0]['client_id']:$lastClientId,
            'q_code'        => $_POST['quotationsNo'],
            'enquiry_date'  => $general->dateFormat($_POST['enquiryDate']),
            'q_date'        => $general->dateFormat($_POST['quotationsDate']),
            'grand_total'   => $_POST['grandTotal'],
            'q_added_by'    => $_SESSION['userId'],
            'q_added_on'    => $general->getDateTime(),
        );

        $id = $db->insert($quotationTable, $data);
        
        if ($id > 0) {
            $c = count($_POST['prdName']);
            $lastQId = $db->getInsertId();
            for ($k = 0; $k < $c; $k++) {
                if(!is_numeric($_POST['prdName'][$k])){
                    $pData=array(
                        'product_name'          => $_POST['prdName'][$k],
                        'product_description'   => $_POST['prdDesc'][$k],
                        'product_price'         => $_POST['prdPrice'][$k],
                        'minimum_qty'           => '10',
                        'product_added_by'      => $_SESSION['userId'],
                        'product_added_on'      => date('Y-m-d H:i:s'),
                        'product_status'        => 'active'
                    );
                    $id = $db->insert('product_details',$pData);
                    $lastProductId = $db->getInsertId();
                    $stockData = array(
                        'product_id'    => $lastProductId,
                        'actual_price'  => $_POST['prdPrice'][$k],
                        'minimum_qty'   => 10,
                        'stock_status'  => 'active'
                    );
                    $stockId = $db->insert('stock_details',$stockData);
                    $_POST['prdName'][$k] = $lastProductId;
                }
                $quotationDetails = array(
                    'q_id' => $lastQId,
                    'product_id' => $_POST['prdName'][$k],
                    'sqft' => $_POST['sqft'][$k],
                    'p_price' => $_POST['prdPrice'][$k],
                    'p_qty' => $_POST['prdQty'][$k],
                    'discount' => ($_POST['discount'][$k]) ? $_POST['discount'][$k] : NULL,
                    'line_total' => ($_POST['lineTotal'][$k]) ? $_POST['lineTotal'][$k] : NULL
                );
                $db->insert($quotationPrdTable, $quotationDetails);
            }
            $_SESSION['alertMsg'] = "Quotation details added successfully";
            $_SESSION['quotationId'] = $id;
        } else {
            $_SESSION['alertMsg'] = "Please try again!";
        }
    } else {
        $_SESSION['alertMsg'] = "Please select the products!";
    }
    header("location:quotations-list.php");
} catch (Exception $exc) {
    error_log($exc->getMessage());
    error_log($exc->getTraceAsString());
}
