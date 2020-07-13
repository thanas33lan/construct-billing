  <footer class="main-footer">
  	<div class="pull-right hidden-xs">
  		<b>Version</b> 1.2.0
  	</div>
  	<strong>Copyright © 2017-<?php echo date('Y'); ?> <a href="https://www.facebook.com/samwinTech/">SAMWIN INFOTECH</a>.</strong> All rights
  	reserved.
  </footer>
  </div>
  <!-- ./wrapper -->

  <!-- Bootstrap 3.3.6 -->
  <script src="<?php echo BASE_URL; ?>assets/js/bootstrap.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/validation.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/select2.js"></script>
  <!-- DataTables -->
  <script src="<?php echo BASE_URL; ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>

  <!-- AdminLTE App -->
  <script src="../dist/js/app.min.js"></script>
  <script src="<?php echo BASE_URL; ?>assets/js/jquery.blockUI.js"></script>

  <script type="text/javascript">
  	$(document).ready(function() {
  		str = $(location).attr('pathname');
		splitsUrl = str.split("/", 4);
  		if (splitsUrl[2] == 'user-list.php' || splitsUrl[2] == 'addUser.php' || splitsUrl[2] == 'editUser.php') {
  			$(".manage").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".userMenu").addClass('active');
  		}
  		if (splitsUrl[2] == 'agentList.php' || splitsUrl[2] == 'addAgent.php' || splitsUrl[2] == 'editAgent.php') {
  			$(".manage").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".agentMenu").addClass('active');
  		}
  		if (splitsUrl[2] == 'supplierList.php' || splitsUrl[2] == 'addSupplier.php' || splitsUrl[2] == 'editSupplier.php') {
  			$(".manage").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".supplierMenu").addClass('active');
  		} else if (splitsUrl[2] == 'client-list.php' || splitsUrl[2] == 'addClient.php' || splitsUrl[2] == 'editClient.php') {
  			$(".manage").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".clientMenu").addClass('active');
  		} else if (splitsUrl[2] == 'product-list.php' || splitsUrl[2] == 'addProduct.php' || splitsUrl[2] == 'editProduct.php') {
  			$(".manage").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".productMenu").addClass('active');
  		} else if (splitsUrl[2] == 'create-invoice.php') {
  			$(".invoiceMenu").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".createInvoice").addClass('active');
  			$("body").addClass('sidebar-collapse');
  		} else if (splitsUrl[2] == 'invoice-list.php') {
  			$(".invoiceMenu").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".invoiceList").addClass('active');
  		} else if (splitsUrl[2] == 'buyer-history.php') {
  			$(".invoiceMenu").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".buyerHistory").addClass('active');
  		} else if (splitsUrl[2] == 'purchaseList.php' || splitsUrl[2] == 'addPurchase.php' || splitsUrl[2] == 'editPurchase.php') {
  			$(".purchaseMenu").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".purchase").addClass('active');
  		} else if (splitsUrl[2] == 'expense-list.php' || splitsUrl[2] == 'addExpense.php' || splitsUrl[2] == 'editExpense.php') {
  			$(".expenseMenu").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".expense").addClass('active');
  		} else if (splitsUrl[2] == 'stock-list.php') {
  			$(".stockMenu").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".stock").addClass('active');
  		}else if (splitsUrl[1] == 'quotations') {
  			$(".quotationsMenu").addClass('active');
  			$(".allMenu").removeClass('active');
  			$(".quotations").addClass('active');
  		}

  	});

  	<?php
		if (isset($_SESSION['alertMsg']) && trim($_SESSION['alertMsg']) != "") {
			?>
  		alert('<?php echo $_SESSION['alertMsg']; ?>');
  		<?php
			$_SESSION['alertMsg'] = '';
			unset($_SESSION['alertMsg']);
		}
		?>
  	jQuery(".checkNum").keydown(function(e) {
  		// Allow: backspace, delete, tab, escape, enter and .
  		if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
  			// Allow: Ctrl+A
  			(e.keyCode == 65 && e.ctrlKey === true) ||
  			// Allow: home, end, left, right
  			(e.keyCode >= 35 && e.keyCode <= 39)) {
  			// let it happen, don't do anything
  			return;
  		}
  		// Ensure that it is a number and stop the keypress
  		if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
  			e.preventDefault();
  		}
  	});
  </script>

  </body>

  </html>