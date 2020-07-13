<?php
ob_start();
include('../header.php');
$id=base64_decode($_GET['id']);
//get all user details
$aQuery="SELECT * FROM user_details where user_status='active' AND login_id != 'tile-admin'";
$userResult = $db->rawQuery($aQuery);
//get all suppliers details
$sQuery="SELECT * FROM supplier_details where supplier_status='active'";
$supplierResult = $db->rawQuery($sQuery);

$expenseQuery="SELECT * from expense_details where expense_id='".$id."'";
$expenseInfo=$db->query($expenseQuery);

if($id=='' || !$expenseInfo[0]['expense_id']){
    header("location:expense-list.php");
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1> <i  class="fa fa-gears"></i> Edit Expense</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="expense-list.php"><i class="fa fa-user"></i> Expense</a></li>
               <li class="active">Edit Expense</li>
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
                    <form class="form-horizontal" method='post'  name='expenseEditForm' id='expenseEditForm' autocomplete="off" action="editExpenseHelper.php">
                         <div class="box-body">
                              <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                  <label for="particulars" class="col-lg-4 control-label">Particulars<span class="mandatory">*</span></label>
                                                  <div class="col-lg-7">
                                                       <input type="text" value="<?php echo $expenseInfo[0]['particulars'];?>" class="form-control isRequired" id="particulars" name="particulars" placeholder="Particulars" title="Please enter particulars" />
                                                       <input type="hidden" value="<?php echo base64_encode($expenseInfo[0]['expense_id']);?>" class="form-control" id="expenseId" name="expenseId"/>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                  <label for="purchasedFrom" class="col-lg-4 control-label">Purchased From</label>
                                                  <div class="col-lg-7">
                                                       <select class="form-control" id="purchasedFrom" name="purchasedFrom" title="Please enter purchased from">
                                                            <option value="">-- Select --</option>
                                                            <?php foreach($supplierResult as $supplier) {?>
                                                                 <option value="<?php echo base64_encode($supplier['supplier_id']);?>" <?php echo ($supplier['supplier_id']==$expenseInfo[0]['purchased_from'])?"selected='selected'":''; ?>><?php echo $supplier['supplier_name'];?></option>
                                                            <?php } ?>
                                                       </select>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              
                                   <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                  <label for="purchasedBy" class="col-lg-4 control-label">Purchased By<span class="mandatory">*</span></label>
                                                  <div class="col-lg-7">
                                                       <select class="form-control" id="purchasedBy" name="purchasedBy" title="Please enter purchased by">
                                                            <option value="">-- Select --</option>
                                                            <?php foreach($userResult as $user) {?>
                                                                 <option value="<?php echo base64_encode($user['user_id']);?>" <?php echo ($user['user_id']==$expenseInfo[0]['purchased_by'])?"selected='selected'":''; ?>><?php echo $user['user_name'];?></option>
                                                            <?php } ?>
                                                       </select>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                  <label for="quantity" class="col-lg-4 control-label">Quantity<span class="mandatory">*</span></label>
                                                  <div class="col-lg-7">
                                                       <input type="text" value="<?php echo $expenseInfo[0]['quantity'];?>" class="form-control isRequired" id="quantity" name="quantity" placeholder="Quantity" title="Please enter the quantity"/>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                  <label for="price" class="col-lg-4 control-label">Price<span class="mandatory">*</span></label>
                                                  <div class="col-lg-7">
                                                       <input type="text" value="<?php echo $expenseInfo[0]['price'];?>" class="form-control isRequired" id="price" name="price" placeholder="Price" title="Please enter the price" />
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                  <label for="amount" class="col-lg-4 control-label">Amount<span class="mandatory">*</span></label>
                                                  <div class="col-lg-7">
                                                       <input type="text" value="<?php echo $expenseInfo[0]['amount'];?>" class="form-control isRequired" id="amount" name="amount" placeholder="Amount" title="Please enter the expense amount" />
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                  <label for="paymentStatus" class="col-lg-4 control-label">Payment Status<span class="mandatory">*</span></label>
                                                  <div class="col-lg-7">
                                                       <select class="form-control" id="paymentStatus" name="paymentStatus" title="Please enter the payment status" >
                                                            <option value="paid-by-cash" <?php echo ($expenseInfo[0]['payment_status']=='paid-by-cash')?"selected='selected'":''; ?>>Paid By Cash</option>
                                                            <option value="online" <?php echo ($expenseInfo[0]['payment_status']=='online')?"selected='selected'":''; ?>>Online</option>
                                                            <option value="cheque" <?php echo ($expenseInfo[0]['payment_status']=='cheque')?"selected='selected'":''; ?>>Cheque</option>
                                                            <option value="pending" <?php echo ($expenseInfo[0]['payment_status']=='pending')?"selected='selected'":''; ?>>Pending</option>
                                                            <option value="emi" <?php echo ($expenseInfo[0]['payment_status']=='emi')?"selected='selected'":''; ?>>EMI</option>
                                                       </select>
                                                  </div>
                                             </div>
                                        </div>
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                  <label for="remarks" class="col-lg-4 control-label">Remarks</label>
                                                  <div class="col-lg-7">
                                                       <textarea class="form-control" id="remarks" name="remarks" title="Please enter the remarks" ><?php echo $expenseInfo[0]['remarks'];?></textarea>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>

                         <!-- /.box-body -->
                         <div class="box-footer">
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                              <a href="expense-list.php" class="btn btn-default"> Cancel</a>
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

function validateNow(){
     flag = deforayValidator.init({
          formId: 'expenseEditForm'
     });

     if(flag){
          $.blockUI();
          document.getElementById('expenseEditForm').submit();
     }
}

function checkNameValidation(tableName,fieldName,obj,fnct,alrt,callback){
     var removeDots=obj.value.replace(/\,/g,"");
     //str=obj.value;
     removeDots = removeDots.replace(/\s{2,}/g,' ');
     $.post("../includes/checkDuplicate.php", { tableName: tableName,fieldName : fieldName ,value : removeDots.trim(),fnct : fnct, format: "html"},
     function(data){
          if(data==='1'){
               alert(alrt);
               document.getElementById(obj.id).value="";
          }
     });
}

</script>
<?php
include('../footer.php');
?>
