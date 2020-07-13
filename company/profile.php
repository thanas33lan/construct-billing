<?php
ob_start();
$title = "EDIT PROFILE";
include('../header.php');
if(!isset($_SESSION['userLoginId']) || ($_SESSION['userLoginId'] != 'tile-admin')){
     header("location:/users/user-list.php");
}
define('UPLOAD_PATH',realpath(__DIR__.DIRECTORY_SEPARATOR.'<?php echo BASE_URL;?>uploads'));
//invoice number generation
$cQuery="SELECT * FROM company_profile";
$cResult = $db->rawQuery($cQuery);

?>
<!-- Content Wrapper. Contains page content -->
<link href="<?php echo BASE_URL;?>assets/css/jasny-bootstrap.min.css" rel="stylesheet" />
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1><i class="fa fa-building"></i> Update Company Profile</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li class="active">Update Company Profile</li>
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
                    <form class="form-horizontal" method='post'  name='companyProfile' id='companyProfile'  enctype="multipart/form-data" autocomplete="off" action="EditCompanyProfileHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="companyName" class="col-lg-4 control-label">Company Name <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired" id="companyName" name="companyName" placeholder="Company Name"  value="<?php echo (isset($cResult[0]['company_name']) && $cResult[0]['company_name'])?$cResult[0]['company_name']:NULL;?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="companyEmail" class="col-lg-4 control-label">Company Email <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired IsEmail" id="companyEmail" name="companyEmail" placeholder="Company Email"  value="<?php echo (isset($cResult[0]['company_email']) && $cResult[0]['company_email'])?$cResult[0]['company_email']:NULL;?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="companySite" class="col-lg-4 control-label">Company Website</label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control" id="companySite" name="companySite" placeholder="Company website"  value="<?php echo (isset($cResult[0]['website']) && $cResult[0]['website'])?$cResult[0]['website']:NULL;?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="companylat" class="col-lg-4 control-label">Map Latitude</label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control" id="companylat" name="companylat" placeholder="Company location latitude"  value="<?php echo (isset($cResult[0]['lat']) && $cResult[0]['lat'])?$cResult[0]['lat']:NULL;?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="companylng" class="col-lg-4 control-label">Map Longitude</label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control" id="companylng" name="companylng" placeholder="Company location longitude"  value="<?php echo (isset($cResult[0]['lng']) && $cResult[0]['lng'])?$cResult[0]['lng']:NULL;?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="companyPhone" class="col-lg-4 control-label">Company Phone <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired checkNum" id="companyPhone" name="companyPhone" placeholder="Company Phone"  value="<?php echo (isset($cResult[0]['company_phone']) && $cResult[0]['company_phone'])?$cResult[0]['company_phone']:NULL;?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="gstIn" class="col-lg-4 control-label">GSTIN <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control isRequired " id="gstIn" name="gstIn" placeholder="GSTIN"  value="<?php echo (isset($cResult[0]['gst_number']) && $cResult[0]['gst_number'])?$cResult[0]['gst_number']:NULL;?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="addressL1" class="col-lg-4 control-label">Address Line One<span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <textarea type="text" class="form-control isRequired" id="addressL1" name="addressL1" placeholder="Address" title="Please enter address"><?php echo $cResult[0]['address_line_one'];?></textarea>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="addressL2" class="col-lg-4 control-label">Address Line Two <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <textarea type="text" class="form-control isRequired" id="addressL2" name="addressL2" placeholder="Address" title="Please enter address"><?php echo $cResult[0]['address_line_two'];?></textarea>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                        <label for="" class="col-lg-4 control-label">Logo Image </label>
                                        <div class="col-lg-8">
                                             <div class="fileinput fileinput-new logoImage" data-provides="fileinput">
                                                  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:200px; height:150px;">
                                                  <?php
                                                  if(isset($cResult[0]['company_logo']) && trim($cResult[0]['company_logo'])!= ''){
                                                  ?>
                                                  <img src="<?php echo BASE_URL;?>uploads/logo/<?php echo $cResult[0]['company_logo']; ?>" alt="Logo">
                                                  <?php } else { ?>
                                                  <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&text=BRAND LOGO">
                                                  <?php } ?>
                                                  </div>
                                                  <div>
                                                  <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span>
                                                  <input type="file" id="logoImage" name="logoImage" title="Please select member image" onchange="getNewlogoImage('<?php echo $cResult[0]['company_logo']; ?>');">
                                                  </span>
                                                  <?php if(isset($cResult[0]['company_logo']) && trim($cResult[0]['company_logo'])!= '' && file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' .  DIRECTORY_SEPARATOR . $cResult[0]['company_logo'])){ ?>
                                                       <a id="clearLogoImage" href="javascript:void(0);" class="btn btn-default" data-dismiss="fileupload" onclick="clearLogoImage('<?php echo $cResult[0]['company_logo']; ?>')">Clear</a>
                                                  <?php } ?>
                                                  <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                  </div>
                                                  </div>
                                                  <div class="box-body">
                                                       Please make sure member image size of: <code>80x80</code>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="cmyCode" class="col-lg-4 control-label">Company Code <span class="mandatory">*</span></label>
                                             <div class="col-lg-7">
                                                  <input type="text" value="<?php echo $cResult[0]['company_code'];?>" class="form-control isRequired" id="cmyCode" name="cmyCode" placeholder="company code" title="Please enter company code" maxlength="3" style="text-transform:uppercase">
                                             </div>
                                        </div>
                                        <div class="form-group">
                                             <label for="altNumber" class="col-lg-4 control-label">Alternate Contact</label>
                                             <div class="col-lg-7">
                                                  <input type="text" value="<?php echo $cResult[0]['alt_number'];?>" class="form-control" id="altNumber" name="altNumber" placeholder="Alternate contact" title="Please enter alternate contact">
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="acName" class="col-lg-4 control-label">Accounter Name</label>
                                             <div class="col-lg-7">
                                                  <input type="text" value="<?php echo $cResult[0]['accounter_name'];?>" class="form-control" id="acName" name="acName" placeholder="Accounter name" title="Please enter accounter name">
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="acBranch" class="col-lg-4 control-label">Bank Branch</label>
                                             <div class="col-lg-7">
                                                  <input type="text" value="<?php echo $cResult[0]['accounte_branch'];?>" class="form-control" id="acBranch" name="acBranch" placeholder="Bank branch" title="Please enter bank branch">
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-groacNumberup">
                                             <label for="acNumber" class="col-lg-4 control-label">Account Number</label>
                                             <div class="col-lg-7">
                                                  <input type="number" value="<?php echo $cResult[0]['accounte_no'];?>" class="form-control" id="acNumber" name="acNumber" placeholder="A/C number" title="Please enter account number">
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="acIfsc" class="col-lg-4 control-label">IFSC Code</label>
                                             <div class="col-lg-7">
                                                  <input type="text" value="<?php echo $cResult[0]['accounte_ifsc'];?>" class="form-control" id="acIfsc" name="acIfsc" placeholder="IFSC Code" title="Please enter ifsc code">
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="cusDeclaration" class="col-lg-4 control-label">Customer Declaration and Terms and Conditions</label>
                                             <div class="col-lg-7">
                                                  <textarea value="<?php echo $cResult[0]['declaration'];?>" class="form-control" id="cusDeclaration" name="cusDeclaration" title="Please enter customer declaration" rows="5"><?php echo $cResult[0]['declaration'];?></textarea>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              
                         <!-- /.box-body -->
                         <div class="box-footer">
                         <input type="hidden" name="companyId" value="<?php echo (isset($cResult[0]['company_id']) && $cResult[0]['company_id']!='')?$cResult[0]['company_id']:NULL;?>"/>
                         <input type="hidden" id="removedLogoImage" name="removedLogoImage"/>
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
<script type="text/javascript" src="<?php echo BASE_URL;?>assets/js/jasny-bootstrap.js"></script>
<script type="text/javascript">

function validateNow(){
     flag = deforayValidator.init({
          formId: 'companyProfile'
     });
     if(flag){
        $.blockUI();
        document.getElementById('companyProfile').submit();
     }
}
function clearLogoImage(img){
    $(".logoImage").fileinput("clear");
    $("#clearLogoImage").addClass("hide");
    $("#removedLogoImage").val(img);
}
function getNewLogoImage(img){
    $("#clearLogoImage").addClass("hide");
    $("#removedLogoImage").val(img);
}
</script>
<?php
include('../footer.php');
?>
