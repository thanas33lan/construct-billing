<?php
ob_start();
include('../header.php');
$general = new General();
//invoice number generation
$bQuery="SELECT * FROM bill_details where bill_id='".base64_decode($_GET['id'])."'";
$bResult = $db->rawQuery($bQuery);

//get pay details
$pQuery="SELECT * FROM paid_details where bill_id='".base64_decode($_GET['id'])."'";
$pResult = $db->rawQuery($pQuery);

//get all agent details
$aQuery="SELECT * FROM agent_details where agent_status='active'";
$aResult = $db->rawQuery($aQuery);
$agentDetail = '';
foreach($aResult as $agent)
{
    $agentDetail .= '<option value="'.$agent['agent_id'].'">'.ucwords($agent['agent_name']).'</option>';
}
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <h1><i class="fa fa-money"></i> Pay </h1>
          <ol class="breadcrumb">
               <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
               <li class="active">Add Payment</li>
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
                    <form class="form-horizontal" method='post'  name='invoiceGenerate' id='invoiceGenerate' autocomplete="off" action="payInvoiceHelper.php">
                         <div class="box-body">
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="invoiceNo" class="col-lg-4 control-label">Invoice Number </label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control " id="invoiceNo" name="invoiceNo" readonly value="<?php echo $bResult[0]['invoice_no'];?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="invoiceDate" class="col-lg-4 control-label">Invoice Date </label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control" id="invoiceDate" name="invoiceDate" placeholder="Invoice Date" title="Please choose date" readonly value="<?php echo $general->humanDateFormat($bResult[0]['invoice_date']);?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="invoiceDueDate" class="col-lg-4 control-label">Invoice Due Date </label>
                                             <div class="col-lg-7">
                                                  <input type="text" class="form-control" id="invoiceDueDate" name="invoiceDueDate" placeholder="Invoice Due Date" title="Please choose date" value="<?php echo $general->humanDateFormat($bResult[0]['invoice_due_date']);?>"/>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="clientName" class="col-lg-4 control-label">Client Name </label>
                                             <div class="col-lg-7">
                                                  <input type="text" name="clientName" id="clientName" class="form-control" value="<?php echo $bResult[0]['client_name'];?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="phoneNo" class="col-lg-4 control-label">Billing Address </label>
                                             <div class="col-lg-7">
                                                  <textarea type="text" class="form-control " id="address" name="address" placeholder="Address" title="Please enter address"><?php echo $bResult[0]['billing_address'];?></textarea>
                                             </div>
                                        </div>
                                   </div>
                                   <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="shipAddress" class="col-lg-4 control-label">Shipping Address </label>
                                             <div class="col-lg-7">
                                                  <textarea type="text" class="form-control " id="shipAddress" name="shipAddress" placeholder="Shipping Address" title="Please enter shipping address"><?php echo $bResult[0]['shipping_address'];?></textarea>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                             <label for="phoneNo" class="col-lg-4 control-label">Total Amount </label>
                                             <div class="col-lg-7">
                                                  <span type="text" class="form-control"><?php echo $bResult[0]['total_amount'];?></span>
                                                  <input type="hidden" name="grandTotal" id="grandTotal" value="<?php echo $bResult[0]['total_amount'];?>"/>
                                             </div>
                                        </div>
                                   </div>
                              </div>
                              
		                <!-- </div> -->
                         </div>

                        <!-- payment options -->
                        <div class="box-header">
                            <h3 class="box-title ">Payment Options</h3>
                        </div>
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="width:15%;">Pay Option </th>
                                    <th style="width:15%;">Paid On</th>
                                    <th style="width:40%;">Pay Details </th>
                                    <th style="width:15%">Amount </th>
                                    <th style="width:15%;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="payTable">
                            <?php
                            if(count($pResult)>0){
                                $i = 1;
                                foreach($pResult as $result)
                                {
                                    ?>
                                <tr>
                                    <td>
                                        <select name="payOption[]" id="payOption<?php echo $i;?>" class="form-control " title="Choose option" onchange="showHideEmi('<?php echo $i; ?>')">
                                            <option value="">-- Select --</option>
                                            <option value="cash" <?php echo ($result['pay_option']=='cash')? "selected='selected'":'';?>>Cash</option>
                                            <option value="cheque" <?php echo ($result['pay_option']=='cheque')? "selected='selected'":'';?>>Cheque</option>
                                            <option value="online" <?php echo ($result['pay_option']=='online')? "selected='selected'":'';?>>Online</option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" value="<?php echo $general->humanDateFormat($result['paid_on']);?>" class="form-control isRequired" id="paidOn<?php echo $i;?>" name="paidOn[]" placeholder="Paid Date" title="Please choose date"/>
                                    </td>
                                    <td><textarea name="payDetails[]" id="payDetails<?php echo $i;?>" class="form-control" placeholder="Enter Details"><?php echo $result['pay_details'];?></textarea></td>
                                    <td><input type="text" name="payAmt[]" id="payAmt<?php echo $i;?>" class="form-control" placeholder="Amount" value="<?php echo $result['paid_amount'];?>" onkeyup="updatepayTotal()"/></td>
                                    <td align="center" style="vertical-align:middle;">
                                        <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insPayRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributePayRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                    </td>
                                </tr>
                                    <?php
                                    $i++;
                                }
                            }else{
                                $i = 1;
                            ?>
                                <tr>
                                    <td>
                                        <select name="payOption[]" id="payOption1" class="form-control " title="Choose option" onchange="showHideEmi(1)">
                                            <option value="">-- Select --</option>
                                            <option value="cash">Cash</option>
                                            <option value="cheque">Cheque</option>
                                            <option value="online">Online</option>
                                            <option value="emi">EMI</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control isRequired" id="paidOn1" name="paidOn[]" placeholder="Paid Date" title="Please choose date"/>
                                    </td>
                                    <td>
                                        <select name="agentName[]" id="agentName1" class="form-control " title="Choose option">
                                            <option value="">-- Select --</option>
                                            <?php echo $agentDetail;?>
                                        </select>
                                    </td>
                                    <td><textarea name="payDetails[]" id="payDetails1" class="form-control" placeholder="Enter Details"></textarea></td>
                                    <td><input type="text" name="payAmt[]" id="payAmt1" class="form-control" placeholder="Amount" onkeyup="updatepayTotal()"/></td>
                                    <td align="center" style="vertical-align:middle;">
                                        <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insPayRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributePayRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3"><strong style="float:right;">Paid Amount</strong></td>
                                    <td ><input type="text" id="paidGrandTotal" name="paidGrandTotal" class="form-control isRequired checkNum" placeholder="Grand Total" title="Grand Total for this order" readonly/></td>
                                </tr>
                            </tfoot>
                        </table>

                         <!-- /.box-body -->
                         <div class="box-footer">
                         <input type="hidden" name="billId" value="<?php echo $bResult[0]['bill_id'];?>"/>
                              <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
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
var payTableRowId = <?php echo $i;?>;
$(document).ready(function() {
    updatepayTotal();
    $('#paidOn1').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            minDate: "Today",
            yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
        }).click(function(){
            $('.ui-datepicker-calendar').show();
        });

    <?php
    if(count($pResult)>0){
        $x = 1;
        foreach($pResult as $result)
        {
            ?>
            showHideEmi('<?php echo $x;?>');
            $('#paidOn<?php echo $x;?>').datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'dd-M-yy',
                        minDate: "Today",
                        yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
                    }).click(function(){
                        $('.ui-datepicker-calendar').show();
                    });
            <?php
            $x++;
        }
    }
    ?>
});

