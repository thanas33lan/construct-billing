<?php
ob_start();
include('../header.php');
$id=base64_decode($_GET['id']);
$userQuery="SELECT * from user_details where user_id='".$id."'";
$userInfo=$db->query($userQuery);
if($id=='' || !$userInfo[0]['user_id']){
    header("location:user-list.php");
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1> <i  class="fa fa-gears"></i> Edit User</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="user-list.php"><i class="fa fa-user"></i> Users</a></li>
               <li class="active">Edit Users</li>
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
                    <form class="form-horizontal" method='post'  name='userEditForm' id='userEditForm' autocomplete="off" action="editUserHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="userName" class="col-lg-4 control-label">User Name <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="userName" name="userName" placeholder="User Name" title="Please enter user name" value="<?php echo $userInfo[0]['user_name']; ?>"/>
                                                  <input type="hidden" name="userId" id="userId" value="<?php echo base64_encode($userInfo[0]['user_id']);?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="phoneNo" class="col-lg-4 control-label">Phone Number</label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control" id="phoneNo" name="phoneNo" placeholder="Phone Number" title="Please enter phone number" value="<?php echo $userInfo[0]['user_phone']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                             
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="loginId" class="col-lg-4 control-label">Login Id <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="loginId" name="loginId" placeholder="Login Id" title="Please enter login id" value="<?php echo $userInfo[0]['login_id']; ?>" onblur="checkNameValidation('user_details','login_id',this,'<?php echo "user_id##".$userInfo[0]['user_id'];?>','This login id that you entered already exists.Try another login id',null)"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="password" class="col-lg-4 control-label">Password </label>
                                             <div class="col-lg-7">
                                                  <input type="password" class="form-control ppwd" id="confirmPassword" name="password" placeholder="Password" title="Please enter the password"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="confirmPassword" class="col-lg-4 control-label">Confirm Password</label>
                                             <div class="col-lg-7">
                                                  <input type="password" class="form-control cpwd confirmPassword" id="confirmPassword" name="password" placeholder="Confirm Password" title="" />
                                             </div>
                                        </div>
                                   </div>

                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="status" class="col-lg-4 control-label">Status <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <select class="form-control isRequired" name='status' id='status' title="Please select the status">
                                                       <option value=""> -- Select -- </option>
                                                       <option value="active" <?php echo ($userInfo[0]['user_status']=='active')?"selected='selected'":""?>>Active</option>
                                                       <option value="inactive" <?php echo ($userInfo[0]['user_status']=='inactive')?"selected='selected'":""?>>Inactive</option>
                                                  </select>
                                             </div>
                                        </div>
                                   </div>

                              </div>

                         </div>

                         <!-- /.box-body -->
                         <div class="box-footer">
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                              <a href="user-list.php" class="btn btn-default"> Cancel</a>
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
          formId: 'userEditForm'
     });

     if(flag){
               $.blockUI();
               document.getElementById('userEditForm').submit();
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
