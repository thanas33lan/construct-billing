<?php
ob_start();
include('../header.php');
//get All product details
$hsnQuery = "SELECT product_id FROM product_details ORDER BY product_id DESC LIMIT 1";
$hsnResult = $db->rawQuery($hsnQuery);
if (isset($hsnResult[0]['product_id']) && $hsnResult[0]['product_id'] != '') {
     $strparam = strlen($hsnResult[0]['product_id'] + 1);
     $zeros = substr("0000", $strparam);
     $hsnNoUnq = $zeros . $hsnResult[0]['product_id'] + 1;
} else {
     $hsnNoUnq = '0001';
}
$hsnCodeKey = sprintf("%04d", $hsnNoUnq);
$hsnNo = $cmyResult[0]['company_code'] . $hsnCodeKey;
//get all supplier details
$aQuery = "SELECT supplier_id,supplier_name FROM supplier_details where supplier_status='active'";
$aResult = $db->rawQuery($aQuery);
$supplierDetail = '';
foreach ($aResult as $supplier) {
     $supplierDetail .= '<option value="' . $supplier['supplier_id'] . '">' . ucwords($supplier['supplier_name']) . '</option>';
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1><i class="fa fa-gears"></i> Add Product</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="product-list.php"><i class="fa fa-product"></i> Products</a></li>
               <li class="active">Add product</li>
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
                    <form class="form-horizontal" method='post' name='productForm' id='productForm' autocomplete="off" action="addProductHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="productName" class="control-label">Product Name <span class="mandatory">*</span></label>
                                                  <input type="text" class="form-control isRequired" id="productName" name="productName" placeholder="Product Name" title="Please enter product name" onblur="checkNameValidation('product_details','product_name',this,null,'This product name that you entered already exists.Try another name')" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="prdDesc" class="control-label">Description</label>
                                                  <textarea type="text" class="form-control" id="prdDesc" name="prdDesc" placeholder="Please enter the description" title="Please enter description"></textarea>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="hsnCode" class="control-label">HSN Code <span class="mandatory">*</span></label>
                                                  <input type="text" value="<?php echo $hsnNo; ?>" class="form-control isRequired" id="hsnCode" name="hsnCode" placeholder="HSN Code" title="Please enter HSN code" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="tax" class="control-label">Tax(%) <span class="mandatory">*</span></label>
                                                  <input type="text" class="form-control isRequired  checkNum" id="tax" name="tax" placeholder="Tax" title="Please enter tax" />
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="supplier" class="control-label">Select Supplier <span class="mandatory">*</span></label>
                                                  <select name="supplier" id="supplier" class="form-control isRequired" title="Choose supplier">
                                                       <option value="">-- Select --</option>
                                                       <?php echo $supplierDetail; ?>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="productPrice" class="control-label">Product Price <span class="mandatory">*</span></label>
                                                  <input type="text" class="form-control isRequired checkNum" id="productPrice" name="productPrice" placeholder="Product Price" title="Please enter product price" />
                                                  <code>Including GST</code>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <hr>
                              <div class="row">
                                   <h4 style="margin-left:20px;padding:0px;margin-top:2px;">Stock Details</h4>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="productPrice" class="control-label">Product Quantity <span class="mandatory">*</span></label>
                                                  <input type="text" class="form-control isRequired checkNum" id="actualQty" value="<?php echo $productInfo[0]['qty_available']; ?>" name="actualQty" placeholder="Product Actual Qty" title="Please enter product actual quantity" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="productPrice" class="control-label">Minimum Quantity <span class="mandatory">*</span></label>
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
                                   <tr>
                                        <td><input type="text" class="form-control" id="name1" name="name[]" placeholder="Option name" title="Please enter the option name" /></td>
                                        <td><input type="text" name="value[]" id="value1" class="form-control" placeholder="Option value" title="Please enter the option value" /></td>
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
                              </tbody>
                         </table>
                         <!-- /.box-body -->
                         <div class="box-footer">
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
     var optionTableRowId = 2;

     function validateNow() {
          flag = deforayValidator.init({
               formId: 'productForm'
          });
          if (flag) {
               $.blockUI();
               document.getElementById('productForm').submit();
          }
     }

     function checkNameValidation(tableName, fieldName, obj, fnct, alrt) {
          var removeDots = obj.value.replace(/\,/g, "");
          //str=obj.value;
          removeDots = removeDots.replace(/\s{2,}/g, ' ');
          $.post("../includes/checkDuplicate.php", {
                    tableName: tableName,
                    fieldName: fieldName,
                    value: removeDots.trim(),
                    fnct: fnct
               },
               function(data) {
                    if (data === '1') {
                         alert(alrt);
                         document.getElementById(obj.id).value = "";
                    }
               });
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
     $(document).ready(function() {
          $("#supplier").select2({
               placeholder: "Enter supplier name",
               minimumInputLength: 0,
               width: '100%',
               allowClear: true,
               ajax: {
                    placeholder: "Type supplier name to search",
                    url: "get-supplier-list.php",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                         return {
                              q: params.term, // search term
                              page: params.page
                         };
                    },
                    processResults: function(data, params) {
                         params.page = params.page || 1;
                         return {
                              results: data.result,
                              pagination: {
                                   more: (params.page * 30) < data.total_count
                              }
                         };
                    },
                    //cache: true
               },
               escapeMarkup: function(markup) {
                    return markup;
               }
          });
     });
</script>
<?php
include('../footer.php');
?>