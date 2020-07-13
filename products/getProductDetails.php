<?php
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$tableName="product_details";
$primaryKey="product_id";
$general = new General();

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */
        
        $aColumns = array('product_name','hsn_code','qty_available','minimum_qty','product_price','product_tax','last_paid');
        $oColumns = array('product_id','product_name','hsn_code','qty_available','minimum_qty','product_price','product_tax','last_paid');
        
        /* Indexed column (used for fast and accurate table cardinality) */
        $sIndexColumn = $primaryKey;
        
        $sTable = $tableName;
        /*
         * Paging
         */
        $sLimit = "";
        if (isset($_POST['iDisplayStart']) && $_POST['iDisplayLength'] != '-1') {
            $sOffset = $_POST['iDisplayStart'];
            $sLimit = $_POST['iDisplayLength'];
        }
        
        /*
         * Ordering
        */
        
        $sOrder = "";
        if (isset($_POST['iSortCol_0'])) {
            $sOrder = "";
            for ($i = 0; $i < intval($_POST['iSortingCols']); $i++) {
                if ($_POST['bSortable_' . intval($_POST['iSortCol_' . $i])] == "true") {
                    $sOrder .= $oColumns[intval($_POST['iSortCol_' . $i])] . "
				 	" . ( $_POST['sSortDir_' . $i] ) . ", ";
                }
            }
            $sOrder = substr_replace($sOrder, "", -2);
        }
        
        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
        */
        
        $sWhere = "";
        if (isset($_POST['sSearch']) && $_POST['sSearch'] != "") {
            $searchArray = explode(" ", $_POST['sSearch']);
            $sWhereSub = "";
            foreach ($searchArray as $search) {
                if ($sWhereSub == "") {
                    $sWhereSub .= "(";
                } else {
                    $sWhereSub .= " AND (";
                }
                $colSize = count($aColumns);
                
                for ($i = 0; $i < $colSize; $i++) {
                    if ($i < $colSize - 1) {
                        $sWhereSub .= $aColumns[$i] . " LIKE '%" . ($search ) . "%' OR ";
                    } else {
                        $sWhereSub .= $aColumns[$i] . " LIKE '%" . ($search ) . "%' ";
                    }
                }
                $sWhereSub .= ")";
            }
            $sWhere .= $sWhereSub;
        }
        
        /* Individual column filtering */
        for ($i = 0; $i < count($aColumns); $i++) {
            if (isset($_POST['bSearchable_' . $i]) && $_POST['bSearchable_' . $i] == "true" && $_POST['sSearch_' . $i] != '') {
                if ($sWhere == "") {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . ($_POST['sSearch_' . $i]) . "%' ";
                } else {
                    $sWhere .= " AND " . $aColumns[$i] . " LIKE '%" . ($_POST['sSearch_' . $i]) . "%' ";
                }
            }
        }
        
        /*
         * SQL queries
         * Get data to display
        */
        
       $sQuery="SELECT * FROM product_details where product_name IS NOT NULL";
        
       $and = ' AND ';
       $sWhereAnd = '';
        if (isset($sWhere) && $sWhere != "") {
            $sWhereAnd = 'AND';
            $sQuery = $sQuery.' AND ';
        }
        if($_POST['prdName']!=''){
            $sWhere .= $and.' product_name like "%'.$_POST['prdName'].'%"';
        }
        if($_POST['hsnCode']!=''){
            $sWhere .= $and.' hsn_code like "%'.$_POST['hsnCode'].'%"';
        }
        if($_POST['tax']!=''){
            $sWhere .= $and.' product_tax like "%'.$_POST['tax'].'%"';
        }
        if($_POST['minimumStock']!=''){
            $sWhere .= $and.' minimum_qty like "%'.$_POST['minimumStock'].'%"';
        }
        if($_POST['status']!=''){
            $sWhere .= $and.' product_status="'.$_POST['status'].'"';
        }

        if (isset($sWhere) && $sWhere != "") {
            $sQuery = $sQuery.' '.$sWhere;
        }
        
        if (isset($sOrder) && $sOrder != "") {
            $sOrder = preg_replace('/(\v|\s)+/', ' ', $sOrder);
            $sQuery = $sQuery.' order by '.$sOrder;
        }
        
        if (isset($sLimit) && isset($sOffset)) {
            $sQuery = $sQuery.' LIMIT '.$sOffset.','. $sLimit;
        }
       //die($sQuery);
       //echo $sQuery;die;
        $_SESSION['productQuery'] = $sQuery;
        $rResult = $db->rawQuery($sQuery);
       // print_r($rResult);
        /* Data set length after filtering */
        
        $aResultFilterTotal =$db->rawQuery("SELECT * FROM product_details where product_name IS NOT NULL $sWhereAnd $sWhere order by $sOrder");
        $iFilteredTotal = count($aResultFilterTotal);

        /* Total data set length */
        $aResultTotal =  $db->rawQuery("select COUNT(product_id) as total FROM product_details");
       // $aResultTotal = $countResult->fetch_row();
       //print_r($aResultTotal);
        $iTotal = $aResultTotal[0]['total'];

        /*
         * Output
        */
        $output = array(
            "sEcho" => intval($_POST['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        foreach ($rResult as $aRow) {
            $row = array();
	        $row[] = ucwords($aRow['product_name']);
            $row[] = $aRow['hsn_code'];
            // $row[] = $aRow['qty_available'];
            // $row[] = $aRow['minimum_qty'];
            $row[] = number_format($aRow['product_price'],2);
            $row[] = $aRow['product_tax'];
            if($aRow['last_paid'] != NULL){
                $row[] = date('d-M-Y h:i:s \P\M', strtotime($aRow['last_paid']));
            }else{
                $row[] = $aRow['last_paid'];
            }
            $row[] = '<a href="editProduct.php?id=' . base64_encode($aRow['product_id']) . '" class="btn btn-primary btn-xs" style="margin-right: 2px;" title="Edit"><i class="fa fa-pencil"> Edit</i></a>
            <a href="javascript:void(0);" onclick="deleteProduct('.$aRow['product_id'].');" class="btn btn-danger btn-xs" style="margin-right: 2px;" title="Delete"><i class="fa fa-trash"> Delete</i></a>';
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
?>