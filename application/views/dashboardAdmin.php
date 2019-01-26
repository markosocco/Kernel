<html>
	<head>
		<title>Kernel - Admin Dashboard</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/dashboardStyle.css")?>"> -->
	</head>
	<body>
		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Welcome, <b><?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?>!</b>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<section class="content container-fluid">

				<!-- ALERTS -->
				<!-- <?php if (isset($_SESSION['alertMessage'])): ?>
					<script>
					$(document).ready(function()
					{
						successAlert();
					});
					</script>
				<?php endif; ?> -->
				<!-- <div>
					<button id="success" type="button" class="btn btn-success">Test Success</button>
					<button id="warning" type="button" class="btn btn-warning">Test Warning</button>
					<button id="danger" type="button" class="btn btn-danger">Test Danger</button>
					<button id="info" type="button" class="btn btn-info">Test Info</button>
				</div>
				<br> -->

	


		</section>
			</div>

		<?php require("footer.php"); ?>

	</div> <!--.wrapper closing div-->

	<script>
		$("#dashboard").addClass("active");
		$('.circlechart').circlechart(); // Initialization

		$(document).ready(function()
		{
			$("#success").click(function(){
				successAlert();
			});
			$("#danger").click(function(){
				dangerAlert();
			});
			$("#warning").click(function(){
				warningAlert();
			});
			$("#info").click(function(){
				infoAlert();
			});
		});
	</script>

	</body>
</html>
