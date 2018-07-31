<html>
	<head>
		<title>Kernel - Monitor Team</title>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTeamStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Monitor Team
						<small>What's happening to my team?</small>
					</h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/monitorTeam"); ?>"><i class="fa fa-dashboard"></i> My Team</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>

				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->

				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#tasks").addClass("active");
			$("#monitorTeam").addClass("active");
		</script>
	</body>
</html>
