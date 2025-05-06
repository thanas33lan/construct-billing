<?php
ob_start();
include('../header.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1><i class="fa fa-gears"></i> Add Client</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="client-list.php"><i class="fa fa-user"></i> Clients</a></li>
               <li class="active">Add Client</li>
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
                    <form class="form-horizontal" method='post' name='clientForm' id='clientForm' autocomplete="off" action="addClientHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="clientName" class="control-label">Client Name <span class="mandatory">*</span></label>
                                                  <input type="text" class="form-control isRequired" id="clientName" name="clientName" placeholder="Client Name" title="Please enter client name" onblur="checkNameValidation('client_details','client_name',this,null,'This name that you entered already exists.Try another name',null)" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="phoneNo" class="control-label">Phone Number </label>
                                                  <input type="text" class="form-control checkNum" id="phoneNo" name="phoneNo" placeholder="Phone Number" title="Please enter phone number" />
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="phoneNo" class="control-label">Alter Phone Number </label>
                                                  <input type="text" class="form-control checkNum " id="alterPhoneNo" name="alterPhoneNo" placeholder="Phone Number" title="Please enter phone number" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="clientEmail" class="control-label">Client Email </label>
                                                  <input type="text" class="form-control isEmail" id="clientEmail" name="clientEmail" placeholder="Email" title="Please enter email" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="phoneNo" class="control-label">Address <span class="mandatory">*</span></label>
                                                  <textarea type="text" class="form-control isRequired" id="address" name="address" placeholder="Address" title="Please enter address"></textarea>
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="clientName" class="control-label">Shipping Address <span class="mandatory">*</span></label>
                                                  <textarea type="text" class="form-control isRequired" id="shipAddress" name="shipAddress" placeholder="Shipping Address" title="Please enter shipping address"></textarea>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="clientEmail" class="control-label">GSTIN <span class="mandatory">*</span></label>
                                                  <input type="text" class="form-control isRequired" id="gstIn" name="gstIn" placeholder="GSTIN" title="Please enter GSTIN Number" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="status" class="control-label">Status <span class="mandatory">*</span></label>
                                                  <select class="form-control isRequired" name='status' id='status' title="Please select the status">
                                                       <option value=""> -- Select -- </option>
                                                       <option value="active">Active</option>
                                                       <option value="inactive">Inactive</option>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                         </div>
                         <!-- /.box-body -->
                         <div class="box-footer">
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                              <a href="client-list.php" class="btn btn-default"> Cancel</a>
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
     function validateNow() {
          flag = deforayValidator.init({
               formId: 'clientForm'
          });
          if (flag) {
               $.blockUI();
               document.getElementById('clientForm').submit();
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
</script>
<?php
include('../footer.php');
?>