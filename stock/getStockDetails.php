<?php
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$tableName="stock_details";
$primaryKey="stock_id";
$general = new General();

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */
        
        $aColumns = array('pd.product_name','sd.actual_qty','sd.minimum_qty','sd.quantity','sd.actual_price','sd.stock_status');
        
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
                    $sOrder .= $aColumns[intval($_POST['iSortCol_' . $i])] . "
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
        
       $sQuery="SELECT pd.product_id,pd.product_name,sd.stock_id,sd.actual_qty,sd.minimum_qty,sd.quantity,sd.actual_price,sd.stock_status FROM stock_details as sd INNER JOIN product_details as pd ON pd.product_id=sd.product_id";
       $and = ' AND ';
       $sWhereAnd = '';
        if (isset($sWhere) && $sWhere != "") {
            $sWhereAnd = 'AND';
            $sQuery = $sQuery.' AND ';
        }
        if($_POST['prdName']!=''){
            $sWhere .= $and.' pd.product_name like "%'.$_POST['prdName'].'%"';
        }
        if($_POST['actualQty']!=''){
            $sWhere .= $and.' sd.actual_qty like "%'.$_POST['actualQty'].'%"';
        }
       if($_POST['minimumQty']!=''){
            $sWhere .= $and.' sd.minimum_qty like "%'.$_POST['minimumQty'].'%"';
        }
        if($_POST['status']!=''){
            $sWhere .= $and.' sd.stock_status="'.$_POST['status'].'"';
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
        $_SESSION['stockQuery'] = $sQuery;
        $rResult = $db->rawQuery($sQuery);
       // print_r($rResult);
        /* Data set length after filtering */
        
        $aResultFilterTotal =$db->rawQuery("SELECT pd.product_id,pd.product_name,sd.stock_id,sd.actual_qty,sd.minimum_qty,sd.quantity,sd.actual_price,sd.stock_status FROM stock_details as sd INNER JOIN product_details as pd ON pd.product_id=sd.product_id where sd.actual_qty IS NOT NULL $sWhereAnd $sWhere order by $sOrder");
        $iFilteredTotal = count($aResultFilterTotal);

        /* Total data set length */
        $aResultTotal =  $db->rawQuery("select COUNT(product_id) as total FROM stock_details");
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
            if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'tile-admin' || $_SESSION['userLoginId'] == 'admin'){
                $row[] = "<a href='javascript:void(0);' id='1' onclick='showDetails(\"".$aRow['product_id']."\",this)'><i class='fa fa-plus'></i></a>";
            }
	        $row[] = ucwords($aRow['product_name']);
            $row[] = $aRow['actual_qty'];
            $row[] = $aRow['minimum_qty'];
            $row[] = $aRow['quantity'];
            $row[] = (int)$aRow['actual_qty']-(int)$aRow['quantity'];
            if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'tile-admin' || $_SESSION['userLoginId'] == 'admin'){
                $row[] = number_format($aRow['actual_price'],2);
            }
            $row[] = '<a href="/products/editProduct.php?id=' . base64_encode($aRow['product_id']) . '" class="btn btn-primary btn-xs" style="margin-right: 2px;" title="Edit"><i class="fa fa-pencil"> Edit</i></a>';
            /* <a href="javascript:void(0);" onclick="deleteStock('.$aRow['stock_id'].');" class="btn btn-danger btn-xs" style="margin-right: 2px;" title="Delete"><i class="fa fa-trash"> Delete</i></a> */
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
?>