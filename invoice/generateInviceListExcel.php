<?php
session_start();
ob_start();
include('../includes/MysqliDb.php');
include ('../vendor/autoload.php');
include('../includes/General.php');
$general=new General();

$filedGroup = array();

$rs_field = 'Invoice No,Invoice Date,Invoice Due Date,Client Name,Client Address,Shipping Address,Total,Collection Amount,Pending Amount,Added On';

if(isset($rs_field) && trim($rs_field)!= ''){
     //Excel code start
     $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
     $sheet = $excel->getActiveSheet();
     $styleArray = array(
     'font' => array(
         'bold' => true,
         'size' => '13',
     ),
     'alignment' => array(
         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
         'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
     ),
     'borders' => array(
         'outline' => array(
             'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
         ),
     )
    );
    $borderStyle = array(
          'alignment' => array(
              'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
          ),
          'borders' => array(
              'outline' => array(
                  'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
              ),
          )
     );
    $filedGroup = explode(",",$rs_field);
    $headings = $filedGroup;
    //Set heading row
     
     $colNo = 1;
    foreach ($headings as $field => $value) {
     $sheet->getCellByColumnAndRow($colNo, 1)->setValueExplicit(html_entity_decode($value), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
     $cellName = $sheet->getCellByColumnAndRow($colNo,1)->getColumn();
     $sheet->getStyle($cellName.'1')->applyFromArray($styleArray);
     $colNo++;
    }
    //Set query and values
    $invoiceList = $db->rawQuery($_SESSION['invoiceListQry']);
      $output = array();
      foreach($invoiceList as $aRow){
         $row = array();
         $addedDate = explode(" ",$aRow['bill_added_on']);
	        $row[] = $aRow['invoice_no'];
	        $row[] = $general->humanDateFormat($aRow['invoice_date']);
	        $row[] = ($aRow['invoice_due_date']!='')?$general->humanDateFormat($aRow['invoice_due_date']):'';
	        $row[] = $aRow['client_name'];
            $row[] = $aRow['billing_address'];
            $row[] = $aRow['shipping_address'];
            $row[] = $aRow['total_amount'];

            //sum paid mount
            $pQuery = "SELECT SUM(paid_amount) AS paid_amount FROM paid_details where bill_id=".$aRow['bill_id'];
            $paidResult = $db->query($pQuery);
            $row[] = $paidResult[0]['paid_amount'];
            $row[] = $aRow['total_amount'] - $paidResult[0]['paid_amount'];

            $row[] = $general->humanDateFormat($addedDate[0])." ".$addedDate[1];
         
        $output[] = $row;
      }
     $start = (count($output));
     foreach ($output as $rowNo => $rowData) {
          $colNo = 1;
          foreach ($rowData as $field => $value) {
            $rRowCount = $rowNo + 2;
            $cellName = $sheet->getCellByColumnAndRow($colNo,$rRowCount)->getColumn();
            $sheet->getStyle($cellName . $rRowCount)->applyFromArray($borderStyle);
            $sheet->getStyle($cellName . $start)->applyFromArray($borderStyle);
            $sheet->getDefaultRowDimension()->setRowHeight(18);
            $sheet->getColumnDimensionByColumn($colNo)->setWidth(20);
            $sheet->getCellByColumnAndRow($colNo, $rowNo + 2)->setValueExplicit(html_entity_decode($value), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getStyleByColumnAndRow($colNo, $rowNo + 2)->getAlignment()->setWrapText(true);
            $colNo++;
          }
     }
     $filename = '';
     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
     $filename = 'Inovice-list-' . date('d-M-Y-H-i-s') . '.xlsx';
     $pathFront=realpath('../temporary');
     $writer->save($pathFront. DIRECTORY_SEPARATOR . $filename);
    echo $filename;
}else{
    echo $filename = '';
}