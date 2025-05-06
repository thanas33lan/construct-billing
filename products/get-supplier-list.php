<?php
include('../includes/MysqliDb.php');
$text = '';
if (isset($_GET['q']) && $_GET['q'] != "") {
    $text = $_GET['q'];
    $cQuery = "SELECT * FROM supplier_details where supplier_status='active' and supplier_name like '%" . $text . "%'";
    $cResult = $db->rawQuery($cQuery);
    $echoResult = array();
    if (count($cResult) > 0) {
        foreach ($cResult as $row) {
            $echoResult[] = array("id" => $row['supplier_name'], "text" => ucwords($row['supplier_name']));
        }
    } else {
        $echoResult[] = array("id" => $text, 'text' => $text);
    }

    $result = array("result" => $echoResult);
    echo json_encode($result);
}
