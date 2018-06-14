<html>
	<head>
		<title>Kernel - Log In</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/loginStyle.css")?>">
	</head>
	<body>

		<div id = mainContainer>

			<img id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>">
			<h1>KERNEL:<br>
			PROJECT MANAGEMENT</h1>

			<div id = "login" class = "loginElements">
				<form name = "loginForm" action = "validateLogin" method = "POST">
					<input type = "text" placeholder = "USERNAME" name = "email" value = "<?php if (isset($_SESSION['stickyEmail'])) echo $_SESSION['stickyEmail']; ?>" required>
					<input type = "password" placeholder = "PASSWORD" name = "password" required>

					<input type = "submit" name = "submitLogin" value = "LOG IN">
				</form>
			</div>

			<p>Unable to Log In? <a href = "<?php echo base_url("index.php/controller/contact"); ?>">Click here to contact the MIS Department</a></p>
		</div>

		<footer>
			<p>&copy; 2018 <a href="http://www.ilovetaters.com">Taters Enterprises Inc</a>. All rights reserved.</p>
			<!-- <p>© 2018 Team Lowkey, Inc. All Rights Reserved</p> -->
		</footer>

	</body>
</html>
