<?php
ob_start();
include('../header.php');

//get All product details
$purQuery = "SELECT purchase_id FROM purchase_details ORDER BY purchase_id DESC LIMIT 1";
$purResult = $db->rawQuery($purQuery);
if (isset($purResult[0]['purchase_id']) && $purResult[0]['purchase_id'] != '') {
    $strparam = strlen($purResult[0]['purchase_id'] + 1);
    $zeros = substr("0000", $strparam);
    $purNoUnq = $zeros . $purResult[0]['purchase_id'] + 1;
} else {
    $purNoUnq = '0001';
}
$purCodeKey = sprintf("%04d", $purNoUnq);
$purNo = 'PUR' . $purCodeKey;
//get All product details
$pQuery = "SELECT * FROM product_details where product_status='active'";
$pResult = $db->rawQuery($pQuery);

//get all agent details
$aQuery = "SELECT * FROM supplier_details where supplier_status='active'";
$aResult = $db->rawQuery($aQuery);

$productList = '';
foreach ($pResult as $prd) {
    $productList .= '<option value="' . $prd['product_id'] . '" data-hsn="' . $prd['hsn_code'] . '" data-price="' . $prd['product_price'] . '" data-gst="' . $prd['product_tax'] . '" data-qty="' . $prd['qty_available'] . '" data-mini-qty="' . $prd['minimum_qty'] . '">' . $prd['product_name'] . '</option>';
}

