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
								<a class = "project" data-id = "<?php echo $row['PROJECTID']; ?>">
								<div class="small-box bg-green">
									<div class="inner">
										<h2>82%</h2>

										<?php //Compute for days remaining
										$current = date_create(date("Y-m-d"));
										$end = date_create($row['PROJECTENDDATE']);
										$edate = date_format($end, "Y-m-d");
										$enddate = date_create($edate);
										$datediff = date_diff($enddate, $current);
										?>
										<p><b><?php echo $row['PROJECTTITLE']; ?></b><br><i><?php echo $datediff->format('%a');?> days remaining</i></p>
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
								<a class = "project" data-id = "<?php echo $row['PROJECTID']; ?>">
								<div class="small-box bg-yellow">
									<div class="inner">
										<h2><?php echo $row['PROJECTTITLE']; ?></h2>

										<?php //Compute for days remaining
										$current = date_create(date("Y-m-d"));
										$start = date_create($row['PROJECTSTARTDATE']);
										$sdate = date_format($start, "Y-m-d");
										$startdate = date_create($sdate);
										$datediff = date_diff($startdate, $current);
										?>

										<?php $startdate = date_create($row['PROJECTSTARTDATE']);?>
										<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Launch in <?php echo $datediff->format('%a');?> days</i></p>
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

			$("a.project").click(function() //redirect to individual project profile
      {
        var $id = $(this).attr('data-id');
        window.location.replace("<?php echo base_url("index.php/controller/projectGantt/?id="); ?>" + $id);
      });
		</script>
	</body>
</html>
