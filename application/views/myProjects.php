<html>
	<head>
		<title>Kernel - My Projects</title>

		<!-- <link rel = "stylesheet" href = "<?php //echo base_url("/assets/css/myProjectsStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						My Projects
						<small>What are my projects</small>
						<small>(GREEN = ONGOING; YELLOW = PLANNED *FOR NOW*)</small>
					</h1>
					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">

					<div class="row">
						<div class="col-lg-3 col-xs-6">
							<!-- small box -->
							<a href="<?php echo base_url("index.php/controller/newProject"); ?>">
							<div class="small-box bg-red">
								<div class="inner">
									<h2>Create</h2>

									<p>New<br>Project</p>
								</div>
								<div class="icon">
									<i class="ion ion-plus-round"></i>
								</div>
							</div>
						</a>
						</div>
						<!-- ./col -->

						<?php foreach ($ongoingProjects as $row):?>
							<div class="col-lg-3 col-xs-6">
								<!-- small box -->
								<a href="<?php echo base_url("index.php/controller/projectGantt"); ?>">
								<div class="small-box bg-green">
									<div class="inner">
										<h2>82%</h2>

										<!-- <p><?php echo $row['PROJECTSTARTDATE']; ?>-<?php echo $row['PROJECTENDDATE']; ?></p> -->
										<p><?php echo $row['PROJECTTITLE']; ?><br>420 days remaining</p>
									</div>
									<div class="icon">
										<i class="ion ion-beaker"></i>
									</div>
								</div>
							</a>
							</div>
							<!-- ./col -->
						<?php endforeach;?>

						<?php foreach ($plannedProjects as $row):?>
							<div class="col-lg-3 col-xs-6">
								<!-- small box -->
								<a href="<?php echo base_url("index.php/controller/projectGantt"); ?>">
								<div class="small-box bg-yellow">
									<div class="inner">
										<h2><?php echo $row['PROJECTTITLE']; ?></h2>

										<p><?php echo $row['PROJECTSTARTDATE']; ?><br>Launch in 70 days</p>
									</div>
									<div class="icon">
										<i class="ion ion-clock"></i>
									</div>
								</div>
							</a>
							</div>
							<!-- ./col -->
						<?php endforeach;?>
				</section>

			</div>
				<!-- /.content -->

			<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->
		<!-- ./wrapper -->

		<script>
			$("#myProjects").addClass("active");
		</script>
	</body>
</html>
