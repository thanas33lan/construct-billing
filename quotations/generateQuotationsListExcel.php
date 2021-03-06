<?php
session_start();
ob_start();
include('../includes/MysqliDb.php');
include ('../vendor/autoload.php');
include('../includes/General.php');
$general=new General();

$filedGroup = array();

$rs_field = 'Quotation Code, Date, Client Name, Created By, Created On, Quotation Amount';

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
    $invoiceList = $db->rawQuery($_SESSION['quotationListQry']);
      $output = array();
      foreach($invoiceList as $aRow){
        $row = array();
        
        $row[] = $aRow['q_code'];
        $row[] = $general->humanDateFormat($aRow['q_date']);
        $row[] = ucwords($aRow['client_name']);
        $row[] = ucwords($aRow['user_name']);
        $row[] = date('d-M-Y @ H:i A',strtotime($aRow['q_added_on']));
        $row[] = $aRow['grand_total'];
         
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
     $filename = 'Quotation-list-' . date('d-M-Y-H-i-s') . '.xlsx';
     $pathFront=realpath('../temporary');
     $writer->save($pathFront. DIRECTORY_SEPARATOR . $filename);
    echo $filename;
}else{
    echo $filename = '';
}