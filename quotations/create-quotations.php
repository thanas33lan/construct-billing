<?php
ob_start();
include('../header.php');
//quotations number generation
$bQuery = "SELECT q_id FROM quotations order by q_id DESC limit 1";
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

if (isset($bResult[0]['q_id']) && $bResult[0]['q_id'] != '') {
    $strparam = strlen($bResult[0]['q_id'] + 1);
    $zeros = substr("0000", $strparam);
    $qtnNoUnq = $zeros . $bResult[0]['q_id'] + 1;
} else {
    $qtnNoUnq = '0001';
}
$qtnKey = sprintf("%04d", $qtnNoUnq);
$buyNo = 'ORDER' . $qtnKey;
$docxNo = 'DOCX' . $qtnKey;
$qtnNo = $cmyResult[0]['company_code'] . '/QTN' . $qtnKey . '/' . $financialYear;

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
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-money"></i> Create Quotations</h1>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Add Quotations</li>
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
                <form class="form-horizontal" method='post' name='quotationsGenerate' id='quotationsGenerate' autocomplete="off" action="addQuotationsHelper.php">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="quotationsNo" class="control-label">Quotations Code <span class="mandatory">*</span></label>
                                        <input type="text" class="form-control isRequired" id="quotationsNo" name="quotationsNo" readonly value="<?php echo $qtnNo; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="enquiryDate" class="control-label">Enquiry Date</label>
                                        <input type="text" class="form-control" id="enquiryDate" name="enquiryDate" placeholder="Enquiry date" title="Please choose enquiry date" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="quotationsDate" class="control-label">Quotations Date </label>
                                        <input type="text" class="form-control" id="quotationsDate" name="quotationsDate" placeholder="Quotations date" title="Please choose date" value="<?php echo date('d-M-Y'); ?>" readonly />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="clientName" class="control-label">Client Name </label>
                                        <select class="form-control isRequired" id="clientName" name="clientName" title="Please choose client name">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="clientAddress" class="control-label">Address <span class="mandatory">*</span></label>
                                        <textarea type="text" class="form-control isRequired" id="clientAddress" name="clientAddress" placeholder="Address" title="Please enter address"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-header">
                        <h3 class="box-title ">Product Details</h3>
                    </div>

                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed table-responsive" style="width:100%;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:20%;">Product Name </th>
                                <th rowspan="2" style="width:15%;">Product Description </th>
                                <th rowspan="2" style="width:10%;">INR <i class="fa fa-inr"></i> </th>
                                <th rowspan="2" style="width:10%;">Qty </th>
                                <th rowspan="2" style="width:10%;">Discount <i class="fa fa-percent"></i></th>
                                <th rowspan="2" style="width:15%;">Line Total </th>
                                <th rowspan="2" style="width:10%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="productsTable">
                            <tr>
                                <td>
                                    <select name="prdName[]" id="prdName1" class="form-control prdName isRequired" title="Select Product" onchange="checkExistProduct(1)">
                                        <option value="">-- Select --</option>
                                        <?php echo $productList; ?>
                                        <option value="other">Others</option>
                                    </select>
                                </td>
                                <td><textarea name="prdDesc[]" id="prdDesc1" class="form-control" title="Enter other product description"></textarea></td>
                                <td><input type="number" class="form-control" name="prdPrice[]" id="prdPrice1" onchange="updateTotalPrice(1)" /></td>
                                <td><input type="text" class="form-control isRequired" name="prdQty[]" id="prdQty1" title="Enter Quantity" onchange="updateTotalPrice(1)" /></td>
                                <td><input type="text" class="form-control" name="discount[]" id="discount1" title="Enter discount" onchange="updateTotalPrice(1)" /></td>
                                <td><input type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal1" title="Enter Total" readonly /></td>
                                <td align="center" style="vertical-align:middle;display: inline-flex;">
                                    <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                </td>
                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><strong style="float:right;">Additonal Charges</strong></td>
                                <td colspan="2"><input type="text" id="additionalChargesReason" name="additionalChargesReason" class="form-control" placeholder="Additional charges reason" title="Please enter the additional charges reason" /></td>
                                <td><input type="text" id="additionalCharges" name="additionalCharges" class="form-control checkNum" placeholder="Additional charges" title="Please enter the additional charges" /></td>
                            </tr>
                            <tr>
                                <td colspan="5"><strong style="float:right;">Taxable Total Price</strong></td>
                                <td><input type="text" id="grandTotal" name="grandTotal" class="form-control checkNum" placeholder="Total" readonly title="Grand Total for this quotations" /></td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                        <a href="quotations-list.php" class="btn btn-default"> Cancel</a>
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
        /* $("#prdName1").select2({
            placeholder: "Enter product name",
            width: '200px',
            allowClear: true,
            maximumSelectionLength: 2
        }); */

        $("#prdName1").select2({
            placeholder: "Enter product name",
            minimumInputLength: 0,
            width: '100%',
            allowClear: true,
            ajax: {
                placeholder: "Type product name to search",
                url: "get-product-list.php",
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
                    $("#clientAddress").val(clientName[0]['billing_address']);
                }
                if (clientName[0]['shipping_address'] != '') {
                    $("#shipAddress").val(clientName[0]['shipping_address']);
                }
                if (clientName[0]['mobile'] != '') {
                    $("#clientMobile").val(clientName[0]['mobile']);
                }
            }
        });
        $('#quotationsDate').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            minDate: "Today",
            yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
        }).click(function() {
            $('.ui-datepicker-calendar').show();
        });
        $('#enquiryDate').datepicker({
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
            formId: 'quotationsGenerate'
        });

        if (flag) {
            var gt = Math.round($("#grandTotal").val());
            $.blockUI();
            document.getElementById('quotationsGenerate').submit();
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
        h.setAttribute("align", "center");
        h.setAttribute("style", "vertical-align:middle;");
        h.setAttribute("style", "display: inline-flex;");

        b.innerHTML = '<select name="prdName[]" id="prdName' + tableRowId + '" class="form-control prdName isRequired" title="Enter Product" onchange="checkExistProduct(' + tableRowId + ')"><option value="">-- Select --</option><?php echo $productList; ?><option value="other">Others</option></select>';
        c.innerHTML = '<textarea name="prdDesc[]" id="prdDesc' + tableRowId + '" class="form-control" title="Enter other product description"></textarea>';
        d.innerHTML = '<input type="number" class="form-control" name="prdPrice[]" id="prdPrice' + tableRowId + '" onchange="updateTotalPrice(' + tableRowId + ')"/>';
        e.innerHTML = '<input type="text" class="form-control isRequired" name="prdQty[]" id="prdQty' + tableRowId + '" title="Enter Quantity"  onchange="updateTotalPrice(' + tableRowId + ')"/>';
        f.innerHTML = '<input type="text" class="form-control" name="discount[]" id="discount' + tableRowId + '" title="Enter discount"  onchange="updateTotalPrice(' + tableRowId + ')"/>';
        g.innerHTML = '<input type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal' + tableRowId + '" title="Enter Total" readonly/>';
        h.innerHTML = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>';
        $(a).fadeIn(800);
        /* $("#prdName" + tableRowId).select2({
            placeholder: "Select product name",
            width: '200px',
            allowClear: true,
        }); */

        $("#prdName" + tableRowId).select2({
            placeholder: "Enter product name",
            minimumInputLength: 0,
            width: '100%',
            allowClear: true,
            ajax: {
                placeholder: "Type product name to search",
                url: "get-product-list.php",
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

    function updatePrice(rowId) {
        var prdSelection = $("#prdName" + rowId).val();
        // console.log($("#prdName" + rowId).find(':selected').attr());
        if (prdSelection != '') {
            var hsn = $("#prdName" + rowId).find(':selected').attr('data-hsn');
            var pId = $("#prdName" + rowId).find(':selected').attr('data-id');
            var price = $("#prdName" + rowId).find(':selected').attr('data-price');
            var desc = $("#prdName" + rowId).find(':selected').attr('data-description');
            $("#prdQty" + rowId).val(1);
            $("#prdPrice" + rowId).val(price);
            $("#priceSpan" + rowId).html(price);
            $("#prdDesc" + rowId).html(desc);
        }
        updateTotalPrice(rowId);
    }

    function updateTotalPrice(rowId) {
        var grandTotal = 0;
        var unitPrice = document.getElementsByName("prdPrice[]");
        var qty = document.getElementsByName("prdQty[]");
        var discount = document.getElementsByName("discount[]");
        var unitTotal = document.getElementsByName("lineTotal[]");
        for (i = 0; i < unitPrice.length; i++) {
            if (unitPrice[i].value != "" && qty[i].value != "") {
                if (qty[i].value == 0) {
                    alert("Sorry! You can not add ZERO quantity.")
                    qty[i].value = 1;
                }
                unitQty = parseFloat(unitPrice[i].value) * parseFloat(qty[i].value);
                if (discount[i].value != "") {
                    unitTotal[i].value = (unitQty - ((unitQty / 100) * discount[i].value));
                } else {
                    unitTotal[i].value = unitQty;
                }
                grandTotal = grandTotal + parseFloat(unitTotal[i].value);
            } else {
                grandTotal = grandTotal + 0;
            }
        }
        var roundGrandTotal = Math.round(grandTotal);
        document.getElementById('grandTotal').value = roundGrandTotal.toFixed(2);
    }

    function checkExistProduct(rowId) {
        var itemId = document.getElementById("prdName" + rowId).value;
        var itemCount = document.getElementsByName("prdName[]");
        var itemLength = itemCount.length - 1;
        var k = 0;
        for (i = 0; i <= itemLength; i++) {
            if (itemId == itemCount[i].value) {
                k++;
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
</script>
<?php
include('../footer.php');
?>