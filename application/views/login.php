<html>
	<head>
		<title>Kernel - Login</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/loginStyle.css")?>">
	</head>
	<body>

		<!-- <div id="alertMessage" class = 'message'>
			<?php
      // if (isset($_SESSION['danger']))
      {
        // echo "<p id = 'danger' class = 'alert'>" . $_SESSION['danger'] . "<span class='closebtn' onclick='removeDanger()';'>&times;</span></p>";
      }
      ?>
		</div> -->

		<div id = mainContainer>

			<img id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>">
			<h1>KERNEL<br>
			<!-- <h1>Human Resource & Resource Management</h1> -->
			INFORMATION SYSTEM</h1>

			<div id = "login" class = "loginElements">
				<form name = "loginForm" action = "validateLogin" method = "POST">
					<input type = "text" placeholder = "USERNAME" name = "username" value = "<?php if (isset($_SESSION['stickyUsername'])) echo $_SESSION['stickyUsername']; ?>" required>
					<input type = "password" placeholder = "PASSWORD" name = "password" required>

					<!--
					<div id = errorMessage>
						<?php echo $message ?>
					</div> -->

					<input type = "submit" name = "submitLogin" value = "LOG IN">
				</form>
			</div>

			<p>Can't Log In? <a href = "<?php echo base_url("index.php/guardwatch_controller/viewContact"); ?>">Click here to contact Lawin</a></p>
		</div>

		<footer>
			<p>© 2018 Team Lowkey, Inc. All Rights Reserved</p>
		</footer>

		<script src = "<?php base_url("/assets/jquery-3.1.1.min.js");?>"></script>
		<script>

		function removeDanger()
		{
			document.getElementById('danger').style.display = 'none';
		}

		</script>
	</body>
</html>
