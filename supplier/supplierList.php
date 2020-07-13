<?php
$title = "SUPPLIERS";
include('../header.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-gears"></i> Suppliers</h1>
      <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Suppliers</li>
      </ol>
    </section>

     <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="addSupplier.php" class="btn btn-primary pull-right"> <i class="fa fa-plus"></i> Add Suppliers</a>
              <a style="margin-right:5px;" href="javascript:void(0);" class="btn btn-success pull-right" onclick="downloadReport();"> <i class="fa fa-download"></i> Export Excel</a>
            </div>
	    
            <!-- /.box-header -->
            <div class="box-body">
              <table id="SupplierDataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Supplier Name</th>
                  <th>Mobile</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="5" class="dataTables_empty">Loading data from server</td>
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
  <script>
  var oTable = null;
  $(function () {
   
  });
   
  $(document).ready(function() {
	//$.blockUI();
        oTable = $('#SupplierDataTable').dataTable({	
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
                {"sClass":"center","bSortable":false},
            ],
            "aaSorting": [[ 0, "asc" ]],
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "getSupplierDetails.php",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
              $.ajax({
                  "dataType": 'json',
                  "type": "POST",
                  "url": sSource,
                  "data": aoData,
                  "success": fnCallback
              });
            }
        });
       //$.unblockUI();
	} );

  function deleteSupplier(id){
    if(confirm('Are you want to delete this supplier..!')){
      $.blockUI();
      $.post("delete.php", {supplierId:id },
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

  function downloadReport(){
    $.blockUI();
    $.post("generateReportExcel.php", { },
     function(data){
       $.unblockUI();
      if(data === "" || data === null || data === undefined){
	      alert('Unable to generate excel..');
      }else{
	      location.href = '../temporary/'+data;
      }
     });
  }
  
</script>
 <?php
 include('../footer.php');
 ?>
