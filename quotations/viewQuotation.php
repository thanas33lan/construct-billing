<?php
$title = ucwords("Quotations view");
include('../header.php');
$general = new General();

$bQuery = "SELECT * FROM quotations WHERE q_id='" . base64_decode($_GET['id']) . "'";
$bResult = $db->rawQuery($bQuery);

$bdQuery = "SELECT qpm.p_price, qpm.p_qty, qpm.discount, qpm.line_total, p.product_name FROM quotations_products_map AS qpm JOIN product_details AS p ON qpm.product_id=p.product_id WHERE qpm.q_id='" . base64_decode($_GET['id']) . "'";
$bdResult = $db->rawQuery($bdQuery);

//client name
$cliQuery = "SELECT * FROM client_details WHERE client_id='" . $bResult[0]['q_customer'] . "'";
$cliResult = $db->rawQuery($cliQuery);

//client name
$uQuery = "SELECT user_name FROM user_details WHERE user_id='" . $bResult[0]['q_added_by'] . "'";
$uResult = $db->rawQuery($uQuery);

$cQuery = "SELECT * FROM company_profile";
$cResult = $db->rawQuery($cQuery);
?>
<style>
    .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
        border-top: 1px solid transparent;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

        <div class="box box-default container">
            <!-- /.box-header -->
            <div class="box-body row">
                <table class="table table-responsive" border="0">
                    <tr>
                        <td>
                            <h1 style="line-height:1; text-align:center;"><u>Quotation</u></h1>
                        </td>
                    </tr>
                    <tr class="col-md-6"><td width="68%">#<?php echo $bResult[0]['q_code']; ?></td></tr>
                    <tr>
                        <td>
                            <table border="0">
                                <tr>
                                    <td width="68%" class="col-md-6">
                                        <h2 style="line-height:0; font-size:22px;"><u><?php echo strtoupper($cResult[0]['company_name']) ?></u></h2>
                                        <br>Mobile No : <?php echo $cResult[0]['company_phone'] . '<br> Alternate :' . $cResult[0]['alt_number'] ?>
                                        <br>e-mail: <?php echo $cResult[0]['company_email'] ?>
                                    </td>
                                    <td class="col-md-6">
                                        <img src="<?php echo BASE_URL; ?>uploads/logo/<?php echo $cResult[0]['company_logo']; ?>" alt="Company Logo" style="width:30%;">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <hr>
                    <tr>
                        <td>
                            <table border="0">
                                <tr>
                                    <td class="col-md-6">To<br><b>The&nbsp;&nbsp;<?php echo ucwords($cliResult[0]['client_name']); ?>,<br><?php echo $cliResult[0]['client_address']; ?><br>Tamilnadu</b>
                                    </td>
                                    <td class="col-md-6">
                                        <table border="0">
                                            <tr><br>
                                                <td><b>For Enquiry</b></td>
                                                <td width="12px">: </td>
                                                <td style="text-align:left;"><?php echo $cResult[0]['company_email'] . ' (+91 ' . $cResult[0]['company_phone'] . ')'; ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Enquiry Date</b></td>
                                                <td>: </td>
                                                <td style="text-align:left;"><?php echo $general->humanDateFormat($bResult[0]['enquiry_date']); ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Our Ref. No</b></td>
                                                <td>: </td>
                                                <td style="text-align:left;"><?php echo $bResult[0]['q_code']; ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Date </b></td>
                                                <td>: </td>
                                                <td style="text-align:left;"><?php echo $general->humanDateFormat($bResult[0]['q_date']); ?></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <hr>
                            <div class="col-md-12">
                                <p align="left">Kind Attention:&nbsp;<u><b><?php echo ucwords($cliResult[0]['client_name']); ?></b></u></p>
                                <table border="0" style="padding-left:10px">
                                    <tr>
                                        <td style="word-wrap: break-word;">Dear Sir,<br>
                                            We thank you very much for your enquiry at <?php echo $general->humanDateFormat($bResult[0]['enquiry_date']); ?> , and we are very pleased to quote our most
                                            competitive rates for the following items.
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="table table-bordered table-responsive table-striped" style="padding:3px; align:center;" border="1">
                                <tr>
                                    <th align="center" style=" font-weight:bold;" width="40">S:No</th>
                                    <th align="center" style=" font-weight:bold;">Product</th>
                                    <th align="center" style=" font-weight:bold;" width="150">Price</th>
                                    <th align="center" style=" font-weight:bold;" width="100">Quantity</th>
                                    <th align="center" style=" font-weight:bold;" width="100">DISC in (%)</th>
                                    <th align="center" style=" font-weight:bold;" width="150">Total</th>
                                </tr>
                                <tbody>
                                    <?php foreach ($bdResult as $key => $val) { ?>
                                        <tr>
                                            <td align="center"><?php echo ($key + 1); ?></td>
                                            <td align="left"><?php echo ucwords($val['product_name']); ?></td>
                                            <td align="right">₹ <?php echo number_format($val['p_price'], 2); ?></td>
                                            <td align="right"><?php echo $val['p_qty']; ?></td>
                                            <td align="right"><?php echo $val['discount']; ?> %</td>
                                            <td align="right">₹ <?php echo number_format($val['line_total'], 2); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" align="right"><b>Grand Total</b></td>
                                        <td align="right"><b>₹ <?php echo number_format($bResult[0]['grand_total'], 2); ?></b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="line-height:3px">We would be looking forward to receive your valued order.</p>
                            <p>Thanking You.</p><br>
                        </td>
                    </tr>

                    <tr>
                        <td>With regards,</td>
                    </tr>
                    <tr>
                        <td>
                            <p style="line-height:1; text-align:left;">For, <b><?php echo strtoupper($cResult[0]['company_name']); ?></b></p>
                        </td>
                    </tr>
                </table>
            </div>
    </section>
</div>
<?php
include('../footer.php');
?>