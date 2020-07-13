<?php
$title = "PRODUCTS";
include('../header.php');
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-gears"></i> Products</h1>
      <ol class="breadcrumb">
        <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Products</li>
      </ol>
    </section>

     <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="addProduct.php" class="btn btn-primary pull-right"> <i class="fa fa-plus"></i> Add Products</a>
              <a style="margin-right:5px;" href="javascript:void(0);" class="btn btn-success pull-right" onclick="downloadProduct();"> <i class="fa fa-download"></i> Export Excel</a>
            </div>
            <div class="row">
            
            <div class="col-md-12">
              <h4>&nbsp;<i class="fa fa-filter"></i>&nbsp;Use Filters</h4>
                <div class="col-md-4">
                  <b>Product Name</b> <input  type="text" id="prdName" name="prdName" class="form-control" placeholder="Product Name" title="Filter By Product Name" onkeyup="getFilterData()" onkeydown="getFilterData();"/> 
                </div>
                <div class="col-md-2">
                  <b>HSN</b> <input type="text" name="hsnCode" id="hsnCode" class="form-control" placeholder="HSN" title="Filter By HSN" onkeyup="getFilterData()" onkeydown="getFilterData();"/> 
                </div>
                <div class="col-md-2">
                  <b>Tax</b> <input type="text" name="tax" id="tax" class="form-control" Placeholder="Tax" title="Filter By Tax" onkeyup="getFilterData()" onkeydown="getFilterData();"/> 
                </div>
                <div class="col-md-2">
                <b> Stock </b><input type="text" name="minimumStock" id="minimumStock" class="form-control" Placeholder="Stock" title="Filter By stock" onkeyup="getFilterData()" onkeydown="getFilterData();"/> 
                </div>
                <div class="col-md-2">
                <b>Status </b>
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
            
              <table id="productDataTable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Product Name</th>
                  <th>HSN</th>
                  <!-- <th>Qty</th> -->
                  <!-- <th>Minimum Qty</th> -->
                  <th>Product Price</th>
                  <th>Tax <i class="fa fa-percent"></i></th>
                  <th>Last Paid</th>
                  <th>Action</th>
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
  <script>
  var oTable = null;
  $(function () {
   
  });
   
  $(document).ready(function() {
	$.blockUI();
        oTable = $('#productDataTable').dataTable({	
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
                {"sClass":"center","bSortable":false},
            ],
            "aaSorting": [[ 0, "desc" ]],
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "getProductDetails.php",
            "fnServerData": function ( sSource, aoData, fnCallback ) {
              aoData.push({"name": "prdName", "value": $("#prdName").val()});
              aoData.push({"name": "hsnCode", "value": $("#hsnCode").val()});
              aoData.push({"name": "tax", "value": $("#tax").val()});
              aoData.push({"name": "minimumStock", "value": $("#minimumStock").val()});
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
  function showDetails(prdId,key)
  {
      newRow = key.parentNode.parentNode.rowIndex+1;
      len = document.getElementById("productDataTable").rows[0].cells.length;
      var x=document.getElementById("productDataTable").insertRow(newRow);
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
  function hideDetails(prdId,key){
      hideRow = key.parentNode.parentNode.rowIndex+1;
      document.getElementById("productDataTable").deleteRow(hideRow);
      key.setAttribute("onclick", "showDetails('"+prdId+"',this)");
      $(key).find('i').removeClass('fa fa-minus');
      $(key).find('i').addClass('fa fa-plus');
  }
  function downloadProduct(){
    $.blockUI();
    $.post("generateProductExcel.php", { },
     function(data){
       $.unblockUI();
      if(data === "" || data === null || data === undefined){
	      alert('Unable to generate excel..');
      }else{
	      location.href = '../temporary/'+data;
      }
     });
  }
  function deleteProduct(id){
    if(confirm('Are you want to delete this product..!')){
      $.blockUI();
      $.post("delete.php", {productId:id },
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
