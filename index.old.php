<?php
include('header.php');
if (!isset($_SESSION['userId']) && $_SESSION['userId'] != "") {
	header("location:login.php");
}
if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] != 'merlin'){
	header("invoice/create-invoice.php");
}
$pQuery = "SELECT 'actual_price','net_amount','sold_qty' FROM product_details AS pd LEFT JOIN stock_details AS sd ON pd.product_id=sd.product_id LEFT JOIN bill_product_details AS bpd ON pd.product_name=bpd.product_name GROUP BY bpd.product_name";
$cResult = $db->rawQuery($pQuery);
// Count Customers
$cusQuery = "SELECT COUNT('client_id') AS customers FROM client_details";
$cusResult = $db->rawQuery($cusQuery);
// Count Products
$productQuery = "SELECT COUNT('product_id') AS products FROM product_details";
$productResult = $db->rawQuery($productQuery);

// Total Sale
$saleQuery = "SELECT SUM(`total_amount`) AS saleamount FROM bill_details";
$saleResult = $db->rawQuery($saleQuery);

// Total Paid
$paidQuery = "SELECT SUM(`paid_amount`) AS paidamount FROM paid_details";
$paidResult = $db->rawQuery($paidQuery);

// Total Investment
$investmentQuery = "SELECT SUM(`purchase_amount`) AS investamount FROM purchase_details";
$investResult = $db->rawQuery($investmentQuery);

// Total Suppliers
$supplierQuery = "SELECT COUNT(`supplier_id`) AS suppliers FROM supplier_details";
$supplierResult = $db->rawQuery($supplierQuery);

// Total Expenses
$expensQuery = "SELECT SUM(`price`) AS expenses FROM expense_details";
$expensResult = $db->rawQuery($expensQuery);

// Total Profit
$profitQuery = "SELECT SUM(`actual_price`) AS actual, SUM(`net_amount`) AS original FROM stock_details AS ed INNER JOIN product_details AS pd ON ed.product_id=pd.product_id INNER JOIN bill_product_details AS bpd ON pd.product_name=bpd.product_name";
$profitResult = $db->rawQuery($profitQuery);

$profit = $profitResult[0]['original'] - $profitResult[0]['actual'];
?>
<link href="/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><i class="fa fa-dashboard"></i> Dashbord</h1>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-red"><i class="fa fa-fw fa-life-saver"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Total Investment</span>
						<span class="info-box-number"><small><i class="fa fa-inr"></i></small> <?php echo number_format($investResult[0]['investamount'], 2); ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-green"><i class="fa fa-fw fa-cart-arrow-down"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Total Sales</span>
						<span class="info-box-number"><small><i class="fa fa-inr"></i></small> <?php echo number_format($saleResult[0]['saleamount'], 2); ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-4 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-green"><i class="fa fa-fw fa-line-chart"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Total Income</span>
						<span class="info-box-number"><small><i class="fa fa-inr"></i></small> <?php echo number_format($paidResult[0]['paidamount'], 2); ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-fw fa-users"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Total Customers</span>
						<span class="info-box-number"><?php echo $cusResult[0]['customers']; ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-fw fa-cubes"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Total Products</span>
						<span class="info-box-number"><?php echo $productResult[0]['products']; ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-fw fa-rocket"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Total Suppliers</span>
						<span class="info-box-number"><?php echo $supplierResult[0]['suppliers']; ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-3 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-red"><i class="fa fa-fw fa-sort-amount-asc"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Total Expenses</span>
						<span class="info-box-number"><small><i class="fa fa-inr"></i></small> <?php echo number_format($expensResult[0]['expenses'], 2); ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-green"><i class="fa fa-fw fa-bar-chart"></i></span>

					<div class="info-box-content">
						<span class="info-box-text">Total Profit</span>
						<span class="info-box-number"><small><i class="fa fa-inr"></i></small> <?php echo number_format($profit, 2); ?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-green"><i class="fa fa-fw fa-money"></i></span>

					<div class="info-box-content" style="display: inline-flex;">
						<div class="" style=" margin-left: -92px; ">
							<span class="info-box-text">Spending Money</span>
							<span class="info-box-number"><small><i class="fa fa-inr"></i></small> <?php echo number_format($profitResult[0]['actual'], 2); ?></span>
						</div>
						<div class="" style=" margin-left: 20px; ">
							<span class="info-box-text">Taken Money</span>
							<span class="info-box-number"><small><i class="fa fa-inr"></i></small> <?php echo number_format($profitResult[0]['original'], 2); ?></span>
						</div>
					</div>
					<!-- /.info-box-content -->
				</div>
			</div>
		</div>
		<!-- /.row -->
		<div class="row">
			<div class="box" style="margin-left:10px;display: -webkit-inline-box;">
				<h3 class="box-title">Use the datepicker to filter : </h3>
				<input type="text" class="form-control" id="filterDate" name="filterDate" class="form-control" placeholder="Select filter date" readonly style="width:220px;background:#fff;margin: 15px;" />
				<button class="btn btn-primary btn-sm" value="submit" onclick="checkFilter();" style="margin: 15px;">Filter</button>
			</div>
			<div class="col-xs-12">
				<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->
</div>
<script type="text/javascript" src="/assets/plugins/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/assets/highcharts/highcharts.js"></script>
<script src="/assets/highcharts/series-label.js"></script>
<script src="/assets/highcharts/exporting.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
        $('#filterDate').daterangepicker({
            format: 'DD-MMM-YYYY',
	          separator: ' to ',
            startDate: moment().subtract('days', 29),
            endDate: moment(),
            maxDate: moment(),
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                'Last 7 Days': [moment().subtract('days', 6), moment()],
                'Last 30 Days': [moment().subtract('days', 29), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            }
        },
        function(start, end) {
            startDate = start.format('YYYY-MM-DD');
            endDate = end.format('YYYY-MM-DD');
      });
      $('#filterDate').val('');
	} );
	function checkFilter(){
		$.post("profit-chart.php", {dateFilter: $('#filterDate').val() },
		 function(data){
			$("#container").html(data);
		 });
	}
</script>
<?php
include('footer.php');
?>