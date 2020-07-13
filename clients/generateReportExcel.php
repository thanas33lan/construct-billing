<?php
session_start();
ob_start();
include('../includes/MysqliDb.php');
include ('../vendor/autoload.php');
include('../includes/General.php');
$general=new General();

$filedGroup = array();

$rs_field = 'Client Name,Mobile,Alternate Mobile,GST No,Email,Client Address,Shipping Address,Client Created By,Created On,Client Status';

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
    $reportList = $db->rawQuery($_SESSION['clientQuery']);
      $output = array();
      foreach($reportList as $aRow){
        $row = array();
        $row[] = ucwords($aRow['client_name']);
        $row[] = $aRow['client_mobile_no'];
        $row[] = $aRow['alter_phone_number'];
        $row[] = $aRow['gst_no'];
        $row[] = $aRow['client_email_id'];
        $row[] = $aRow['client_address'];
        $row[] = $aRow['client_shipping_address'];
        $row[] = ucwords($aRow['user_name']);
        $row[] = date('d-M-Y h:i:s \P\M', strtotime($aRow['client_added_on']));
        $row[] = ucwords($aRow['client_status']);
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
     $filename = 'Client-report-' . date('d-M-Y-H-i-s') . '.xlsx';
     $pathFront=realpath('../temporary');
     $writer->save($pathFront. DIRECTORY_SEPARATOR . $filename);
    echo $filename;
}else{
    echo $filename = '';
}