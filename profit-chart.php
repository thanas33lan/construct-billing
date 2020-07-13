<?php  
include('includes/MysqliDb.php');
include('includes/General.php');
$general = new General();
$dateSplit = explode(' to ',$_POST['dateFilter']);
$startDate = date("Y-m-d", strtotime($dateSplit[0]));
$endDate = date("Y-m-d", strtotime($dateSplit[1]));
$checkMonthStart = date('m-Y',strtotime($startDate));
$checkMonthEnd = date('m-Y',strtotime($endDate));
// Month wise Profit
$profitQuery = "SELECT `actual_price` AS actual, `net_amount` AS original, `invoice_date` AS invoiceDate FROM stock_details AS ed INNER JOIN product_details AS pd ON ed.product_id=pd.product_id INNER JOIN bill_product_details AS bpd ON pd.product_id=bpd.product_name INNER JOIN bill_details AS bd ON bpd.bill_id=bd.bill_id";
// Total Profit
$totalQuery = "SELECT SUM(`actual_price`) AS actual, SUM(`net_amount`) AS original FROM stock_details AS ed INNER JOIN product_details AS pd ON ed.product_id=pd.product_id INNER JOIN bill_product_details AS bpd ON pd.product_id=bpd.product_name INNER JOIN bill_details AS bd ON bpd.bill_id=bd.bill_id";

if($startDate == $endDate){
	$where = " WHERE invoice_date ='".$startDate."'";
}else if(isset($startDate) && $startDate != "" && isset($endDate) && $endDate != ""){
    $where = " WHERE invoice_date >='".$startDate."' AND invoice_date <= '".$endDate."'";
}else{
    $where = " WHERE invoice_date >='".$startDate."'";
}
$totalQueryExec = $totalQuery.$where;
$query = $profitQuery . $where;
// echo $query;die;
$profitResult = $db->rawQuery($query);
$totalResult = $db->rawQuery($totalQueryExec);
$testArr = array();
$testFinal = array();
foreach($profitResult as $val){
	$testArr[$val["invoiceDate"]]['actual']+=isset($val['actual'])?$val['actual']:0;
	$testArr[$val["invoiceDate"]]['original']+=isset($val['original'])?$val['original']:0;
	$testFinal[$val["invoiceDate"]] = $testArr[$val["invoiceDate"]];
};

?>
<?php if(count($profitResult) > 0){?>
	<script>
		Highcharts.chart('container', {
			credits: {
                enabled: false
            },
            exporting: {
                filename: 'Quality Sample Lot Chart',
                buttons: {
                    contextButton: {
                        menuItems: [
                            'printChart',
                            'separator',
                            'downloadPNG',
                            'downloadJPEG',
                            'downloadPDF',
                            'downloadSVG',
                            'separator',
                            'downloadCSV',
                            'downloadXLS',
                            'viewData'
                        ]
                    }
                }
            },
			title: {
				text: 'Profit report'
			},
			xAxis: {
				categories: [
					<?php foreach ($testFinal as $key=>$rep) {?>
							'<?php echo date('d F, Y',strtotime($key)); ?>',
					<?php } ?>
				],
				crosshair: true,
                scrollbar: {
                    enabled: true
                },
			},
			labels: {
				items: [{
					html: 'Total profit by report',
					style: {
						left: '50px',
						top: '18px',
						color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
					}
				}]
			},
			series: [{
				type: 'column',
				name: 'Taken',
				data: [
					<?php foreach ($testFinal as $key=>$rep) {?>
							<?php echo $rep['actual']; ?>,
					<?php } ?>
				]
			}, {
				type: 'column',
				name: 'Spending',
				data: [
					<?php foreach ($testFinal as $key=>$rep) {?>
							<?php echo $rep['original']; ?>,
					<?php } ?>
				]
			},{
				type: 'column',
				name: 'Profit',
				data: [
					<?php foreach ($testFinal as $key=>$rep) {?>
							<?php echo ($rep['original']-$rep['actual']); ?>,
					<?php } ?>
				]
			},
			{
				type: 'spline',
				name: 'Average',
				data: [(<?php echo ($totalResult[0]['original']-$totalResult[0]['actual']);?>/<?php echo count($testFinal);?>)],
				marker: {
					lineWidth: 2,
					lineColor: Highcharts.getOptions().colors[3],
					fillColor: 'white'
				}
			}, 
			{
				type: 'pie',
				name: 'Total',
				data: [{
					name: 'Taken',
					y: <?php echo $totalResult[0]['actual'];?>,
					color: Highcharts.getOptions().colors[0] // Jane's color
				}, {
					name: 'Spending',
					y: <?php echo $totalResult[0]['original'];?>,
					color: Highcharts.getOptions().colors[1] // John's color
				}, {
					name: 'Profit',
					y: <?php echo ($totalResult[0]['original']-$totalResult[0]['actual']);?>,
					color: Highcharts.getOptions().colors[2] // Joe's color
				}],
				center: [100, 80],
				size: 100,
				showInLegend: false,
				dataLabels: {
					enabled: false
				}
			}]
		});
	</script>
<?php }else{
	echo "<h2 style='text-align:center;'>No data available</h2>";
}?>