$suplierDetail = '';
foreach ($aResult as $supplier) {
    $suplierDetail .= '<option value="' . $supplier['supplier_id'] . '">' . ucwords($supplier['supplier_name']) . '</option>';
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
        <h1> Add Purchase</h1>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Add Purchase</li>
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
                <form class="form-horizontal" method='post' name='addPurchase' id='addPurchase' autocomplete="off" action="addPurchaseHelper.php">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="supplierName" class="control-label">Supplier Name <span class="mandatory">*</span></label>
                                        <select class="form-control isRequired" id="supplierName" name="supplierName">
                                            <option value="">-- Select --</option>
                                            <?php echo $suplierDetail; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="purchaseDate" class="control-label">Purchase Date <span class="mandatory">*</span> </label>
                                        <input type="text" class="form-control isRequired" id="purchaseDate" name="purchaseDate" placeholder="Purchase Date" title="Please choose date" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="invoiceNo" class="control-label">Invoice Number <span class="mandatory">*</span> </label>
                                        <input value="<?php echo $purNo; ?>" type="text" class="form-control isRequired" id="invoiceNo" name="invoiceNo" placeholder="Invoice Number" title="Please enter invoice number" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <label for="purchaseNo" class="control-label">Purchase Number <span class="mandatory">*</span> </label>
                                        <input value="<?php echo $purNo; ?>" type="text" class="form-control isRequired" id="purchaseNo" name="purchaseNo" placeholder="Purchase Number" title="Please enter purchase number" />
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
                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered table-condensed" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Product Name </th>
                                <th>Qty </th>
                                <th>INR <i class="fa fa-inr"></i> </th>
                                <th>Line Total </th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="productsTable">
                            <tr>
                                <td>
                                    <select name="prdName[]" id="prdName1" class="form-control prdName isRequired" title="Enter Product" onchange="checkExistProduct(1)">
                                        <option value="">-- Select --</option>
                                        <?php echo $productList; ?>
                                    </select>
                                </td>
                                <td><input type="text" class="form-control isRequired" name="prdQty[]" id="prdQty1" title="Enter Quantity" onchange="updateTotalPrice(1);" onkeyup="updateTotalPrice(1)" onblur="updateTotalPrice(1);" /></td>
                                <td><input type="text" class="form-control isRequired" name="prdPrice[]" id="prdPrice1" onchange="updateTotalPrice(1);" onkeyup="updateTotalPrice(1)" onblur="updateTotalPrice(1);" /></td>
                                <td><input type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal1" title="Enter Total" readonly /></td>
                                <td align="center" style="vertical-align:middle;">
                                    <a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>
                                </td>
                            </tr>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3"><b class="pull-right">Grand Total</b></td>

                                <td><input type="text" id="grandTotal" name="grandTotal" class="form-control isRequired checkNum" placeholder="Grand Total" title="Grand Total for this order" readonly /></td>
                            </tr>
                        </tfoot>
                    </table>


                    <!-- /.box-body -->
                    <div class="box-footer">
                        <a class="btn btn-primary" href="javascript:void(0);" onclick="validateNow();return false;">Submit</a>
                        <a href="purchaseList.php" class="btn btn-default"> Cancel</a>
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
            width: '250px',
            allowClear: true,
            maximumSelectionLength: 2
        });

        $('#purchaseDate').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-M-yy',
            maxDate: "Today",
            yearRange: <?php echo (date('Y') - 100); ?> + ":" + "<?php echo (date('Y')) ?>"
        }).click(function() {
            $('.ui-datepicker-calendar').show();
        });
    });

    function validateNow() {
        flag = deforayValidator.init({
            formId: 'addPurchase'
        });
        if (flag) {
            $.blockUI();
            document.getElementById('addPurchase').submit();
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

        f.setAttribute("align", "center");
        f.setAttribute("style", "vertical-align:middle");

        b.innerHTML = '<select name="prdName[]" id="prdName' + tableRowId + '" class="form-control prdName isRequired" title="Enter Product" onchange="checkExistProduct(' + tableRowId + ')"><option value="">-- Select --</option><?php echo $productList; ?></select>';
        c.innerHTML = '<input type="text" class="form-control isRequired" name="prdQty[]" id="prdQty' + tableRowId + '" title="Enter Quantity" onchange="updateTotalPrice(' + tableRowId + ');" onkeyup="updateTotalPrice(' + tableRowId + ')" onblur="updateTotalPrice(' + tableRowId + ');"/>';
        d.innerHTML = '<input type="text" class="form-control isRequired" name="prdPrice[]" id="prdPrice' + tableRowId + '" onchange="updateTotalPrice(' + tableRowId + ');" onkeyup="updateTotalPrice(' + tableRowId + ')" onblur="updateTotalPrice(' + tableRowId + ');" />';
        e.innerHTML = '<input type="text" class="form-control isRequired" name="lineTotal[]" id="lineTotal' + tableRowId + '" title="Enter Total" readonly/>';
        f.innerHTML = '<a class="btn btn-xs btn-primary" href="javascript:void(0);" onclick="insRow();"><i class="fa fa-plus"></i></a>&nbsp;<a class="btn btn-xs btn-default" href="javascript:void(0);" onclick="removeAttributeRow(this.parentNode.parentNode);"><i class="fa fa-minus"></i></a>';
        $(a).fadeIn(800);
        $("#prdName" + tableRowId).select2({
            placeholder: "Enter product name",
            width: '250px',
            allowClear: true,
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
        if (prdSelection != '') {
            var price = $("#prdName" + rowId).find(':selected').attr('data-price');
            $("#taxablePrice" + rowId).val($("#prdName" + rowId).find(':selected').attr('data-price'));

            $("#prdQty" + rowId).val(1);
            $("#prdPrice" + rowId).val(price);
            $("#lineTotal" + rowId).val(price);

            updateTotalPrice(rowId);
        }
    }

    function updateTotalPrice(rowId) {
        var grandTotal = 0;
        var unitPrice = document.getElementsByName("prdPrice[]");
        var qty = document.getElementsByName("prdQty[]");
        var unitTotal = document.getElementsByName("lineTotal[]");
        for (i = 0; i < unitPrice.length; i++) {
            if (unitPrice[i].value != "" && qty[i].value != "") {
                if (qty[i].value == 0) {
                    alert("Sorry! You can not add ZERO quantity.")
                    qty[i].value = 1;
                }
                unitQtyTotal = parseFloat(unitPrice[i].value) * parseFloat(qty[i].value);
                unitTotal[i].value = unitQtyTotal;
                grandTotal += parseFloat(unitQtyTotal);
            } else {
                grandTotal += 0;
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
        } else {
            updatePrice(rowId);
        }
    }
</script>
<?php
include('../footer.php');
?>