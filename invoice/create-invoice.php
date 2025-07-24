<?php
ob_start();
include('../header.php');
//invoice number generation
$bQuery = "SELECT bill_id FROM bill_details order by bill_id DESC limit 1";
$bResult = $db->rawQuery($bQuery);
$cmyQuery = "SELECT company_code FROM company_profile";
$cmyResult = $db->rawQuery($cmyQuery);
// To get the Financial Year
if (date('m') >= 04) {
    $d = date('Y-m-d', strtotime('+1 years'));
    $financialYear = date('Y') . '-' . date('y', strtotime($d));
} else {
    $d = date('Y-m-d', strtotime('-1 years'));
    $financialYear = date('Y', strtotime($d)) . '-' . date('y');
}

if (isset($bResult[0]['bill_id']) && $bResult[0]['bill_id'] != '') {
    $strparam = strlen($bResult[0]['bill_id'] + 1);
    $zeros = substr("0000", $strparam);
    $invoiceNoUnq = $zeros . $bResult[0]['bill_id'] + 1;
} else {
    $invoiceNoUnq = '0001';
}
$invCodeKey = sprintf("%04d", $invoiceNoUnq);
$buyNo = 'ORDER' . $invCodeKey;
$docxNo = 'DOCX' . $invCodeKey;
$invoiceNo = $cmyResult[0]['company_code'] . $invCodeKey . '/' . $financialYear;

//get All product details
$pQuery = "SELECT * FROM product_details where product_status='active'";
$pResult = $db->rawQuery($pQuery);

//get all agent details
$aQuery = "SELECT * FROM agent_details where agent_status='active'";
$aResult = $db->rawQuery($aQuery);

$productList = '';
foreach ($pResult as $prd) {
    $productList .= '<option value="' . $prd['product_id'] . '"data-description="' . $prd['product_description'] . '" data-id="' . $prd['product_id'] . '" data-hsn="' . $prd['hsn_code'] . '" data-price="' . $prd['product_price'] . '" data-gst="' . $prd['product_tax'] . '" data-qty="' . $prd['qty_available'] . '" data-mini-qty="' . $prd['minimum_qty'] . '">' . $prd['product_name'] . '</option>';
}

