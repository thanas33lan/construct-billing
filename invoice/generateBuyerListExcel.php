<?php
try {
	session_start();
	ob_start();
	include('../includes/MysqliDb.php');
	include('../vendor/autoload.php');
	include('../includes/General.php');
} catch (Exception $e) {
	error_log($e->getMessage());
}
$general = new General();

$filedGroup = array();

$rs_field = 'Invoice Number,Invoice Date,Invoice Due Date,Client Name,Total,Collection Amount,Pending Amount,Added On';

if (isset($rs_field) && trim($rs_field) != '') {
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
	$filedGroup = explode(",", $rs_field);
	$headings = $filedGroup;
	//Set heading row

	$colNo = 1;
	foreach ($headings as $field => $value) {
		$sheet->getCellByColumnAndRow($colNo, 1)->setValueExplicit(html_entity_decode($value), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
		$cellName = $sheet->getCellByColumnAndRow($colNo, 1)->getColumn();
		$sheet->getStyle($cellName . '1')->applyFromArray($styleArray);
		$colNo++;
	}
	//Set query and values
	$buyerList = $db->rawQuery($_SESSION['buyerListQry']);
	$output = array();
	foreach ($buyerList as $aRow) {
		$addedDate = explode(" ",$aRow['bill_added_on']);
		$row = array();
		$row[] = $aRow['invoice_no'];
		$row[] = $general->humanDateFormat($aRow['invoice_date']);
		$row[] = $general->humanDateFormat($aRow['invoice_due_date']);
		$row[] = $aRow['client_name'];
		$row[] = $aRow['total_amount'];
		$row[] = $aRow['paid_amount'];
		$row[] = $aRow['total_amount'] - $aRow['paid_amount'];
		$row[] = $general->humanDateFormat($addedDate[0])." ".$addedDate[1];
		$output[] = $row;
	}
	$start = (count($output));
	foreach ($output as $rowNo => $rowData) {
		$colNo = 1;
		foreach ($rowData as $field => $value) {
			$rRowCount = $rowNo + 2;
			$cellName = $sheet->getCellByColumnAndRow($colNo, $rRowCount)->getColumn();
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
	$filename = 'BuyerHistory-list-' . date('d-M-Y-H-i-s') . '.xlsx';
	$pathFront = realpath('../temporary');
	$writer->save($pathFront . DIRECTORY_SEPARATOR . $filename);
	echo $filename;
} else {
	echo $filename = '';
}
