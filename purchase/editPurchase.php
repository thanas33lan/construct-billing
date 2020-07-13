<?php
ob_start();
include('../header.php');

//get All product details
$pQuery="SELECT * FROM product_details where product_status='active'";
$pResult = $db->rawQuery($pQuery);

//get all agent details
$aQuery="SELECT * FROM supplier_details where supplier_status='active'";
$aResult = $db->rawQuery($aQuery);

$productList = '';
foreach($pResult as $prd){
    $productList .= '<option value="'.$prd['product_id'].'" data-hsn="'.$prd['hsn_code'].'" data-price="'.$prd['product_price'].'" data-gst="'.$prd['product_tax'].'" data-qty="'.$prd['qty_available'].'" data-mini-qty="'.$prd['minimum_qty'].'">'.$prd['product_name'].'</option>';
}

$suplierDetail = '';
foreach($aResult as $supplier)
{
    $suplierDetail .= '<option value="'.$supplier['supplier_id'].'">'.ucwords($supplier['supplier_name']).'</option>';
}

//get purchase detaiils
$puQuery="SELECT * FROM purchase_details where purchase_id=".base64_decode($_GET['id']);
$puResult = $db->rawQuery($puQuery);

$prdQuery="SELECT * FROM purchase_product_details where purchase_id=".base64_decode($_GET['id']);
$prdResult = $db->rawQuery($prdQuery);


