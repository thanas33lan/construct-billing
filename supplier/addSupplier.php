<?php
ob_start();
include('../header.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1><i class="fa fa-gears"></i> Add Supplier</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="supplierList.php"><i class="fa fa-user"></i> Suppliers</a></li>
               <li class="active">Add Supplier</li>
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
                    <form class="form-horizontal" method='post'  name='supplierForm' id='supplierForm' autocomplete="off" action="addSupplierHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="supplierName" class="col-lg-4 control-label">Supplier Name <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="supplierName" name="supplierName" placeholder="Supplier Name" title="Please enter supplier name" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="phoneNo" class="col-lg-4 control-label">Phone Number <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control checkNum isRequired" id="phoneNo" name="phoneNo" placeholder="Phone Number" title="Please enter phone number"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                             
                              <div class="row">
                                <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="phoneNo" class="col-lg-4 control-label">Alter Phone Number </label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control checkNum " id="alterPhoneNo" name="alterPhoneNo" placeholder="Phone Number" title="Please enter phone number"/>
                                             </div>
                                        </div>
                                   </div>
                                <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="supplierName" class="col-lg-4 control-label">Address <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <textarea class="form-control isRequired" id="supplierAddress" name="supplierAddress" placeholder="Supplier Name" title="Please enter supplier address" ></textarea>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="emailId" class="col-lg-4 control-label">Email </label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isEmail" id="emailId" name="emailId" placeholder="Email Id" title="Please enter email id" />
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              
                         </div>
                         <!-- /.box-body -->
                         <div class="box-footer">
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                              <a href="supplierList.php" class="btn btn-default"> Cancel</a>
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
          formId: 'supplierForm'
     });
     if(flag){
        $.blockUI();
        document.getElementById('supplierForm').submit();
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
