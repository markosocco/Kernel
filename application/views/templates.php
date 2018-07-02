<html>
	<head>
		<title>Kernel - Project Templates</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/templatesStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Templates
					<small>What are the sample project models?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">

				<!-- IF ARCHIVES IS EMPTY -->
				<!-- <?php if(!$templates):?>
					<h1>You do not have any project templates</h1>
					<p>(How are we templating? I don't understand the table and columns)</p>

					<?endif;?> -->

					<!-- IF ARCHIVES IS NOT EMPTY -->
					<?php //foreach ($templates as $row):?>

						<!-- ./col -->
					<?php //endforeach;?>

			</section>

		</div>


		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#templates").addClass("active");
		</script>

	</body>
</html>