?>
<style>
#agentName1 option{
display:none;
}
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1> Edit Purchase</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li class="active">Edit Purchase</li>
          </ol>
     </section>

     <!-- Main content -->
     <section class="content">
          <!-- SELECT2 EXAMPLE -->
          <div class="box box-default">
               <div class="box-header with-border">
                    <div class="pull-right" style="font-size:15px;"><span class="mandatory">*</span> indicates required field &nbsp;</div>
               </div>
               <!-- /.box-header -->
               <div class="box-body">
                    <!-- form start -->
                    <form class="form-horizontal" method='post'  name='editPurchase' id='editPurchase' autocomplete="off" action="editPurchaseHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="supplierName" class="col-lg-4 control-label">Supplier Name <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <select class="form-control isRequired" id="supplierName" name="supplierName" >
                                                  <option value="">-- Select --</option>
                                                  <?php
                                                  foreach($aResult as $supplier)
                                                  {
                                                      ?>
                                                    <option value="<?php echo $supplier['supplier_id'];?>" <?php echo ($supplier['supplier_id']==$puResult[0]['supplier_id'])?"selected='selected'":''; ?>><?php echo ucwords($supplier['supplier_name']);?></option>
                                                    <?php
                                                  }
                                                  ?>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="purchaseDate" class="col-lg-4 control-label">Purchase Date <span class="mandatory">*</span> </label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="purchaseDate" name="purchaseDate" placeholder="Purchase Date" title="Please choose date" value="<?php echo date('d-M-Y',strtotime($puResult[0]['purchase_on']));?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="purchaseNo" class="col-lg-4 control-label">Purchase Number <span class="mandatory">*</span> </label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="purchaseNo" name="purchaseNo" placeholder="Purchase Number" title="Please enter purchase number" value="<?php echo $puResult[0]['purchase_no'];?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              
                              
		                <!-- </div> -->
                         </div>
                         <div class="box-header">
                        <h3 class="box-title ">Product Details</h3>
                    </div>
		    <!-- <div class="box-body"> -->
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Product Name </th>
                                    <th>Qty </th>
                                    <th>INR <i class="fa fa-inr"></i> </th>
                                    <th>Line Total </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            
                            <tbody id="productsTable">
                            <?php
                            $total = 0;
                            if(count($prdResult)>0){
                            foreach($prdResult as $prd)
                            {
                                $total += $prd['purchase_line_total'];
                                ?>
<tr>
                                    <td>
                                        <select name="prdName[]" id="prdName1" class="form-control prdName isRequired" title="Enter Product" onchange="checkExistProduct(1)">
                                            <option value="">-- Select --</option>
                                            <?php foreach($pResult as $product){ ?>
                                                <option value="<?php echo $product['product_id'];?>" <?php echo ($prd['product_id']==$product['product_id'])?"selected='selected'":''; ?> data-price="<?php echo $product['product_price'];?>"><?php echo $product['product_name'];?></option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control isRequired" name="prdQty[]" id="prdQty1" title="Enter Quantity" value="<?php echo $prd['purchase_qty'];?>" onchange="updateTotalPrice(1);" onkeyup="updateTotalPrice(1)"  onblur="updateTotalPrice(1);"/></td>
                                    <td><input type="text" class="form-control isRequired" name="prdPrice[]" id="prdPrice1" value="<?php echo $prd['purchase_prd_amount'];?>" onchange="updateTotalPrice(1);" onkeyup="updateTotalPrice(1)"  onblur="updateTotalPrice(1);" /></td>
                                    <td><input type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal1" title="Enter Total" readonly  value="<?php echo $prd['purchase_line_total'];?>"/></td>
                                    <td align="center" style="vertical-align:middle;">
                                        <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            }else{
                            ?>
                                <tr>
                                    <td>
                                        <select name="prdName[]" id="prdName1" class="form-control prdName isRequired" title="Enter Product" onchange="checkExistProduct(1)">
                                            <option value="">-- Select --</option>
                                            <?php echo $productList;?>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control isRequired" name="prdQty[]" id="prdQty1" title="Enter Quantity" onchange="updateTotalPrice(1);" onkeyup="updateTotalPrice(1)"  onblur="updateTotalPrice(1);"/></td>
                                    <td><input type="text" class="form-control isRequired" name="prdPrice[]" id="prdPrice1" onchange="updateTotalPrice(1);" onkeyup="updateTotalPrice(1)"  onblur="updateTotalPrice(1);" /></td>
                                    <td><input type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal1" title="Enter Total" readonly/></td>
                                    <td align="center" style="vertical-align:middle;">
                                        <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" ><b class="pull-right">Grand Total</b></td>                                    
                                    <td ><input type="text" id="grandTotal" name="grandTotal" class="form-control isRequired checkNum" placeholder="Grand Total" title="Grand Total for this order" readonly value="<?php echo $total;?>"/></td>
                                </tr>
                            </tfoot>
                        </table>


                         <!-- /.box-body -->
                         <div class="box-footer">
                         <input type="hidden" name="purchaseId" id="purchaseId" value="<?php echo $_GET['id'];?>"/>
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                              <a href="purchaseList.php" class="btn btn-default"> Cancel</a>
                         </div>
                         <!-- /.box-footer -->
                    </form>
                    <!-- /.row -->
               </div>
          </div>
          <!-- /.box -->

     </section>
     <!-- /.content -->
</div>
<script type="text/javascript">
var tableRowId = 2;
var payTableRowId = 2;
$(document).ready(function() {
    $("#prdName1").select2({
        placeholder: "Enter product name",
        width:'250px',
        allowClear:true,
        maximumSelectionLength: 2
        });
    
        $('#purchaseDate').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            maxDate: "Today",
            yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
        }).click(function(){
            $('.ui-datepicker-calendar').show();
        });
});

function validateNow(){
     flag = deforayValidator.init({
          formId: 'editPurchase'
     });
     if(flag){
        $.blockUI();
        document.getElementById('editPurchase').submit();
    }
}

function insRow() {
        rl = document.getElementById("productsTable").rows.length;
        var a = document.getElementById("productsTable").insertRow(rl);
        a.setAttribute("style", "display:none");
        var b = a.insertCell(0);
        var c = a.insertCell(1);
        var d = a.insertCell(2);
        var e = a.insertCell(3);
        var f = a.insertCell(4);
        
        f.setAttribute("align", "center");
        f.setAttribute("style","vertical-align:middle");
        
        b.innerHTML = '<select name="prdName[]" id="prdName' + tableRowId + '" class="form-control prdName isRequired" title="Enter Product" onchange="checkExistProduct('+tableRowId+')"><option value="">-- Select --</option><?php echo $productList;?></select>';
        c.innerHTML = '<input type="text" class="form-control isRequired" name="prdQty[]" id="prdQty' + tableRowId + '" title="Enter Quantity" onchange="updateTotalPrice('+tableRowId+');" onkeyup="updateTotalPrice('+tableRowId+')" onblur="updateTotalPrice('+tableRowId+');"/>';
        d.innerHTML = '<input type="text" class="form-control isRequired" name="prdPrice[]" id="prdPrice' + tableRowId + '" onchange="updateTotalPrice('+tableRowId+');" onkeyup="updateTotalPrice('+tableRowId+')" onblur="updateTotalPrice('+tableRowId+');" />';
        e.innerHTML = '<input type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal' + tableRowId + '" title="Enter Total" readonly/>';
        f.innerHTML = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>';
        $(a).fadeIn(800);
        $("#prdName"+tableRowId).select2({
        placeholder: "Enter product name",
        width:'250px',
        allowClear:true,
        });
        tableRowId++;
    }

    function removeAttributeRow(el) {
        $(el).fadeOut("slow", function() {
            el.parentNode.removeChild(el);
            rl = document.getElementById("productsTable").rows.length;
            if (rl == 0) {
                insRow();
            }
        });
    }
    

    function updatePrice(rowId){
        var prdSelection = $("#prdName"+rowId).val();
        if(prdSelection!=''){
            var price = $("#prdName"+rowId).find(':selected').attr('data-price');
            $("#taxablePrice"+rowId).val($("#prdName"+rowId).find(':selected').attr('data-price'));

            $("#prdQty"+rowId).val(1);
            $("#prdPrice"+rowId).val(price);
            $("#lineTotal"+rowId).val(price);
            
            updateTotalPrice(rowId);
        }
    }
    
    function updateTotalPrice(rowId)
    {
        var grandTotal = 0;
        var unitPrice = document.getElementsByName("prdPrice[]");
        var qty = document.getElementsByName("prdQty[]");
        var unitTotal = document.getElementsByName("lineTotal[]");
        for (i = 0; i < unitPrice.length; i++){
            if (unitPrice[i].value != "" && qty[i].value != "") {
                if(qty[i].value == 0){
                    alert("Sorry! You can not add ZERO quantity.")
                    qty[i].value = 1;
                }
                unitQtyTotal = parseFloat(unitPrice[i].value) * parseFloat(qty[i].value);
                unitTotal[i].value = unitQtyTotal;
                grandTotal += parseFloat(unitQtyTotal);
            }
            else {
                grandTotal += 0;
            }
        }
        var roundGrandTotal = Math.round(grandTotal);
        document.getElementById('grandTotal').value = roundGrandTotal.toFixed(2);
    }

    function checkExistProduct(rowId) {
    var itemId = document.getElementById("prdName"+rowId).value;
    var itemCount = document.getElementsByName("prdName[]");
    var itemLength=itemCount.length-1;
    var k=0;
    for (i = 0;  i <= itemLength; i++) {
        if (itemId == itemCount[i].value) {
            k++;
        }
    }
    if (k>1) {
        alert("Product name already added..!!");
        $("#prdName"+rowId).val('');
    }else{
        updatePrice(rowId);
    }
}
</script>
<?php
include('../footer.php');
?>
