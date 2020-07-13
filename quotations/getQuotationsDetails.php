<?php
session_start();
include('../includes/MysqliDb.php');
include('../includes/General.php');
$tableName="quotations";
$primaryKey="q_id";
$general = new General();

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
        */
        
        $aColumns = array('q_code', "DATE_FORMAT(q_date,'%d-%b-%Y')",'client_name', 'user_name','q_added_on','grand_total');
        $orderColumns = array('q_id','q_code', 'q_date', 'client_name','user_name','q_added_on','grand_total');
        
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
       $sQuery="SELECT q.q_id,q.q_code,q.q_date,cd.client_name,ud.user_name,q.q_added_on,q.grand_total FROM quotations AS q LEFT JOIN client_details AS cd ON q.q_customer=cd.client_id LEFT JOIN user_details AS ud ON q.q_added_by=ud.user_id";
        
        if (isset($sWhere) && $sWhere != "") {
            $sWhere=' where '.$sWhere;
            $sQuery = $sQuery.' '.$sWhere;
        }

        $start_date = '';
          $end_date = '';
          if(isset($_POST['quotationsDate']) && trim($_POST['quotationsDate'])!= ''){
               $s_c_date = explode("to", $_POST['quotationsDate']);
               if (isset($s_c_date[0]) && trim($s_c_date[0]) != "") {
                    $start_date = $general->dateFormat(trim($s_c_date[0]));
               }
               if (isset($s_c_date[1]) && trim($s_c_date[1]) != "") {
                    $end_date = $general->dateFormat(trim($s_c_date[1]));
               }
          }
          if(isset($_POST['quotationsDate']) && trim($_POST['quotationsDate'])!= ''){
            if($sWhere!='')
            {
                $sWhere = $sWhere.' AND DATE(q_date) >= "'.$start_date.'" AND DATE(q_date) <= "'.$end_date.'"';
            }else{
                $sWhere = ' where DATE(q_date) >= "'.$start_date.'" AND DATE(q_date) <= "'.$end_date.'"';
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
        // echo $sQuery;die;
        $_SESSION['quotationListQry'] = $sQuery;
        $rResult = $db->rawQuery($sQuery);
        
        /* Data set length after filtering */
        $aResultFilterTotal =$db->rawQuery("SELECT * FROM quotations $sWhere order by $sOrder");
        $iFilteredTotal = count($aResultFilterTotal);
        /* Total data set length */
        $aResultTotal =  $db->rawQuery("select COUNT(q_id) as total FROM quotations");
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
        $delete = "";
        $update = "";
        foreach ($rResult as $aRow) {
            $row = array();
            
	        $row[] = $aRow['q_code'];
	        $row[] = $general->humanDateFormat($aRow['q_date']);
	        $row[] = ucwords($aRow['client_name']);
            $row[] = ucwords($aRow['user_name']);
            $row[] = date('d-M-Y @ H:i A',strtotime($aRow['q_added_on']));
            $row[] = $aRow['grand_total'];
            $delete = '<a href="javascript:void(0)" class="btn btn-danger btn-xs" style="margin-right: 2px;padding-bottom:2px;" title="Want to delete this '.$aRow['q_code'].'" onclick="deleteQuotations('.$aRow['q_id'].')"><i class="fa fa-trash"> Delete</i></a>';
            $update = '<a href="/quotations/generatePdf.php?id='.base64_encode($aRow['q_id']).'" target="_blank" class="btn btn-primary btn-xs" style="margin-right: 2px;padding-bottom:2px;" title="Print Quotation - '.$aRow['q_code'].'"><i class="fa fa-file-pdf-o"> PDF</i></a><a href="viewQuotation.php?id=' . base64_encode($aRow['q_id']) . '" class="btn btn-primary btn-xs" style="margin-right: 2px;" title="View"><i class="fa fa-eye"> View</i></a>';
            $row[] = $update.$delete;
            $output['aaData'][] = $row;
        }
        echo json_encode($output);
?>