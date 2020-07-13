<?php
$title = "INVOICE LIST";
include('../header.php');
?>
<link href="../assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
<style>
	.inline{
		display: inline-flex;
	}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><i class="fa fa-gears"></i> Invoice</h1>
		<ol class="breadcrumb">
			<li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Invoice</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header with-border">
						<a href="create-invoice.php" class="btn btn-primary pull-right"> <i class="fa fa-plus"></i> Add Invoice</a>
						<a style="margin-right:5px;" href="javascript:void(0);" class="btn btn-success pull-right" onclick="downloadInvoice();"> <i class="fa fa-download"></i> Export Excel</a>
					</div>

					<!-- /.box-header -->
					<div class="box-body">
						<table class="table" cellpadding="1" cellspacing="3" style="margin-left:1%;margin-top:20px;width:50%;margin-bottom: 0px;">
							<tr>
								<td><b>Invoice Date&nbsp;:</b></td>
								<td>
									<input type="text" id="invoiceDate" name="invoiceDate" class="form-control" placeholder="Select Invoice Date" readonly style="width:220px;background:#fff;" />
								</td>
								<td>&nbsp;<input type="button" onclick="searchData();" value="Search" class="btn btn-default btn-sm">
									&nbsp;<button class="btn btn-danger btn-sm" onclick="reset();"><span>Reset</span></button>
								</td>
							</tr>

						</table>
						<table id="ClientDataTable" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Invoice Number</th>
									<th>Invoice Date</th>
									<th>Invoice Due Date</th>
									<th>Client Name</th>
									<th>Billing Address</th>
									<th>Shipping Address</th>
									<th>Total</th>
									<th>Added On</th>
									<th style="width:13%">Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="6" class="dataTables_empty">Loading data from server</td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<script type="text/javascript" src="../assets/plugins/daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="../assets/plugins/daterangepicker/daterangepicker.js"></script>
<script>
	var oTable = null;
	$(document).ready(function() {

		<?php
		if (isset($_SESSION['billId']) && $_SESSION['billId'] != '') {
		?>
			window.open("/invoice/generatePdf.php?id=<?php echo base64_encode($_SESSION['billId']); ?>", '_blank');
		<?php
			unset($_SESSION['billId']);
		}
		?>
		$.blockUI();
		oTable = $('#ClientDataTable').dataTable({
			"oLanguage": {
				"sLengthMenu": "_MENU_ records per page"
			},
			"bJQueryUI": false,
			"bAutoWidth": false,
			"bInfo": true,
			"bScrollCollapse": true,
			"bStateSave": true,
			"bRetrieve": true,
			"aoColumns": [{
					"sClass": "center"
				},
				{
					"sClass": "center"
				},
				{
					"sClass": "center"
				},
				{
					"sClass": "center"
				},
				{
					"sClass": "center"
				},
				{
					"sClass": "center"
				},
				{
					"sClass": "center"
				},
				{
					"sClass": "center"
				},
				{
					"sClass": "center inline",
					"bSortable": false
				},
			],
			"aaSorting": [
				[0, "DESC"]
			],
			"bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "getInvoiceDetails.php",
			"fnServerData": function(sSource, aoData, fnCallback) {
				aoData.push({
					"name": "invoiceDate",
					"value": $("#invoiceDate").val()
				});
				$.ajax({
					"dataType": 'json',
					"type": "POST",
					"url": sSource,
					"data": aoData,
					"success": fnCallback
				});
			}
		});
		$.unblockUI();

		$('#invoiceDate').daterangepicker({
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
		$('#invoiceDate').val('');
	});

	function searchData() {
		oTable.fnDraw();
	}

	function downloadInvoice() {
		$.blockUI();
		$.post("generateInviceListExcel.php", {},
			function(data) {
				$.unblockUI();
				if (data === "" || data === null || data === undefined) {
					alert('Unable to generate excel..');
				} else {
					location.href = '../temporary/' + data;
				}
			});
	}

	function deleteInvoice(id) {
		if (confirm('Are you want to delete this invoice..!')) {
			$.blockUI();
			$.post("delete.php", {
				billId: id
			},
			function(data) {
				$.unblockUI();
				if (data === "" || data === null || data === undefined) {
					alert('Unable to delete..');
				} else {
					oTable.fnDraw();
				}
			});
		}
	}
</script>
<?php
include('../footer.php');
?>