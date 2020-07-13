<?php
$title = "EXPENSES";
include('../header.php');
//get all user details
$aQuery="SELECT * FROM user_details where user_status='active' AND login_id != 'merlin'";
$userResult = $db->rawQuery($aQuery);
//get all suppliers details
$sQuery="SELECT * FROM supplier_details where supplier_status='active'";
$supplierResult = $db->rawQuery($sQuery);

$expenseQuery="SELECT * from expense_details";
$expenseInfo=$db->query($expenseQuery);
?>
<link href="../assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-gears"></i> Expense</h1>
      <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Expense</li>
      </ol>
    </section>

     <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="addExpense.php" class="btn btn-primary pull-right"> <i class="fa fa-plus"></i> Add Expense</a>
              <a style="margin-right:5px;" href="javascript:void(0);" class="btn btn-success pull-right" onclick="downloadExpense();"> <i class="fa fa-download"></i> Export Excel</a>
            </div>
            <div class="row">
            
              <div class="col-md-12">
              <h4>&nbsp;<i class="fa fa-filter"></i>&nbsp;Use Filters</h4>
                <div class="col-md-4">
                  <b>Date</b>
                  <input type="text" class="form-control" id="expenseDate" name="expenseDate" placeholder="Expense Date" title="Please choose date" readonly/>
                </div>
                <div class="col-md-2">
                  <b>Purchased By</b>
                  <select class="form-control" id="purchasedBy" name="purchasedBy" title="Please enter purchased by">
                      <option value="">-- Select --</option>
                      <?php foreach($userResult as $user) {?>
                        <option value="<?php echo $user['user_id'];?>"><?php echo $user['user_name'];?></option>
                      <?php } ?>
                  </select>
                </div>
                <div class="col-md-2">
                  <b>Purchased From</b>
                  <select class="form-control" id="purchasedFrom" name="purchasedFrom" title="Please enter purchased from">
                        <option value="">-- Select --</option>
                        <?php foreach($supplierResult as $supplier) {?>
                            <option value="<?php echo $supplier['supplier_id'];?>"><?php echo $supplier['supplier_name'];?></option>
                        <?php } ?>
                  </select>
                </div>
                
                <div class="col-md-2">
                  <b>Payment Status </b>
                  <select class="form-control" id="paymentStatus" name="paymentStatus" title="Please enter the payment status">
                        <option value="">--select--</option>
                        <option value="paid-by-cash">Paid By Cash</option>
                        <option value="online">Online</option>
                        <option value="cheque">Cheque</option>
                        <option value="pending">Pending</option>
                        <option value="emi">EMI</option>
                  </select>
                </div>
                <div class="col-md-2">
                  <a href="javascript:void(0);" onclick="getFilterData();" class="btn btn-primary btn-sm" style="margin-top:19px;">Filter</a>
                  <a href="javascript:void(0);" onclick="setClearData();" class="btn btn-danger btn-sm" style="margin-top:19px;">Clear</a>
                </div>
              </div>
            </div>

            <hr/>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="expenseDataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Expense Date</th>
                  <th>Particulars</th>
                  <th>Purchased From</th>
                  <th>Purchased By</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Amount</th>
                  <th>Payment Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="9" class="dataTables_empty">Loading data from server</td>
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
    $('#expenseDate').daterangepicker({
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
    $('#expenseDate').val('');
        oTable = $('#expenseDataTable').dataTable({	
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "bJQueryUI": false,
            "bAutoWidth": false,
            "bInfo": true,
            "bScrollCollapse": true,
            "bStateSave" : true,
            "bRetrieve": true,                        
            "aoColumns": [
                {"sClass":"center"},
                {"sClass":"center"},
                {"sClass":"center"},
                {"sClass":"center"},
                {"sClass":"center"},
                {"sClass":"center"},
                {"sClass":"center"},
                {"sClass":"center"},
                {"sClass":"center","bSortable":false},
            ],
            "aaSorting": [[ 0, "asc" ]],
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "getExpenseDetails.php",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
              aoData.push({"name": "purchasedFrom", "value": $("#purchasedFrom").val()});
              aoData.push({"name": "purchasedBy", "value": $("#purchasedBy").val()});
              aoData.push({"name": "expenseDate", "value": $("#expenseDate").val()});
              aoData.push({"name": "paymentStatus", "value": $("#paymentStatus").val()});
              $.ajax({
                  "dataType": 'json',
                  "type": "POST",
                  "url": sSource,
                  "data": aoData,
                  "success": fnCallback
              });
            }
        });
	} );

  function setClearData()
  {
    $("#purchasedFrom").val('');
    $("#purchasedBy").val('');
    $("#expenseDate").val('');
    $("#paymentStatus").val('');
    oTable.fnDraw();
  }

  function getFilterData()
  {
    oTable.fnDraw();
  }

  function downloadExpense(){
    $.blockUI();
    $.post("generateExpenseListExcel.php", { },
     function(data){
       $.unblockUI();
      if(data === "" || data === null || data === undefined){
	      alert('Unable to generate excel..');
      }else{
	      location.href = '../temporary/'+data;
      }
     });
  }
  function deleteExpense(id){
    if(confirm('Are you want to delete this expense..!')){
      $.blockUI();
      $.post("delete.php", {expenseId:id },
      function(data){
        $.unblockUI();
        if(data === "" || data === null || data === undefined){
          alert('Unable to delete..');
        }else{
          oTable.fnDraw();
        }
      });
    }
  }
  
</script>
 <?php
 include('../footer.php');
 ?>
