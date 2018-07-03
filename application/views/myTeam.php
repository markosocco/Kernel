<html>
	<head>
		<title>Kernel - My Team</title>
		<!-- <link rel = "stylesheet" href = "<?php //echo base_url("/assets/css/myTeamStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					My Team
					<small>What is my team doing?</small>
				</h1>

				<!-- LIST AND GRID TOGGLE -->
				<div id = "toggleView" class="pull-right" style="margin-top:10px">
					<a href="#" id = "toggleList" class="btn btn-default btn"><i class="fa fa-th-list"></i>
					<a href="#" id = "toggleGrid" class="btn btn-default btn"><i class="fa fa-th-large"></i></a>
				</div>
			</section>

		</div>


		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#myTeam").addClass("active");
		</script>

	</body>
</html>
