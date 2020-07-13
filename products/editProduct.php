<?php
ob_start();
include('../header.php');
$id = base64_decode($_GET['id']);
$productQuery = "SELECT pd.product_description,pd.product_id,pd.supplier_id,pd.product_name,pd.hsn_code,pd.qty_available,pd.minimum_qty,pd.product_price,pd.product_tax,pd.product_status,sd.actual_qty,sd.quantity,sd.actual_price,sd.stock_status FROM product_details as pd  LEFT JOIN stock_details as sd ON pd.product_id=sd.product_id where pd.product_id='" . $id . "'";
// echo $productQuery;die;
$productInfo = $db->query($productQuery);
if ($id == '' || !$productInfo[0]['product_id']) {
     header("location:product-list.php");
}

$aQuery = "SELECT supplier_id,supplier_name FROM supplier_details where supplier_status='active'";
$aResult = $db->rawQuery($aQuery);

$oQuery = "SELECT * FROM options where product_id = $id";
$oResult = $db->rawQuery($oQuery);

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1> <i class="fa fa-gears"></i> Edit Product</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="product-list.php"><i class="fa fa-list"></i> Products</a></li>
               <li class="active">Edit Product</li>
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
                    <form class="form-horizontal" method='post' name='productEditForm' id='productEditForm' autocomplete="off" action="editProductHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="productName" class="col-lg-4 control-label">Product Name <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="productName" name="productName" placeholder="Product Name" title="Please enter product name" value="<?php echo $productInfo[0]['product_name']; ?>" onblur="checkNameValidation('product_details','product_name',this,'<?php echo "product_id##" . $productInfo[0]['product_id']; ?>','This product name that you entered already exists.Try another name')" />
                                                  <input type="hidden" name="productId" id="productId" value="<?php echo base64_encode($productInfo[0]['product_id']); ?>" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="prdDesc" class="col-lg-4 control-label">Description</label>
                                             <div class="col-lg-7">
                                                  <textarea type="text" class="form-control" id="prdDesc" name="prdDesc" placeholder="Please enter the description" title="Please enter description"><?php echo $productInfo[0]['product_description']; ?></textarea>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="hsnCode" class="col-lg-4 control-label">HSN Code <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="hsnCode" name="hsnCode" placeholder="HSN Code" title="Please enter HSN code" value="<?php echo $productInfo[0]['hsn_code']; ?>" onblur="checkNameValidation('product_details','hsn_code',this,'<?php echo "product_id##" . $productInfo[0]['product_id']; ?>,'This hsn code that you entered already exists.Try another hsn code')" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="productPrice" class="col-lg-4 control-label">Product Price <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired checkNum" id="productPrice" name="productPrice" placeholder="Product Price" title="Please enter product price" value="<?php echo $productInfo[0]['product_price']; ?>" />
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="supplier" class="col-lg-4 control-label">Select Supplier <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <select name="supplier" id="supplier" class="form-control isRequired" title="Choose supplier">
                                                       <option value="">-- Select --</option>
                                                       <?php foreach ($aResult as $supplier) { ?>
                                                            <option value="<?php echo $supplier['supplier_id']; ?>" <?php echo ($productInfo[0]['supplier_id'] == $supplier['supplier_id']) ? 'selected="selected"' : ''; ?>><?php echo ucwords($supplier['supplier_name']); ?></option>;
                                                       <?php } ?>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="tax" class="col-lg-4 control-label">Tax(%) <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired checkNum" id="tax" name="tax" placeholder="Tax" title="Please enter tax" value="<?php echo $productInfo[0]['product_tax']; ?>" />
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="pStatus" class="col-lg-4 control-label">Status <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <select class="form-control isRequired" name='pStatus' id='pStatus' title="Please select the status">
                                                       <option value=""> -- Select -- </option>
                                                       <option value="active" <?php echo ($productInfo[0]['product_status'] == 'active') ? "selected='selected'" : "" ?>>Active</option>
                                                       <option value="inactive" <?php echo ($productInfo[0]['product_status'] == 'inactive') ? "selected='selected'" : "" ?>>Inactive</option>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <hr>
                              <div class="row">
                                   <h4 style="margin-left:20px;padding:0px;margin-top:2px;">Stock Details</h4>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="productPrice" class="col-lg-4 control-label">Product Quantity <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired checkNum" id="actualQty" value="<?php echo $productInfo[0]['qty_available']; ?>" name="actualQty" placeholder="Product Actual Qty" title="Please enter product actual quantity" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="productPrice" class="col-lg-4 control-label">Minimum Quantity <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired checkNum" id="minimumQty" value="<?php echo $productInfo[0]['minimum_qty']; ?>" name="minimumQty" placeholder="Product Minimum Qty" title="Please enter product minimum quantity" />
                                                  <code>Set minimum available Quantity</code>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <hr>
                         <h2>Options</h2>
                         <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed">
                              <thead>
                                   <tr>
                                        <th align="center">Option Name </th>
                                        <th align="center">Value</th>
                                        <th align="center">Status</th>
                                        <th align="center">Action</th>
                                   </tr>
                              </thead>
                              <tbody id="optionTable">
                                   <?php 
                                   if(count($oResult) > 0){
                                        foreach($oResult as $key=>$row){ ?>
                                             <tr>
                                                  <td><input type="text" value="<?php echo $row['option_name'];?>" class="form-control" id="name<?php echo ($key+1);?>" name="name[]" placeholder="Option name" title="Please enter the option name" /></td>
                                                  <td><input type="text" value="<?php echo $row['option_value'];?>" name="value[]" id="value<?php echo ($key+1);?>" class="form-control" placeholder="Option value" title="Please enter the option value" /></td>
                                                  <td>
                                                       <select name="status[]" id="status<?php echo ($key+1);?>" class="form-control" title="Choose status">
                                                            <option value="active" <?php echo (isset($row['option_status']) && $row['option_status'] == 'active')?'selected="selected"':'';?>>Active</option>
                                                            <option value="inactive" <?php echo (isset($row['option_status']) && $row['option_status'] == 'inactive')?'selected="selected"':'';?>>Inactive</option>
                                                       </select>
                                                  </td>
                                                  <td align="center" style="vertical-align:middle;">
                                                       <input type="hidden" id="optionId<?php echo ($key+1);?>" name="optionId[]" value="<?php echo $row['id'];?>"/>
                                                       <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insOptionRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" id="<?php echo $row['id'];?>" onclick="deletedOptionRow(this.id);removeAttributeOptionRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                                  </td>
                                             </tr>
                                             <?php }
                                   } else{ ?>
                                   <tr>
                                        <td><input type="text" class="form-control" id="name1" name="name[]" placeholder="Option name" title="Please enter the option name"/></td>
                                        <td><input type="text" name="value[]" id="value1" class="form-control" placeholder="Option value" title="Please enter the option value"/></td>
                                        <td>
                                             <select name="status[]" id="status1" class="form-control" title="Choose status">
                                                  <option value="active">Active</option>
                                                  <option value="inactive">Inactive</option>
                                             </select>
                                        </td>
                                        <td align="center" style="vertical-align:middle;">
                                             <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insOptionRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeOptionRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                        </td>
                                   </tr>
                                   <?php }?>
                              </tbody>
                         </table>
                         <!-- /.box-body -->
                         <div class="box-footer">
                              <input type="hidden" id="deletedRow" name="deletedRow" value=""/>
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                              <a href="product-list.php" class="btn btn-default"> Cancel</a>
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
     var optionTableRowId = <?php echo (count($oResult) > 0)?(count($oResult) + 1) : 2;?>;
     var deletedRow = [];

     function validateNow() {
          flag = deforayValidator.init({
               formId: 'productEditForm'
          });

          if (flag) {
               $.blockUI();
               document.getElementById('productEditForm').submit();
          }
     }

     function checkNameValidation(tableName, fieldName, obj, fnct, alrt, callback) {
          var removeDots = obj.value.replace(/\,/g, "");
          //str=obj.value;
          removeDots = removeDots.replace(/\s{2,}/g, ' ');
          $.post("../includes/checkDuplicate.php", {
                    tableName: tableName,
                    fieldName: fieldName,
                    value: removeDots.trim(),
                    fnct: fnct,
                    format: "html"
               },
               function(data) {
                    if (data === '1') {
                         alert(alrt);
                         document.getElementById(obj.id).value = "";
                    }
               });
     }

     function deletedOptionRow(id) {
          deletedRow.push(id);
          $('#deletedRow').val(deletedRow);
     }

     function insOptionRow() {
          rl = document.getElementById("optionTable").rows.length;
          var a = document.getElementById("optionTable").insertRow(rl);
          a.setAttribute("style", "display:none");
          var b = a.insertCell(0);
          var c = a.insertCell(1);
          var d = a.insertCell(2);
          var e = a.insertCell(3);
          e.setAttribute("align", "center");
          e.setAttribute("style", "vertical-align:middle");

          b.innerHTML = '<input type="text" class="form-control isRequired" id="name' + optionTableRowId + '" name="name[]" placeholder="Option name" title="Please enter the option name"/>';
          c.innerHTML = '<input type="text" name="value[]" id="value' + optionTableRowId + '" class="form-control" placeholder="Option value" title="Please enter the option value"/>';
          d.innerHTML = '<select name="status[]" id="status' + optionTableRowId + '" class="form-control" title="Choose status"><option value="active">Active</option> <option value="inactive">Inactive</option> </select>';
          e.innerHTML = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insOptionRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeOptionRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>';
          $(a).fadeIn(800);
          optionTableRowId++;
     }

     function removeAttributeOptionRow(el) {
          $(el).fadeOut("slow", function() {
               el.parentNode.removeChild(el);
               rl = document.getElementById("optionTable").rows.length;
               if (rl == 0) {
                    insOptionRow();
               }
          });
     }
</script>
<?php
include('../footer.php');
?>