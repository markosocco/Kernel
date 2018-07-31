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
					<div class="row">
						<div class="col-md-4">
							<!-- Widget: user widget style 1 -->
							<div class="box box-widget widget-user">
								<!-- Add the bg color to the header using any of the bg-* classes -->
								<div class="widget-user-header bg-aqua-active">
									<h3 class="widget-user-username">Alexander Pierce</h3>
									<h5 class="widget-user-desc">Founder &amp; CEO</h5>
								</div>
								<div class="widget-user-image">
									<img class="img-circle" src="../dist/img/user1-128x128.jpg" alt="User Avatar">
								</div>
								<div class="box-footer">
									<div class="row">
										<div class="col-sm-4 border-right">
											<div class="description-block">
												<h5 class="description-header">3,200</h5>
												<span class="description-text">SALES</span>
											</div>
											<!-- /.description-block -->
										</div>
										<!-- /.col -->
										<div class="col-sm-4 border-right">
											<div class="description-block">
												<h5 class="description-header">13,000</h5>
												<span class="description-text">FOLLOWERS</span>
											</div>
											<!-- /.description-block -->
										</div>
										<!-- /.col -->
										<div class="col-sm-4">
											<div class="description-block">
												<h5 class="description-header">35</h5>
												<span class="description-text">PRODUCTS</span>
											</div>
											<!-- /.description-block -->
										</div>
										<!-- /.col -->
									</div>
									<!-- /.row -->
								</div>
							</div>
							<!-- /.widget-user -->
						</div>
						<!-- /.col -->

					</div>
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
