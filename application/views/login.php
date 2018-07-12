<html>
	<head>
		<title>Kernel - Log In</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/loginStyle.css")?>">
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Bootstrap Notify -->
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>animate.css/animate.min.css"/>
	</head>
	<body>

		<div id = mainContainer>

			<img class="animated zoomIn" id = "logo"  src = "<?php echo base_url("/assets/media/tei.png")?>">
			<h3 id="formHeader">KERNEL:<br>PROJECT MANAGEMENT</h3>

			<div id = "login" class = "loginElements">
				<form name = "loginForm" action = "validateLogin" method = "POST">
					<input type = "text" placeholder = "USERNAME" name = "email" value = "<?php if (isset($_SESSION['stickyEmail'])) echo $_SESSION['stickyEmail']; ?>" required>
					<input type = "password" placeholder = "PASSWORD" name = "password" required>

					<input type = "submit" class="btn btn-success" name = "submitLogin" value = "LOG IN">
				</form>
			</div>

			<p>Unable to Log In? <a href = "<?php echo base_url("index.php/controller/contact"); ?>" id="resetPass">Reset Password</a></p>
		</div>

		<footer>
			<p>&copy; 2018 <a href="http://www.ilovetaters.com">Taters Enterprises Inc</a>. All rights reserved.</p>
			<!-- <p>© 2018 Team Lowkey, Inc. All Rights Reserved</p> -->
		</footer>

		<!-- jQuery 3 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

	</body>
</html>
