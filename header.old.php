<?php

session_start();
if(!isset($_SESSION['userId'])){
  header("location:login.php");
}
include_once('includes/MysqliDb.php');
include_once('includes/General.php');
$cmyQuery="SELECT company_code,company_name FROM company_profile";
$cmyResult = $db->rawQuery($cmyQuery);
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
  <title><?php echo (isset($title) && $title != null && $title != "") ? $title : $cmyResult[0]['company_name'] ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="shortcut icon" href="/uploads/logo/favicon.ico">
  <link rel="stylesheet" media="all" type="text/css" href="/assets/css/jquery-ui.1.11.0.css" />
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/assets/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/assets/css/font-awesome.min.4.5.0.css">
  
  <!-- Ionicons -->
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">-->
  <!-- DataTables -->
  <link rel="stylesheet" href="/assets/plugins/datatables/dataTables.bootstrap.css">

  <script type="text/javascript" src="/assets/js/jquery-2.2.3.min.js"></script>

 <!-- Latest compiled and minified JavaScript -->
 <script type="text/javascript" src="/assets/js/jquery-ui.1.11.0.js"></script>
    
<style>
  .dataTables_wrapper{
    position: relative;
    clear: both;
    overflow-x: scroll !important;
    overflow-y: visible !important;
    padding: 15px 0 !important;
  }
  .mandatory{color:red;}
</style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b><?php echo $cmyResult[0]['company_code'];?></b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" style="font-weight:bold;"><?php echo $cmyResult[0]['company_name'];?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
	<!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      
     
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/assets/img/default-user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"></span>
            </a>
            <ul class="dropdown-menu">
              <!-- Menu Footer-->
              <li class="user-footer">
              <?php 
              if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'merlin'){?>
		            <div class="pull-left">
                  <a href="company/profile.php" class="btn btn-default btn-flat">Edit Company profile</a>
                </div>
              <?php 
              $pullVal = "pull-right";
              }else{$pullVal = "text-center";} ?>
		            <div class="<?php echo $pullVal;?>">
                  <a href="logout.php" class="btn btn-default btn-flat">Log out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div align="center">
          <!-- <img src="uploads/logo/< ?php echo $global['logo']; ?>"  alt="Logo Image" style="max-width:120px;" > -->
        </div>
      </div>
      <ul class="sidebar-menu">
	    <!-- <li class="allMenu dashboardMenu active">
	      <a>
      <i class="fa fa-dashboard"></i> <span>Dashboard</span>
	      </a>
	    </li> -->
      <li class="treeview manage">
	      <a href="#">
          <i class="fa fa-gears"></i>
          <span>Manage</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
	      </a>
	      <ul class="treeview-menu">
            <?php if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'merlin'){?>
              <li class="allMenu userMenu">
                <a href="users/user-list.php"><i class="fa fa-user"></i> Users</a>
              </li>
            <?php } ?>
            <li class="allMenu agentMenu">
              <a href="agents/agentList.php"><i class="fa fa-user"></i> Agents</a>
            </li>
            <li class="allMenu clientMenu">
              <a href="clients/client-list.php"><i class="fa fa-user"></i> Clients</a>
            </li>
            <?php if(isset($_SESSION['userLoginId']) && $_SESSION['userLoginId'] == 'merlin'){?>
            <li class="allMenu supplierMenu">
              <a href="supplier/supplierList.php"><i class="fa fa-user"></i> Suppliers</a>
            </li>
            <?php } ?>
            <li class="allMenu productMenu">
              <a href="products/product-list.php"><i class="fa fa-list"></i> Products</a>
            </li>
	      </ul>
        
	    </li>
        <li class="treeview invoiceMenu">
          <a href="#">
            <i class="fa fa-money"></i>
            <span>Invoice</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="allMenu createInvoice">
              <a href="invoice/create-invoice.php"><i class="fa fa-money"></i> Create Invoice</a>
            </li>
            <li class="allMenu invoiceList">
              <a href="invoice/invoice-list.php"><i class="fa fa-list"></i> Invoice</a>
            </li>
            <li class="allMenu buyerHistory">
              <a href="invoice/buyer-history.php"><i class="fa fa-list"></i> Buyer History</a>
            </li>
          </ul>
        </li>
        <li class="treeview purchaseMenu">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>Purchase</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="allMenu purchase">
              <a href="purchase/purchaseList.php"><i class="fa fa-shopping-cart"></i> Purchase Inventory</a>
            </li>
          </ul>
        </li>
        <li class="treeview expenseMenu">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>Expense</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="allMenu expense">
              <a href="expenses/expense-list.php"><i class="fa fa-money"></i> Expense Details</a>
            </li>
          </ul>
        </li>
        <li class="treeview stockMenu">
          <a href="#">
            <i class="fa fa-list"></i>
            <span>Stock</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="allMenu stock">
              <a href="stock/stock-list.php"><i class="fa fa-fw fa-balance-scale"></i> Stock Details</a>
            </li>
          </ul>
        </li>
        <!---->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>