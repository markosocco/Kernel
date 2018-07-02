<html>
	<head>
		<title>Kernel - Project Archives</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/archivesStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Project Archives
					<small>What are the projects I have done?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">

				<!-- IF ARCHIVES IS EMPTY -->
				<!-- <?php if(!$archives):?>
					<h1>You have not completed a project yet</h1>
					<p>(Change a Project's PROJECTSTATUS to "Complete" to view dummy Archive)</p>

					<?endif;?> -->

					<!-- IF ARCHIVES IS NOT EMPTY -->
					<?php //foreach ($archives as $row):?>

						<!-- ./col -->
					<?php //endforeach;?>

			</section>

		</div>


		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#projectArchives").addClass("active");
		</script>

	</body>
</html>