function validateNow(){
     flag = deforayValidator.init({
          formId: 'invoiceGenerate'
     });
     if(flag){
        var gt = $("#grandTotal").val();
        var pt = $("#paidGrandTotal").val();
        if(pt<gt)
        {
            alert("Payment total not greater than purchase total!");
            return false;
        }
        $.blockUI();
        document.getElementById('invoiceGenerate').submit();
    }
}

//payemtn option
function insPayRow() {
    rl = document.getElementById("payTable").rows.length;
        var a = document.getElementById("payTable").insertRow(rl);
        a.setAttribute("style", "display:none");
        var b = a.insertCell(0);
        var c = a.insertCell(1);
        var d = a.insertCell(2);
        var e = a.insertCell(3);
        var f = a.insertCell(4);
        var g = a.insertCell(5);
        f.setAttribute("align", "center");
        f.setAttribute("style","vertical-align:middle");
        
        b.innerHTML = '<select name="payOption[]" id="payOption' + payTableRowId + '" class="form-control " title="Choose option"  onchange="showHideEmi('+payTableRowId+')">\
                                            <option value="">-- Select --</option>\
                                            <option value="cash">Cash</option>\
                                            <option value="cheque">Cheque</option>\
                                            <option value="online">Online</option>\
                                            <option value="emi">EMI</option>\
                                        </select>';
        c.innerHTML = '<input type="text" class="form-control isRequired" id="paidOn' + payTableRowId + '" name="paidOn[]" placeholder="Paid Date" title="Please choose date"/>';
        d.innerHTML = '<textarea name="payDetails[]" id="payDetails' + payTableRowId + '" class="form-control" placeholder="Enter Details"></textarea>';
        e.innerHTML = '<input type="text" name="payAmt[]" id="payAmt' + payTableRowId + '" class="form-control" placeholder="Amount" onkeyup="updatepayTotal()"/>';
        f.innerHTML = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insPayRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributePayRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>';
        $('#paidOn'+payTableRowId).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            minDate: "Today",
            yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
        }).click(function(){
            $('.ui-datepicker-calendar').show();
        });
        $(a).fadeIn(800);
        payTableRowId++;
    }
    function removeAttributePayRow(el) {
        $(el).fadeOut("slow", function() {
            el.parentNode.removeChild(el);
            rl = document.getElementById("payTable").rows.length;
            if (rl == 0) {
                insPayRow();
            }
            updatepayTotal();
        });
    }
    function showHideEmi(payRowId)
    {
        if($("#payOption"+payRowId).val()=='emi'){
            $("#agentName"+payRowId+" option").show();
        }else{
            $("#agentName"+payRowId).val('');
            $("#agentName"+payRowId+" option").hide();
        }
    }

    function updatepayTotal()
    {
        var paidGrandTotal = 0;
        var paidPrice = document.getElementsByName("payAmt[]");

        for (i = 0; i < paidPrice.length; i++){
            if(paidPrice[i].value!='')
            {
                paidGrandTotal += parseFloat(paidPrice[i].value);
            }
        }
        var roundGrandTotal = Math.round(paidGrandTotal);
        document.getElementById('paidGrandTotal').value = roundGrandTotal.toFixed(2);
    }

</script>
<?php
include('../footer.php');
?>
