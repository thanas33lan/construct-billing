<?php
ob_start();
include('../header.php');
$id = base64_decode($_GET['id']);
$billQuery = "SELECT * from bill_details where bill_id='" . $id . "'";
$bResult = $db->query($billQuery);
if ($id == '' || !$bResult[0]['bill_id']) {
     header("location:invoice-list.php");
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1> <i class="fa fa-gears"></i> Update PDF</h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="invoice-list.php"><i class="fa fa-user"></i> Invoice</a></li>
               <li class="active">Update PDF</li>
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
                    <form class="form-horizontal" method='post' name='updatePdfForm' id='updatePdfForm' autocomplete="off" action="updatePDFHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="invoiceDueDate" class="control-label">Invoice Due Date </label>
                                                  <input type="text" class="form-control" id="invoiceDueDate" name="invoiceDueDate" placeholder="Invoice due date" title="Please choose due date" readonly value="<?php echo date('d-M-Y'); ?>" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="termPayment" class="control-label">Mode/Terms of Payment </label>
                                                  <input type="text" class="form-control" name="termPayment" id="termPayment" placeholder="Terms of Payment" title="Terms of Payment" value="<?php echo $bResult[0]['term_payment']; ?>" />
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <div class="col-lg-12">
                                                  <label for="supplierRef" class="control-label">Supplier/Delivery Person Name </label>
                                                  <input type="text" class="form-control" name="supplierRef" id="supplierRef" placeholder="Reference" title="Supplier Reference" value="<?php echo $bResult[0]['supplier_ref']; ?>" />
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <!-- /.box-body -->
                              <div class="box-footer">
                                   <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                                   <input type="hidden" name="billId" id="billId" value="<?php echo $id; ?>" />
                                   <a href="invoice-list.php" class="btn btn-default"> Cancel</a>
                              </div>
                              <!-- /.box-footer -->
                         </div>
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
               formId: 'updatePdfForm'
          });

          if (flag) {
               $.blockUI();
               document.getElementById('updatePdfForm').submit();
          }
     }
     $('#invoiceDueDate').datepicker({
          changeMonth: true,
          changeYear: true,
          dateFormat: 'dd-M-yy',
          minDate: "Today",
          yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
     }).click(function() {
          $('.ui-datepicker-calendar').show();
     });
</script>
<?php
include('../footer.php');
?>