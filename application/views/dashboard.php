<html>
	<head>
		<title>Kernel - Dashboard</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/dashboardStyle.css")?>"> -->
	</head>
	<body>
		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					WELCOME, <b><?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?></b>
					<small>(If you put content, the sidebar starts to work everywhere!)</small>
				</h1>
			</section>

		</div>


		<?php require("footer.php"); ?>

	</div> <!--.wrapper closing div-->

	<script>
		$("#dashboard").addClass("active");
	</script>

	</body>
</html>
