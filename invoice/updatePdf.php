<?php
ob_start();
include('../header.php');
$id=base64_decode($_GET['id']);
$billQuery="SELECT * from bill_details where bill_id='".$id."'";
$bResult=$db->query($billQuery);
if($id=='' || !$bResult[0]['bill_id']){
    header("location:invoice-list.php");
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1> <i  class="fa fa-gears"></i> Update PDF</h1>
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
                    <form class="form-horizontal" method='post'  name='updatePdfForm' id='updatePdfForm' autocomplete="off" action="updatePDFHelper.php">
                         <div class="box-body">
                         <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="deliveryNote" class="col-lg-4 control-label">Delivery Note </label>
                                             <div class="col-lg-7">
                                                  <textarea type="text" class="form-control" id="deliveryNote" name="deliveryNote" placeholder="Delivery Note" title="Please enter delivery note"><?php echo $bResult[0]['delivery_note']; ?></textarea>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="termPayment" class="col-lg-4 control-label">Mode/Terms of Payment </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="termPayment" id="termPayment" placeholder="Terms of Payment" title="Terms of Payment" value="<?php echo $bResult[0]['term_payment']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="supplierRef" class="col-lg-4 control-label">Supplier's Ref </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="supplierRef" id="supplierRef" placeholder="Reference" title="Supplier Reference" value="<?php echo $bResult[0]['supplier_ref']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="otherRef" class="col-lg-4 control-label">Other Reference(s) </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="otherRef" id="otherRef" placeholder="Reference" title="Other Reference" value="<?php echo $bResult[0]['other_ref']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              
                              <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="buyerOrderNo" class="col-lg-4 control-label">Buyer Order No </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="buyerOrderNo" id="buyerOrderNo" placeholder="Order Number" title="Order Number" value="<?php echo $bResult[0]['buyer_order_no']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="buyerDate" class="col-lg-4 control-label">Dated </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="buyerDate" id="buyerDate" placeholder="Buyer Date" title="Buyer Date" value="<?php echo $bResult[0]['buyer_date']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="docNo" class="col-lg-4 control-label">Dispatch Document No </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="docNo" id="docNo" placeholder="Document Number" title="Document Number" value="<?php echo $bResult[0]['dispatch_doc_no']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="deliveryNoteDate" class="col-lg-4 control-label">Delivery Note Date </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="deliveryNoteDate" id="deliveryNoteDate" placeholder="Note Date" title="Note date" value="<?php echo $bResult[0]['delivery_note_date']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="disThrough" class="col-lg-4 control-label">Dispatch Through </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="disThrough" id="disThrough" placeholder="Dispatch through" title="Dispatch through" value="<?php echo $bResult[0]['dispatch_through']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="destination" class="col-lg-4 control-label">Destination </label>
                                             <div class="col-lg-7">
                                                <input type="text" class="form-control" name="destination" id="destination" placeholder="Destination" title="Destination" value="<?php echo $bResult[0]['destination']; ?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                              <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="termDelivery" class="col-lg-4 control-label">Terms Of Delivery </label>
                                             <div class="col-lg-7">
                                                <textarea class="form-control" name="termDelivery" id="termDelivery" placeholder="Terms Of Delivery" title="Terms Of Delivery"><?php echo $bResult[0]['term_delivery'];?></textarea>
                                             </div>
                                        </div>
                                   </div>
                              </div>

                         <!-- /.box-body -->
                         <div class="box-footer">
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                              <input type="hidden" name="billId" id="billId" value="<?php echo $id;?>"/>
                              <a href="invoice-list.php" class="btn btn-default"> Cancel</a>
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
          formId: 'updatePdfForm'
     });

     if(flag){
               $.blockUI();
               document.getElementById('updatePdfForm').submit();
     }
}


</script>
<?php
include('../footer.php');
?>
