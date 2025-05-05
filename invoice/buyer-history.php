<?php
$title = "Customer History";
include('../header.php');
$cQuery = "SELECT * FROM client_details where client_status='active'";
$cResult = $db->rawQuery($cQuery);
?>
<link href="../assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1><i class="fa fa-gears"></i> Customer History</h1>
		<ol class="breadcrumb">
			<li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Customer History</li>
		</ol>
	</section>

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header with-border">
						<a style="margin-right:5px;display:none;" id="export-buyer" href="javascript:void(0);" class="btn btn-success pull-right" onclick="downloadBuyer();"> <i class="fa fa-download"></i> Export Buyer Details</a>
					</div>

					<!-- /.box-header -->
					<div class="box-body">
						<table class="table" cellpadding="1" cellspacing="3" style="margin-left:1%;margin-top:20px;width:100%;margin-bottom: 0px;">
							<tr>
								<td><b>Customer Name&nbsp;</b></td>
								<td>
									<select class="form-control" name="customerName" id="customerName" title="Choose Customer">
										<option value="">All Clients</option>
										<?php
										foreach ($cResult as $name) {
										?>
											<option value="<?php echo $name['client_name']; ?>"><?php echo $name['client_name']; ?></option>
										<?php
										}
										?>
									</select>
								</td>
								<td><b>Invoice Date&nbsp;:</b></td>
								<td>
									<input type="text" id="invoiceDate" name="invoiceDate" class="form-control" placeholder="Select Invoice Date" readonly style="width:220px;background:#fff;" />
								</td>
								<td>&nbsp;<input type="button" onclick="getCustomerHistory();" value="Search" class="btn btn-default btn-sm">
									&nbsp;<a href="" class="btn btn-danger btn-sm"><span>Reset</span></a>
								</td>
							</tr>

						</table><br />
						<div class="widget">
							<div class="widget-content">
								<div class="bs-example bs-example-tabs">
									<ul id="myTab" class="nav nav-tabs" style="display:none;">
										<li class="active purchaseHistory"><a href="#purchaseHistory" data-toggle="tab">Purchase History</a></li>
										<li class="paymentHistory"><a href="#paymentHistory" data-toggle="tab">Payment History</a></li>
									</ul>
									<div id="invoiceData">

									</div>
								</div>
							</div>
						</div>

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
		$("#customerName").select2({
			placeholder: "Enter customer name",
			width: '250px',
			allowClear: true,
			maximumSelectionLength: 2
		});
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

	function getCustomerHistory() {
		var customerId = $("#customerName").val();
		var invoiceDate = $("#invoiceDate").val();
		if (customerId != '' || invoiceDate != '') {
			// $.blockUI();
			$.post("getCustomerHistory.php", {
					customerId: customerId,
					invoiceDate: invoiceDate
				},
				function(data) {
					$("#myTab").show();
					$("#invoiceData").html(data);
					$('#export-buyer').show();
					$('.purchaseHistory').addClass('active');
					$('.paymentHistory').removeClass('active');
					$.unblockUI();
				});
		} else {
			getCustomerHistory
			alert("Please choose customer name and invoice date")
		}
	}

	function generatePDF() {
		if ($("#customerName").val() != '' || $("#invoiceDate").val() != '') {
			$.blockUI();
			window.open("/invoice/generateVoucherPdf.php?customerId=" + $("#customerName").val() + "&invoiceDate=" + $("#invoiceDate").val() + "", '_blank');
			$.unblockUI();
			/* $.post("generateVoucherPdf.php", {
				customerId: customerId,
				invoiceDate: invoiceDate
			},
			function(data) {
				if (data == "" || data == null || data == undefined) {
					$.unblockUI();
					alert('Unable to generate download');
				} else {
					window.open('../uploads/' + data, '_blank');
				}
			}); */
		} else {
			alert("Please choose customer name and invoice date")
		}
	}

	function downloadBuyer() {
		$.blockUI();
		$.post("generateBuyerListExcel.php", {},
			function(data) {
				$.unblockUI();
				if (data === "" || data === null || data === undefined) {
					alert('Unable to generate list..');
				} else {
					location.href = '../temporary/' + data;
				}
			});
	}
</script>
<?php
include('../footer.php');
?>