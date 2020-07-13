<?php
include('base-url.php');
session_start();
if (isset($_SESSION['userId'])) {
	header("location:invoice/create-invoice.php");
}
try {
	include_once('includes/MysqliDb.php');
	define('UPLOAD_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));
	//invoice number generation
	$cQuery = "SELECT * FROM company_profile";
	$cResult = $db->rawQuery($cQuery);
} catch (Exception $e) {
	error_log($e->getMessage());
}


?>
<title>Welcome To AR Designer Tiles and Hollow Blocks</title>
<meta name="AR Designer Tiles and Hollow Blocks" content="This is the samwin infotech in billing management system for need please call +919944514911">
<link rel='stylesheet prefetch' href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900'>
<link rel='stylesheet prefetch' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
<style>
	body {
		background: #e9e9e9;
		color: #666666;
		font-family: 'RobotoDraft', 'Roboto', sans-serif;
		font-size: 14px;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
	}

	/* Form Module */
	.form-module {
		position: relative;
		background: #ffffff;
		max-width: 320px;
		width: 100%;
		border-top: 5px solid #33b5e5;
		box-shadow: 0 0 3px rgba(0, 0, 0, 0.1);
		margin: 0 auto;
	}

	.form-module .toggle {
		cursor: pointer;
		position: absolute;
		top: -0;
		right: -0;
		background: #33b5e5;
		width: 30px;
		height: 30px;
		margin: -5px 0 0;
		color: #ffffff;
		font-size: 20px;
		line-height: 30px;
		text-align: center;
	}

	.form-module .form {
		display: none;
		padding: 40px;
	}

	.form-module .form:nth-child(2) {
		display: block;
	}

	.form-module h2 {
		margin: 0 0 20px;
		color: #33b5e5;
		font-size: 18px;
		font-weight: 400;
		line-height: 1;
	}

	.form-module input {
		outline: none;
		display: block;
		width: 100%;
		border: 1px solid #d9d9d9;
		margin: 0 0 20px;
		padding: 10px 15px;
		box-sizing: border-box;
		font-wieght: 400;
		-webkit-transition: 0.3s ease;
		transition: 0.3s ease;
	}

	.form-module input:focus {
		border: 1px solid #33b5e5;
		color: #333333;
	}

	.form-module button {
		cursor: pointer;
		background: #33b5e5;
		width: 100%;
		border: 0;
		padding: 10px 15px;
		color: #ffffff;
		-webkit-transition: 0.3s ease;
		transition: 0.3s ease;
	}

	.form-module button:hover {
		background: #178ab4;
	}

	.form-module .cta {
		background: #f2f2f2;
		width: 100%;
		padding: 15px 40px;
		box-sizing: border-box;
		color: #666666;
		font-size: 12px;
		text-align: center;
	}

	.form-module .cta a {
		color: #333333;
		text-decoration: none;
	}
</style>
<br /><br />
<!-- Form Module-->
<div class="module form-module">
	<div class="toggle"><i class="fa fa-times fa-lock"></i>
	</div>
	<div class="form">
		<div style="text-align: center;margin-top: -8%;">
			<?php
			if (isset($cResult[0]['company_logo']) && trim($cResult[0]['company_logo']) != '' && file_exists(UPLOAD_PATH . DIRECTORY_SEPARATOR . 'logo' . DIRECTORY_SEPARATOR . $cResult[0]['company_logo'])) {
				?>
				<img style="width:100%;" src="uploads/logo/<?php echo $cResult[0]['company_logo']; ?>" alt="Logo">
			<?php } ?>
		</div>
		<h2 align="center">Please sign in to proceed</h2>
		<form class="" method="post" name="adminLoginInformation" id="adminLoginInformation" action="loginProcess.php" autocomplete="off" onsubmit="return doLogin();return false;">
			<div class="form-group">
				<div class="input-group ">
					<!--<span class="input-group-addon"><i class="fa fa-envelope"></i></span>-->
					<input type="text" class="form-control " placeholder="Login Id" name="loginId" autofocus title="Please enter your login id">
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<input class="form-control " placeholder="Password" name="password" type="password" title="Please enter your password">
				</div>
			</div>
			<button onClick="doLogin();return false;" title="Login">Login</button><br /><br />
			<!--<a href="javascript:void(0)" onClick="forgotLogin('show');" class="btn btn-primary" title="Forgot Password">Forgot Password?</a>-->
		</form>
		App version : 1.2.0
	</div>
</div>

<script type="text/javascript" src="<?php echo BASE_URL; ?>assets/js/jquery-2.2.3.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		<?php
		if (isset($_SESSION['alertMsg']) && trim($_SESSION['alertMsg']) != "") {
			?>
			alert('<?php echo $_SESSION['alertMsg']; ?>');
			<?php
			$_SESSION['alertMsg'] = '';
			unset($_SESSION['alertMsg']);
		}
		?>
	});

	function doLogin() {

		document.getElementById('adminLoginInformation').submit();
	}
</script>