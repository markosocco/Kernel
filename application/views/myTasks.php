<html>
	<head>
		<title>Kernel - My Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTasksStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						My Tasks
						<small>What do I need to do</small>
					</h1>
					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myTasks"); ?>"><i class="fa fa-dashboard"></i> My Tasks</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<div>
				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>
			$("#myTasks").addClass("active");
		</script>
	</body>
</html>