$agentDetail = '';
foreach ($aResult as $agent) {
    $agentDetail .= '<option value="' . $agent['agent_id'] . '">' . ucwords($agent['agent_name']) . '</option>';
}
?>
<style>
    #agentName1 option {
        display: none;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-money"></i> Create Invoice</h1>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Add Invoice</li>
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
                <form class="form-horizontal" method='post' name='invoiceGenerate' id='invoiceGenerate' autocomplete="off" action="addInvoiceHelper.php">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="invoiceNo" class="control-label">Invoice Number <span class="mandatory">*</span></label>
                                        <input type="text" class="form-control isRequired" id="invoiceNo" name="invoiceNo" readonly value="<?php echo $invoiceNo; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="invoiceDate" class="control-label">Invoice Date </label>
                                        <input type="text" class="form-control" id="invoiceDate" name="invoiceDate" placeholder="Invoice Date" title="Please choose date" readonly value="<?php echo date('d-M-Y'); ?>" />
                                    </div>
                                </div>
                            </div>
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
                                        <label for="clientName" class="control-label">Client Name </label>
                                        <select class="form-control isRequired" id="clientName" name="clientName">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="clientMobile" class="control-label">Client Mobile</label>
                                        <input type="text" class="form-control" name="clientMobile" id="clientMobile" placeholder="Client Mobile" title="Client mobile number" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="termPayment" class="control-label">Mode/Terms of Payment </label>
                                        <input type="text" class="form-control" name="termPayment" id="termPayment" placeholder="Terms of Payment" title="Terms of Payment" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="supplierRef" class="control-label">Supplier/Delivery Person Name </label>
                                        <input type="text" class="form-control" name="supplierRef" id="supplierRef" placeholder="Reference" title="Supplier Reference" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="phoneNo" class="control-label">Billing Address <span class="mandatory">*</span></label>
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
                        </div>
                        <!-- </div> -->
                    </div>
                    <div class="box-header">
                        <h3 class="box-title ">Product Details</h3>
                    </div>
                    <!-- <div class="box-body"> -->
                    <table style="width:100%;" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed table-responsive">
                        <thead>
                            <tr>
                                <th rowspan="2"><i class="fa fa-check"></i></th>
                                <th rowspan="2">Product Name </th>
                                <th rowspan="2">INR <i class="fa fa-inr"></i> </th>
                                <th rowspan="2">Qty </th>
                                <th rowspan="2">Tax <i class="fa fa-percent"></i> </th>
                                <th rowspan="2">Taxable Price </th>
                                <th colspan="4" style="text-align:center;background-color: aliceblue;">Tax Rate</th>
                                <th colspan="2" style="text-align:center;background-color: antiquewhite;">Tax Rate</th>
                                <th rowspan="2">Discount <i class="fa fa-percent"></i></th>
                                <th rowspan="2" style=" width: 90px; ">Line Total </th>
                                <th rowspan="2" style="width:10%;display: inline-flex;vertical-align:middle;">Action</th>
                            </tr>
                            <tr>
                                <th style="background-color: antiquewhite;">CGST <i class="fa fa-percent"></i></th>
                                <th style="background-color: antiquewhite;">INR <i class="fa fa-inr"></i></th>
                                <th style="background-color: aliceblue;">SGST <i class="fa fa-percent"></i></th>
                                <th style="background-color: aliceblue;">INR <i class="fa fa-inr"></i></th>
                                <th style="background-color: antiquewhite;">IGST <i class="fa fa-percent"></i></th>
                                <th style="background-color: antiquewhite;">INR <i class="fa fa-inr"></i></th>
                            </tr>

                        </thead>
                        <tbody id="productsTable">
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="prdChk[]" id="1" style=" margin-left: -4px; " />
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td><input type="hidden" name="productId[]" id="productId1" />
                                    <select name="prdName[]" id="prdName1" class="form-control prdName isRequired" title="Enter Product" onchange="checkExistProduct(1)">
                                        <option value="">-- Select --</option>
                                        <?php echo $productList; ?>
                                    </select>
                                    <br>
                                    <textarea name="prdDesc[]" id="prdDesc1" class="form-control" title="Enter other product description" placeholder="Enter the product description"></textarea>
                                </td>
                                <td><input style="width:100%;" type="text" class="form-control" name="prdPrice[]" id="prdPrice1" onchange="updateTotalPrice(1);" onkeyup="chenge(this.value,1);updateTotalPrice(1);" /></td>
                                <td><input style="width:100%;" type="text" class="form-control isRequired" name="prdQty[]" id="prdQty1" title="Enter Quantity" onchange="taxCalculation(1);checkProductQty(1);" onkeyup="taxCalculation(1)" onblur="taxCalculation(1);" /></td>
                                <td><input style="width:100%;" type="text" class="form-control isRequired" name="tax[]" id="tax1" title="Enter Tax" onchange="taxCalculation(1)" onkeyup="taxCalculation(1);chenge(this.value,1);" onblur="taxCalculation(1)" /><input type="hidden" id="taxAmtSpan1" /></td>
                                <td><input style="width:100%;" type="text" class="form-control isRequired" name="taxablePrice[]" id="taxablePrice1" title="" /></td>
                                <td style="background-color: antiquewhite;"><input style="width:100%;" type="text" class="form-control" name="cgstTax[]" id="cgstTax1" /></td>
                                <td style="background-color: antiquewhite;"><input style="width:100%;" type="text" class="form-control" name="cgstAmt[]" id="cgstAmt1" /></td>
                                <td style="background-color: aliceblue;"><input style="width:100%;" type="text" class="form-control" name="sgstTax[]" id="sgstTax1" /></td>
                                <td style="background-color: aliceblue;"><input style="width:100%;" type="text" class="form-control" name="sgstAmt[]" id="sgstAmt1" /></td>
                                <td style="background-color: antiquewhite;"><input style="width:100%;" type="text" class="form-control" name="igstTax[]" id="igstTax1" /></td>
                                <td style="background-color: antiquewhite;"><input style="width:100%;" type="text" class="form-control" name="igstAmt[]" id="igstAmt1" /></td>
                                <td><input style="width:100%;" type="text" class="form-control" name="discount[]" id="discount1" title="Enter discount" onchange="taxCalculation(1)" /></td>
                                <td><input style="width:100%;" type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal1" title="Enter Total" readonly /></td>
                                <td align="center" style="vertical-align:middle;width:100%;display: inline-flex;">
                                    <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                </td>
                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><a href="javascript:void(0);" class="btn btn-sm btn-info " onclick="taxCalculation(null,'click');"><b>Switch GST</b></a></td>
                                <!-- <td><a href="javascript:void(0);" class="btn btn-sm btn-info " onclick="taxWithout();"><b>Without GST</b></a></td> -->
                                <td colspan="4"><strong style="float:right;">Taxable Total Price</strong></td>
                                <td><input style="width:100%;" type="text" id="taxableGrandTotal" name="taxableGrandTotal" class="form-control checkNum" /></td>
                                <td colspan="2" style="background-color: antiquewhite;"><input style="width:100%;" type="text" id="cgstTaxTotal" name="cgstTaxTotal" class="form-control checkNum" /></td>
                                <td colspan="2" style="background-color: aliceblue;"><input style="width:100%;" type="text" id="sgstTaxTotal" name="sgstTaxTotal" class="form-control checkNum" /></td>
                                <td colspan="2" style="background-color: antiquewhite;"><input style="width:100%;" type="text" id="igstTaxTotal" name="igstTaxTotal" class="form-control checkNum" /></td>
                                <td colspan="2"><input style="width:100%;" type="text" id="grandTotal" name="grandTotal" class="form-control isRequired checkNum" placeholder="Grand Total" title="Grand Total for this order" /></td>
                            </tr>
                        </tfoot>
                    </table>


                    <!-- payment options -->
                    <div class="box-header">
                        <h3 class="box-title ">Payment Options</h3>
                    </div>
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed"">
                        <thead>
                            <tr>
                                <th>Pay Option </th>
                                <th>Paid On</th>
                                <th>Pay Details </th>
                                <th>Amount </th>
                                <th>Action</th>
                            </tr>

                        </thead>
                        <tbody id=" payTable">
                        <tr>
                            <td>
                                <select name="payOption[]" id="payOption1" class="form-control " title="Choose option" onchange="showHideEmi(this,1)">
                                    <option value="">-- Select --</option>
                                    <option value="cash">Cash</option>
                                    <option value="cheque">Cheque</option>
                                    <option value="online">Online</option>
                                    <!-- <option value="emi">EMI</option> -->
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control isRequired" id="paidOn1" name="paidOn[]" placeholder="Paid Date" title="Please choose date" readonly />
                            </td>
                            <td><textarea name="payDetails[]" id="payDetails1" class="form-control" placeholder="Enter Details"></textarea></td>
                            <td><input type="text" name="payAmt[]" id="payAmt1" class="form-control" placeholder="Amount" onkeyup="updatepayTotal()" /></td>
                            <td align="center" style="vertical-align:middle;">
                                <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insPayRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributePayRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                            </td>
                        </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong style="float:right;">Paid Amount</strong></td>

                                <td><input type="text" id="paidGrandTotal" name="paidGrandTotal" class="form-control isRequired checkNum" placeholder="Grand Total" title="Grand Total for this order" readonly /></td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.box-body -->
                    <div class="box-footer">
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
    var tableRowId = 2;
    var payTableRowId = 2;
    $(document).ready(function() {
        $("#prdName1").select2({
            placeholder: "Enter product name",
            minimumInputLength: 0,
            width: '100%',
            allowClear: true,
            ajax: {
                placeholder: "Type product name to search",
                url: "/quotations/get-product-list.php",
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
        $("#clientName").select2({
            placeholder: "Enter client name",
            minimumInputLength: 0,
            width: '100%',
            allowClear: true,
            ajax: {
                placeholder: "Type client name to search",
                url: "get-client-list.php",
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
        $("#clientName").on("change", function(e) {
            clientName = $('#clientName').select2('data');
            if ($(this).val() != "" && $(this).val() != null) {
                var name = clientName[0]['text'];
                if (clientName[0]['billing_address'] != '') {
                    $("#address").val(clientName[0]['billing_address']);
                }
                if (clientName[0]['shipping_address'] != '') {
                    $("#shipAddress").val(clientName[0]['shipping_address']);
                }
                if (clientName[0]['mobile'] != '') {
                    $("#clientMobile").val(clientName[0]['mobile']);
                }
            }
        });
        $('#invoiceDate,#invoiceDueDate,#paidOn1,#buyerDate,#deliveryNoteDate').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            // minDate: "Today",
            yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
        }).click(function() {
            $('.ui-datepicker-calendar').show();
        });
    });

    function validateNow() {
        flag = deforayValidator.init({
            formId: 'invoiceGenerate'
        });
        if (flag) {
            var gt = Math.round($("#grandTotal").val());
            var pt = Math.round($("#paidGrandTotal").val());
            if (pt > gt) {
                alert("Payment total not greater than purchase total!");
                return false;
            }
            $.blockUI();
            document.getElementById('invoiceGenerate').submit();
        }
    }

    function insRow() {
        rl = document.getElementById("productsTable").rows.length;
        var a = document.getElementById("productsTable").insertRow(rl);
        a.setAttribute("style", "display:none");
        var b = a.insertCell(0);
        var c = a.insertCell(1);
        var d = a.insertCell(2);
        var e = a.insertCell(3);
        var f = a.insertCell(4);
        var g = a.insertCell(5);
        var h = a.insertCell(6);
        var i = a.insertCell(7);
        var j = a.insertCell(8);
        var k = a.insertCell(9);
        var l = a.insertCell(10);
        var m = a.insertCell(11);
        var n = a.insertCell(12);
        var o = a.insertCell(13);
        var p = a.insertCell(14);
        p.setAttribute("align", "center");
        p.setAttribute("style", "vertical-align:middle");
        j.setAttribute("style", "background-color: antiquewhite;");
        k.setAttribute("style", "background-color: antiquewhite;");
        n.setAttribute("style", "background-color: antiquewhite;");
        o.setAttribute("style", "background-color: antiquewhite;");
        l.setAttribute("style", "background-color: aliceblue;");
        m.setAttribute("style", "background-color: aliceblue;");

        b.innerHTML = '<div class="form-group"><div class="checkbox"><label><input type="checkbox" name="prdChk[]" id="' + tableRowId + '" style=" margin-left: -4px; "/></label></div></div>';
        c.innerHTML = '<input type="hidden" name="productId[]" id="productId' + tableRowId + '" /><select style="width:100%;" name="prdName[]" id="prdName' + tableRowId + '" class="form-control prdName isRequired" title="Enter Product" onchange="checkExistProduct(' + tableRowId + ')"><option value="">-- Select --</option><?php echo $productList; ?></select><br><textarea name="prdDesc[]" id="prdDesc' + tableRowId + '" class="form-control" title="Enter other product description" placeholder="Enter the product description"></textarea>';
        d.innerHTML = '<input style="width:100%;" type="text" class="form-control" name="prdPrice[]" id="prdPrice' + tableRowId + '"  onchange=";updateTotalPrice(' + tableRowId + ');" onkeyup="chenge(this.value,' + tableRowId + ');updateTotalPrice(' + tableRowId + ');"/>';
        e.innerHTML = '<input style="width:100%;" type="text" class="form-control isRequired" name="prdQty[]" id="prdQty' + tableRowId + '" title="Enter Quantity" onchange="taxCalculation(' + tableRowId + ');" onkeyup="taxCalculation(' + tableRowId + ')" onchange="taxCalculation(' + tableRowId + ');checkProductQty(' + tableRowId + ');"/>';
        f.innerHTML = '<input style="width:100%;" type="text" class="form-control isRequired" name="tax[]" id="tax' + tableRowId + '" title="Enter Quantity" onchange="taxCalculation(' + tableRowId + ')" onkeyup="taxCalculation(' + tableRowId + ');chenge(this.value,' + tableRowId + ');" onblur="taxCalculation(' + tableRowId + ')"/><input type="hidden" id="taxAmtSpan' + tableRowId + '"/>';
        g.innerHTML = '<input style="width:100%;" type="text" class="form-control isRequired" name="taxablePrice[]" id="taxablePrice' + tableRowId + '" title="" />';
        h.innerHTML = '<input style="width:100%;" type="text" class="form-control" name="cgstTax[]" id="cgstTax' + tableRowId + '" />';
        i.innerHTML = '<input style="width:100%;" type="text" class="form-control" name="cgstAmt[]" id="cgstAmt' + tableRowId + '" />';
        j.innerHTML = '<input style="width:100%;" type="text" class="form-control" name="sgstTax[]" id="sgstTax' + tableRowId + '" />';
        k.innerHTML = '<input style="width:100%;" type="text" class="form-control" name="sgstAmt[]" id="sgstAmt' + tableRowId + '" />';
        l.innerHTML = '<input style="width:100%;" type="text" class="form-control" name="igstTax[]" id="igstTax' + tableRowId + '" />';
        m.innerHTML = '<input style="width:100%;" type="text" class="form-control" name="igstAmt[]" id="igstAmt' + tableRowId + '" />';
        n.innerHTML = '<input style="width:100%;" type="text" class="form-control" name="discount[]" id="discount' + tableRowId + '" title="Enter discount" onchange="taxCalculation(' + tableRowId + ')"/>';
        o.innerHTML = '<input style="width:100%;" type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal' + tableRowId + '" title="Enter Total" readonly/>';
        p.innerHTML = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>';
        $(a).fadeIn(800);
        $("#prdName" + tableRowId).select2({
            placeholder: "Enter product name",
            minimumInputLength: 0,
            width: '100%',
            allowClear: true,
            ajax: {
                placeholder: "Type product name to search",
                url: "/quotations/get-product-list.php",
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
        tableRowId++;
    }

    function removeAttributeRow(el) {
        $(el).fadeOut("slow", function() {
            el.parentNode.removeChild(el);
            rl = document.getElementById("productsTable").rows.length;
            if (rl == 0) {
                insRow();
            }
        });
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
        f.setAttribute("align", "center");
        f.setAttribute("style", "vertical-align:middle");
        f.setAttribute("style", "display: inline-flex;");

        b.innerHTML = '<select name="payOption[]" id="payOption' + payTableRowId + '" class="form-control " title="Choose option"  onchange="showHideEmi(this,' + payTableRowId + ')">\
                                            <option value="">-- Select --</option>\
                                            <option value="cash">Cash</option>\
                                            <option value="cheque">Cheque</option>\
                                            <option value="online">Online</option>\
                                        </select>';
        c.innerHTML = '<input type="text" class="form-control isRequired" id="paidOn' + payTableRowId + '" name="paidOn[]" placeholder="Paid Date" title="Please choose date" readonly/>';
        d.innerHTML = '<textarea name="payDetails[]" id="payDetails' + payTableRowId + '" class="form-control" placeholder="Enter Details"></textarea>';
        e.innerHTML = '<input type="text" name="payAmt[]" id="payAmt' + payTableRowId + '" class="form-control" placeholder="Amount" onkeyup="updatepayTotal()"/>';
        f.innerHTML = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insPayRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributePayRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>';
        // $("#agentName" + payTableRowId + " option").hide();
        $('#paidOn' + payTableRowId).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            minDate: "Today",
            yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
        }).click(function() {
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
        });
    }

    function showHideEmi(obj, payRowId) {
        if (obj.value == 'emi') {
            $("#agentName" + payRowId + " option").show();
        } else {
            $("#agentName" + payRowId).val('');
            $("#agentName" + payRowId + " option").hide();
        }
    }

    function updatepayTotal() {
        var paidGrandTotal = 0;
        var paidPrice = document.getElementsByName("payAmt[]");

        for (i = 0; i < paidPrice.length; i++) {
            if (paidPrice[i].value != '') {
                paidGrandTotal += parseFloat(paidPrice[i].value);
            }
        }
        var roundGrandTotal = Math.round(paidGrandTotal);
        document.getElementById('paidGrandTotal').value = roundGrandTotal.toFixed(2);
    }

    function updatePrice(rowId) {
        var prdSelection = $("#prdName" + rowId).val();
        // console.log($("#prdName" + rowId).find(':selected'));
        if (prdSelection != '') {
            var hsn = $("#prdName" + rowId).find(':selected').attr('data-hsn');
            var pId = $("#prdName" + rowId).find(':selected').attr('data-id');
            var price = $("#prdName" + rowId).find(':selected').attr('data-price');
            var desc = $("#prdName" + rowId).find(':selected').attr('data-description');
            let tax = $("#prdName" + rowId).find(':selected').attr('data-gst');
            let basePrice = price / (1 + tax / 100);

            $("#tax" + rowId).val(tax);
            $("#taxablePrice" + rowId).val($("#prdName" + rowId).find(':selected').attr('data-price'));
            $("#prdQty" + rowId).val(1);
            $("#productId" + rowId).val(pId);
            $("#hsnCode" + rowId).html(hsn);
            $("#prdPrice" + rowId).val(basePrice);
            $("#lineTotal" + rowId).val(basePrice);
            $("#prdDesc" + rowId).html(desc);

            taxCalculation(rowId);
        }
    }

    function taxWithout() {
        var chk = document.getElementsByName("prdChk[]");
        for (i = 0; i < chk.length; i++) {
            if ($("#" + chk[i].id).prop("checked") == true) {
                rowId = chk[i].id;
                $('#cgstTax' + rowId).val('0');
                $('#cgstAmt' + rowId).val('0');
                $('#sgstTax' + rowId).val('0');
                $('#sgstAmt' + rowId).val('0');
                $('#igstTax' + rowId).val('0');
                $('#igstAmt' + rowId).val('0');
                $('#tax' + rowId).val('0');
                taxCalculation(rowId);
                updateTotalPrice(rowId);
            } else {
                rowId = chk[i].id;
                $('#cgstTax' + rowId).val();
                $('#cgstAmt' + rowId).val();
                $('#sgstTax' + rowId).val();
                $('#sgstAmt' + rowId).val();
                $('#igstTax' + rowId).val();
                $('#igstAmt' + rowId).val();
                $('#tax' + rowId).val();
                taxCalculation(rowId);
                updateTotalPrice(rowId);
            }
        }
    }

    function taxCalculation(rowId = null, from = null) {
        if (rowId == null && from == 'click') {
            var chk = document.getElementsByName("prdChk[]");
            for (i = 0; i < chk.length; i++) {
                if ($("#" + chk[i].id).prop("checked") == true) {
                    rowId = chk[i].id;
                    // var price = $("#prdName" + rowId).find(':selected').attr('data-price');
                    var price = $("#prdPrice" + rowId).val();
                    var tax = $("#tax" + rowId).val();
                    if ($('#sqftCheck' + rowId).prop("checked") == true) {
                        $("#taxablePrice" + rowId).val(price * $("#sqft" + rowId).val());
                    } else {
                        $("#taxablePrice" + rowId).val(price * $("#prdQty" + rowId).val());
                    }
                    var taxDivision = tax / 2;
                    var taxCalc = (parseInt(price) * taxDivision / 100) * $("#prdQty" + rowId).val();
                    if ($("#cgstTax" + rowId).val() == '' && from == 'click') {
                        $("#cgstTax" + rowId).val(taxDivision);
                        $("#cgstAmt" + rowId).val(taxCalc);
                        $("#sgstTax" + rowId).val(taxDivision);
                        $("#sgstAmt" + rowId).val(taxCalc);
                        $("#igstTax" + rowId).val('');
                        $("#igstAmt" + rowId).val('');
                    } else {
                        if ($('#sqftCheck' + rowId).prop("checked") == true) {
                            $("#taxablePrice" + rowId).val(price * $("#sqft" + rowId).val());
                        } else {
                            $("#taxablePrice" + rowId).val(price * $("#prdQty" + rowId).val());
                        }
                        var taxDivision = tax;
                        var taxCalc = (parseInt(price) * taxDivision / 100) * $("#prdQty" + rowId).val();
                        $("#cgstTax" + rowId).val('');
                        $("#cgstAmt" + rowId).val('');
                        $("#sgstAmt" + rowId).val('');
                        $("#sgstTax" + rowId).val('');
                        $("#igstTax" + rowId).val(taxDivision);
                        $("#igstAmt" + rowId).val(taxCalc);
                    }
                    //updateTotalPrice(rowId);
                }
            }
            taxCalculation(null, null);
            $('input:checkbox').removeAttr('checked');
        } else {


            // var price = $("#prdName" + rowId).find(':selected').attr('data-price');
            var price = $("#prdPrice" + rowId).val();
            var tax = $("#tax" + rowId).val();
            if (tax != '') {
                if ($('#sqftCheck' + rowId).prop("checked") == true) {
                    $("#taxablePrice" + rowId).val(price * $("#sqft" + rowId).val());
                } else {
                    $("#taxablePrice" + rowId).val(price * $("#prdQty" + rowId).val());
                }
                var taxDivision = tax / 2;
                if ($('#sqftCheck' + rowId).prop("checked") == true) {
                    var taxCalc = (parseInt(price) * taxDivision / 100) * $("#sqft" + rowId).val();
                } else {
                    var taxCalc = (parseInt(price) * taxDivision / 100) * $("#prdQty" + rowId).val();
                }
                if ($("#igstTax" + rowId).val() == '') {
                    $("#cgstTax" + rowId).val(taxDivision);
                    $("#cgstAmt" + rowId).val(taxCalc);
                    $("#sgstTax" + rowId).val(taxDivision);
                    $("#sgstAmt" + rowId).val(taxCalc);
                } else {
                    $("#igstTax" + rowId).val(taxDivision);
                    $("#igstAmt" + rowId).val(taxCalc);
                }
                updateTotalPrice(rowId);
            }
        }
    }

    function checkProductQty(rowId) {
        //check product count
        var prdQty = $("#prdName" + rowId).find(':selected').attr('data-qty');
        var prdMiniQty = $("#prdName" + rowId).find(':selected').attr('data-mini-qty');
        var enteredQty = $("#prdQty" + rowId).val();
        var remainQty = prdQty - enteredQty;
        if (parseInt(enteredQty) > parseInt(prdQty)) {
            alert("Available qty only " + prdQty);
            $("#prdQty" + rowId).val('');
            return false;
        } else if (remainQty < prdMiniQty) {
            // alert("Product reached minimum qty " + prdMiniQty);
        }
    }

    function updateTotalPrice(rowId) {
        var grandTotal = 0;
        var taxableGt = 0;
        var igstTotal = 0;
        var cgstTotal = 0;
        var unitPrice = document.getElementsByName("prdPrice[]");
        var qty = document.getElementsByName("prdQty[]");
        var sqft = document.getElementsByName("sqft[]");
        var unitTotal = document.getElementsByName("lineTotal[]");
        var discount = document.getElementsByName("discount[]");
        var tax = document.getElementsByName("tax[]");
        var cgstTax = document.getElementsByName("cgstTax[]");
        var sgstTax = document.getElementsByName("sgstTax[]");
        var igstTax = document.getElementsByName("igstTax[]");
        var cgstAmt = document.getElementsByName("cgstAmt[]");
        var sgstAmt = document.getElementsByName("sgstAmt[]");
        var igstAmt = document.getElementsByName("igstAmt[]");
        var taxablePrice = document.getElementsByName("taxablePrice[]");

        for (i = 0; i < unitPrice.length; i++) {
            if (unitPrice[i].value != "" && (qty[i].value != "" || sqft[i].value != "")) {
                // Validate quantity or sqft isn't zero
                if ($('#sqftCheck' + (i + 1)).prop("checked") == true) {
                    if (sqft[i].value == 0) {
                        alert("Sorry! You can not add ZERO square feet.")
                        sqft[i].value = 1;
                    }
                    var quantity = parseFloat(sqft[i].value);
                } else {
                    if (qty[i].value == 0) {
                        alert("Sorry! You can not add ZERO quantity.")
                        qty[i].value = 1;
                    }
                    var quantity = parseFloat(qty[i].value);
                }

                // Get base price and calculate base amount
                var basePrice = parseFloat(unitPrice[i].value);
                var baseAmount = basePrice * quantity;

                // Apply discount if provided (on base amount before tax)
                var discountAmount = 0;
                if (discount[i].value != "") {
                    var discountRate = parseFloat(discount[i].value);
                    discountAmount = (baseAmount * discountRate) / 100;
                }

                // Calculate taxable amount after discount
                var taxableAmount = baseAmount - discountAmount;
                taxablePrice[i].value = taxableAmount.toFixed(2);

                // Calculate tax amounts based on taxable amount
                var taxRate = parseFloat(tax[i].value || 0);
                var taxAmount = 0;

                // Check if CGST/SGST or IGST is applicable
                if (cgstTax[i].value != '' && cgstTax[i].value != '0') {
                    // Split GST scenario (CGST + SGST)
                    var halfTaxRate = taxRate / 2;
                    var halfTaxAmount = (taxableAmount * halfTaxRate) / 100;

                    cgstTax[i].value = halfTaxRate.toFixed(2);
                    sgstTax[i].value = halfTaxRate.toFixed(2);
                    cgstAmt[i].value = halfTaxAmount.toFixed(2);
                    sgstAmt[i].value = halfTaxAmount.toFixed(2);

                    // Clear IGST fields
                    igstTax[i].value = '';
                    igstAmt[i].value = '';

                    taxAmount = halfTaxAmount * 2;
                } else if (igstTax[i].value != '' && igstTax[i].value != '0') {
                    // IGST scenario
                    var igstAmount = (taxableAmount * taxRate) / 100;

                    igstTax[i].value = taxRate.toFixed(2);
                    igstAmt[i].value = igstAmount.toFixed(2);

                    // Clear CGST/SGST fields
                    cgstTax[i].value = '';
                    sgstTax[i].value = '';
                    cgstAmt[i].value = '';
                    sgstAmt[i].value = '';

                    taxAmount = igstAmount;
                }

                // Calculate line total (taxable amount + tax amount)
                var lineTotal = taxableAmount + taxAmount;
                unitTotal[i].value = lineTotal.toFixed(2);

                // Add to totals
                grandTotal += lineTotal;
                taxableGt += taxableAmount;
                cgstTotal += parseFloat(cgstAmt[i].value || 0);
                igstTotal += parseFloat(igstAmt[i].value || 0);
            }
        }

        // Update totals
        var roundGrandTotal = Math.round(grandTotal);
        document.getElementById('grandTotal').value = roundGrandTotal.toFixed(2);
        document.getElementById('taxableGrandTotal').value = Math.round(taxableGt).toFixed(2);
        document.getElementById('cgstTaxTotal').value = cgstTotal.toFixed(2);
        document.getElementById('sgstTaxTotal').value = cgstTotal.toFixed(2);
        document.getElementById('igstTaxTotal').value = igstTotal.toFixed(2);
    }

    function checkExistProduct(rowId) {
        var itemId = document.getElementById("prdName" + rowId).value;
        var itemCount = document.getElementsByName("prdName[]");
        var itemLength = itemCount.length - 1;
        var k = 0;
        for (i = 0; i <= itemLength; i++) {
            if (itemId == itemCount[i].value) {
                if (itemCount[i].value != '' && itemId != '') {
                    k++;
                }
            }
        }
        if (k > 1) {
            alert("Product name already added..!!");
            $("#prdName" + rowId).val('');
            $("#prdName" + rowId).trigger('change');
        } else {
            updatePrice(rowId);
        }
    }

    function chenge(vl, rowId) {
        $('#taxAmtSpan' + rowId).val(vl)
    }
</script>
<?php
include('../footer.php');
?>