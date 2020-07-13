<?php
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$tableName="bill_details";
$primaryKey="bill_id";
$general = new General();

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */
        
        $aColumns = array('invoice_no',"DATE_FORMAT(invoice_date,'%d-%b-%Y')","DATE_FORMAT(invoice_due_date,'%d-%b-%Y')",'client_name','billing_address','shipping_address','total_amount',"DATE_FORMAT(bill_added_on,'%d-%b-%Y %H:%i:%s')");
        $orderColumns = array('invoice_no','invoice_date','invoice_due_date','client_name','billing_address','shipping_address','total_amount','bill_added_on');
        
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
                    $sOrder .= $orderColumns[intval($_POST['iSortCol_' . $i])] . "
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
        
       $sQuery="SELECT * FROM bill_details";
        
        if (isset($sWhere) && $sWhere != "") {
            $sWhere=' where '.$sWhere;
            $sQuery = $sQuery.' '.$sWhere;
        }

        $start_date = '';
          $end_date = '';
          if(isset($_POST['invoiceDate']) && trim($_POST['invoiceDate'])!= ''){
               $s_c_date = explode("to", $_POST['invoiceDate']);
               if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
                    $start_date = $general->dateFormat(trim($s_c_date[0]));
               }
               if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
                    $end_date = $general->dateFormat(trim($s_c_date[1]));
               }
          }
          if(isset($_POST['invoiceDate']) && trim($_POST['invoiceDate'])!= ''){
            if($sWhere!='')
            {
                $sWhere = $sWhere.' AND DATE(invoice_date) >= "'.$start_date.'" AND DATE(invoice_date) <= "'.$end_date.'"';
            }else{
                $sWhere = ' where DATE(invoice_date) >= "'.$start_date.'" AND DATE(invoice_date) <= "'.$end_date.'"';
            }
            
       }
       $sQuery = $sQuery.$sWhere;
        if (isset($sOrder) && $sOrder != "") {
            $sOrder = preg_replace('/(\v|\s)+/', ' ', $sOrder);
            $sQuery = $sQuery.' order by '.$sOrder;
        }
        
        if (isset($sLimit) && isset($sOffset)) {
            $sQuery = $sQuery.' LIMIT '.$sOffset.','. $sLimit;
        }
       //die($sQuery);
       //echo $sQuery;
        $_SESSION['invoiceListQry'] = $sQuery;
        $rResult = $db->rawQuery($sQuery);
       // print_r($rResult);
        /* Data set length after filtering */
        
        $aResultFilterTotal =$db->rawQuery("SELECT * FROM bill_details $sWhere order by $sOrder");
        $iFilteredTotal = count($aResultFilterTotal);

        /* Total data set length */
        $aResultTotal =  $db->rawQuery("select COUNT(bill_id) as total FROM bill_details");
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
            $addedDate = explode(" ",$aRow['bill_added_on']);
	        $row[] = $aRow['invoice_no'];
	        $row[] = $general->humanDateFormat($aRow['invoice_date']);
	        $row[] = ($aRow['invoice_due_date']!='')?$general->humanDateFormat($aRow['invoice_due_date']):'';
	        $row[] = $aRow['client_name'];
            $row[] = $aRow['billing_address'];
            $row[] = $aRow['shipping_address'];
            $row[] = $aRow['total_amount'];
            $row[] = $general->humanDateFormat($addedDate[0])." ".$addedDate[1];
            $row[] = '<a href="/invoice/generatePdf.php?id='.base64_encode($aRow['bill_id']).'" target="_blank" class="btn btn-primary btn-xs" style="margin-right: 2px;padding-bottom:2px;" title="Print Invoice -' .$aRow['invoice_no'].'"><i class="fa fa-file-pdf-o"> PDF</i></a>
                    <a href="updatePdf.php?id=' . base64_encode($aRow['bill_id']) . '" class="btn btn-primary btn-xs" style="margin-right: 2px;" title="Update PDF Details" ><i class="fa fa-file-pdf-o"> Fill PDF</i></a>
                    <a style="margin:2px 0px;" href="payInvoice.php?id=' . base64_encode($aRow['bill_id']) . '" class="btn btn-success btn-xs" style="margin-right: 2px;" title="Pay"><i class="fa fa-money"> Pay</i></a>';
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
?>