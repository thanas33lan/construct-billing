<?php
$title = "STOCK DETAILS";
include('../header.php');
if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'tile-admin' || $_SESSION['userLoginId'] == 'admin'){
  $editAction = '{"sClass":"center","bSortable":false},{"sClass":"center"},{"sClass":"center"},{"sClass":"center"},{"sClass":"center"},{"sClass":"center"},{"sClass":"center"},{"sClass":"center","bSortable":false}';
}else{
  $editAction = '{"sClass":"center"},{"sClass":"center"},{"sClass":"center"},{"sClass":"center"},{"sClass":"center"},{"sClass":"center","bSortable":false}';
}
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-gears"></i> Stock</h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Stock</li>
      </ol>
    </section>

     <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <!-- <a href="addProduct.php" class="btn btn-primary pull-right"> <i class="fa fa-plus"></i> Manage Stock</a> -->
              <a style="margin-right:5px;" href="javascript:void(0);" class="btn btn-success pull-right" onclick="downloadStock();"> <i class="fa fa-download"></i> Export Excel</a>
            </div>
            <div class="row">
            
              <div class="col-md-12">
              <h4>&nbsp;<i class="fa fa-filter"></i>&nbsp;Use Filters</h4>
                <div class="col-md-4">
                  <b>Product Name</b> <input  type="text" id="prdName" name="prdName" class="form-control" placeholder="Product Name" title="Filter By Product Name" onkeyup="getFilterData()"/> 
                </div>
                <div class="col-md-2">
                  <b>Actual Quantity</b> <input type="text" name="actualQty" id="actualQty" class="form-control" placeholder="Actual Qty" title="Filter By Actual Qty" onkeyup="getFilterData()"/> 
                </div>
                <div class="col-md-2">
                  <b>Minimum Quantity</b> <input type="text" name="minimumQty" id="minimumQty" class="form-control" Placeholder="Minimum Qty" title="Filter By Minimum Qty" onkeyup="getFilterData()"/> 
                </div>
                <div class="col-md-2">
                <b>Stock Status </b>
                <select name="status" id="status" class="form-control " title="Choose status" onchange="getFilterData();">
                  <option value="">-- Select --</option>
                  <option value="active">Active</option>
                  <option value="inactive">inactive</option>
                </select>
                </div>
              </div>
            </div>

            <hr/>
            <!-- /.box-header -->
            <div class="box-body">
            
              <table id="stockDataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <?php if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'tile-admin' || $_SESSION['userLoginId'] == 'admin'){?>
                    <th></th>
                  <?php }?>
                  <th>Product Name</th>
                  <th>Actual Quantity</th>
                  <th>Minimum Quantity</th>
                  <th>Quantity Saled</th>
                  <th>Quantity Remaining</th>
                  <?php if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'tile-admin' || $_SESSION['userLoginId'] == 'admin'){?>
                    <th>Actual Price</th>
                  <?php }?>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                    <?php if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'tile-admin' || $_SESSION['userLoginId'] == 'admin'){?>
                      <th>Actual Price</th>
                      <td colspan="6" class="dataTables_empty">Loading data from server</td>
                      <?php }else{?>
                        <td colspan="8" class="dataTables_empty">Loading data from server</td>
                      <?php }?>
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
	$.blockUI();
        oTable = $('#stockDataTable').dataTable({	
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            },
            "bJQueryUI": false,
            "bAutoWidth": false,
            "bInfo": true,
            "bScrollCollapse": true,
            "bStateSave" : true,
            "bRetrieve": true,                        
            "aoColumns": [ <?php echo $editAction;?>],
            "aaSorting": [[ 1, "asc" ]],
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "getStockDetails.php",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
              aoData.push({"name": "prdName", "value": $("#prdName").val()});
              aoData.push({"name": "actualQty", "value": $("#actualQty").val()});
              aoData.push({"name": "minimumQty", "value": $("#minimumQty").val()});
              aoData.push({"name": "status", "value": $("#status").val()});
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
	} );

  function getFilterData()
  {
    oTable.fnDraw();
  }
  
  function deleteStock(id){
    if(confirm('Are you want to delete this stock..!')){
      $.blockUI();
      $.post("delete.php", {stockId:id },
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

  function downloadStock(){
    $.blockUI();
    $.post("generateStockExcel.php", { },
     function(data){
       $.unblockUI();
      if(data === "" || data === null || data === undefined){
	      alert('Unable to generate excel..');
      }else{
	      location.href = '../temporary/'+data;
      }
     });
  }

  function hideDetails(prdId,key){
      hideRow = key.parentNode.parentNode.rowIndex+1;
      document.getElementById("stockDataTable").deleteRow(hideRow);
      key.setAttribute("onclick", "showDetails('"+prdId+"',this)");
      $(key).find('i').removeClass('fa fa-minus');
      $(key).find('i').addClass('fa fa-plus');
  }

  function showDetails(prdId,key)
  {
      newRow = key.parentNode.parentNode.rowIndex+1;
      len = document.getElementById("stockDataTable").rows[0].cells.length;
      var x=document.getElementById("stockDataTable").insertRow(newRow);
      cellId = "cell"+newRow;
      x.innerHTML="<td></td><td colspan='8' id='"+cellId+"' style='background-color:#f1dcf7;'></td>";
      key.setAttribute("onclick", "hideDetails('"+prdId+"',this)");
        //AJAX SECTION
        $.post("compareProducts.php", {prdId: prdId},
        function(data) {
          document.getElementById(cellId).innerHTML=data;
          $(key).find('i').removeClass('fa fa-plus');
          $(key).find('i').addClass('fa fa-minus');
          $.unblockUI();
        });
  }
</script>
 <?php
 include('../footer.php');
 ?>
