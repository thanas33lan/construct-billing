<?php
session_start();
include('../includes/MysqliDb.php');
include_once('../includes/General.php');
$general = new General();
$tableName="expense_details";
$primaryKey="expense_id";

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */
        
        $aColumns = array('expense_date','particulars','supplier_name','user_name','quantity','price','amount','payment_status');
        
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
        
       $sQuery="SELECT * FROM expense_details as ed LEFT JOIN user_details AS ud ON ed.purchased_by = ud.user_id LEFT JOIN supplier_details AS sd ON ed.purchased_from=sd.supplier_id";
       $and = ' AND ';
       $sWhereAnd = '';
        // if (isset($sWhere) && $sWhere != "") {
        //     $sWhereAnd = 'AND';
        //     $sQuery = $sQuery.' AND ';
        // }
        if (isset($sWhere) && $sWhere != "") {
            $sWhere=' where '.$sWhere;
            $sQuery = $sQuery.' '.$sWhere;
        }
        if($_POST['purchasedFrom']!=''){
            $sWhere .= $and.' purchased_from ='.$_POST['purchasedFrom'];
        }
        if($_POST['purchasedBy']!=''){
            $sWhere .= $and.' purchased_by ='.$_POST['purchasedBy'];
        }
        if($_POST['paymentStatus']!=''){
            $sWhere .= $and.' payment_status like "%'.$_POST['paymentStatus'].'%"';
        }
        $start_date = '';
        $end_date = '';
        if(isset($_POST['expenseDate']) && trim($_POST['expenseDate'])!= ''){
            $s_c_date = explode("to", $_POST['expenseDate']);
            if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
                $start_date = $general->dateFormat(trim($s_c_date[0]));
            }
            if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
                $end_date = $general->dateFormat(trim($s_c_date[1]));
            }
            $sQuery = $sQuery.' WHERE DATE(expense_date) >= "'.$start_date.'" AND DATE(expense_date) <= "'.$end_date.'"';
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
    //    die($sQuery);
        $_SESSION['expenseListQry'] = $sQuery;
        $rResult = $db->rawQuery($sQuery);
       // print_r($rResult);
        /* Data set length after filtering */
        
        $aResultFilterTotal =$db->rawQuery("SELECT * FROM expense_details as ed LEFT JOIN user_details AS ud ON ed.purchased_by = ud.user_id LEFT JOIN supplier_details AS sd ON ed.purchased_from=sd.supplier_id");
        $iFilteredTotal = count($aResultFilterTotal);

        /* Total data set length */
        $aResultTotal =  $db->rawQuery("select COUNT(expense_id) as total FROM expense_details");
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
	        $row[] = $general->humanDateFormat($aRow['expense_date']);
	        $row[] = ucwords($aRow['particulars']);
	        $row[] = ucwords($aRow['supplier_name']);
	        $row[] = ucwords($aRow['user_name']);
	        $row[] = $aRow['quantity'];
            $row[] = $aRow['price'];
            $row[] = $aRow['amount'];
            $row[] = ucwords(str_replace('-',' ',$aRow['payment_status']));
            $row[] = '<a href="editExpense.php?id=' . base64_encode($aRow['expense_id']) . '" class="btn btn-primary btn-xs" style="margin-right: 2px;" title="Edit"><i class="fa fa-pencil"> Edit</i></a>
            <a href="javascript:void(0);" onclick="deleteExpense('.$aRow['expense_id'].');" class="btn btn-danger btn-xs" style="margin-right: 2px;" title="Delete"><i class="fa fa-trash"> Delete</i></a>';
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
?>