<?php
ob_start();
include('../header.php');
$id=base64_decode($_GET['id']);
$agentQuery="SELECT * from agent_details where agent_id='".$id."'";
$agentInfo=$db->query($agentQuery);
if($id=='' || !$agentInfo[0]['agent_id']){
    header("location:agentList.php");
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1> <i  class="fa fa-gears"></i> Edit Agent</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="agentList.php"><i class="fa fa-user"></i> Agents</a></li>
               <li class="active">Edit Agent</li>
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
                    <form class="form-horizontal" method='post'  name='agentEditForm' id='agentEditForm' autocomplete="off" action="editAgentHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="userName" class="col-lg-4 control-label">Agent Name <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="agentName" name="agentName" placeholder="Agent Name" title="Please enter user name" value="<?php echo $agentInfo[0]['agent_name']; ?>"/>
                                                  <input type="hidden" name="agentId" id="agentId" value="<?php echo base64_encode($agentInfo[0]['agent_id']);?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="phoneNo" class="col-lg-4 control-label">Phone Number<span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control checkNum" id="phoneNo" name="phoneNo" placeholder="Phone Number" title="Please enter phone number" value="<?php echo $agentInfo[0]['agent_phone']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                             
                              <div class="row">
                              <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="phoneNo" class="col-lg-4 control-label">Alter Phone Number </label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control checkNum " id="alterPhoneNo" name="alterPhoneNo" placeholder="Phone Number" title="Please enter phone number" value="<?php echo $agentInfo[0]['alter_phone_number'];?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="emailId" class="col-lg-4 control-label">Email </label>
                                             <div class="col-lg-7">
                                             <input type="text" class="form-control  isEmail" id="emailId" name="emailId" placeholder="Email Id" title="Please enter email id" value="<?php echo $agentInfo[0]['agent_email']; ?>" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="status" class="col-lg-4 control-label">Status <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <select class="form-control isRequired" name='status' id='status' title="Please select the status">
                                                       <option value=""> -- Select -- </option>
                                                       <option value="active" <?php echo ($agentInfo[0]['agent_status']=='active')?"selected='selected'":""?>>Active</option>
                                                       <option value="inactive" <?php echo ($agentInfo[0]['agent_status']=='inactive')?"selected='selected'":""?>>Inactive</option>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                         </div>

                         <!-- /.box-body -->
                         <div class="box-footer">
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                              <a href="agentList.php" class="btn btn-default"> Cancel</a>
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
          formId: 'agentEditForm'
     });

     if(flag){
               $.blockUI();
               document.getElementById('agentEditForm').submit();
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
