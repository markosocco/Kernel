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
					<div class="pull-right" style="margin-top:10px">
						<a href="#" class="btn btn-default btn"><i class="fa fa-th-list"></i></a>
						<a href="#" class="btn btn-default btn"><i class="fa fa-th-large"></i></a>
					</div>

				</section>

				<!-- Main content -->
				<section class="content container-fluid" style="padding-top:50px">
					<div id="gridView">

						<div class="row">
							<div class="col-lg-3 col-xs-6">
								<!-- small box -->
								<a href="<?php echo base_url("index.php/controller/newProject"); ?>">
								<div class="small-box bg-blue">
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

											<form name = "projectID_<?php echo $row['PROJECTID']; ?>" action = 'projectGantt' method="POST">
												<input type = "hidden" class = "inputID">
												<!-- <input type="hidden" name="project_ID" value="<?php echo $row['PROJECTID']; ?>" id ="prjID_<?php echo $row['PROJECTID']; ?>"> -->
											</form>
											<p><b><?php echo $row['PROJECTTITLE']; ?></b><br><i><?php echo $row['datediff'] +1;?> day/s remaining</i></p>
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

											<form name = "projectID_<?php echo $row['PROJECTID']; ?>" action = 'projectGantt' method="POST">
												<input type = "hidden" class = "inputID">
												<!-- <input type="hidden" name="project_ID" value="<?php echo $row['PROJECTID']; ?>" id ="prjID_<?php echo $row['PROJECTID']; ?>"> -->
											</form>

											<?php //Compute for days remaining
											$startdate = date_create($row['PROJECTSTARTDATE']);
											?>
											<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Launch in <?php echo $row['datediff'] +1;?> day/s</i></p>
										</div>
										<div class="icon">
											<i class="ion ion-clock"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>
						</div>

						<div id="listView">
							<div class="box">
								<div class="box-header">
									<h3 class="box-title">
										<button type="button" class="btn btn-primary"><i class="fa fa-upload"></i> Create Project</button>
									</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<table id="projectList" class="table table-bordered table-hover">
										<thead>
										<tr>
											<th>Project Title</th>
											<th>Start Date</th>
											<th>Target Start Date</th>
											<th>Status</th>
											<th>Progress</th>
										</tr>
										</thead>
										<tbody>
										<tr class="btn-success">
											<td>Southmall Opening</td>
											<td>Today</td>
											<td>Tomorrow</td>
											<td>Ongoing</td>
											<td>X</td>
										</tr>
										<a href=""><tr class="btn-warning">
											<td>Southmall Opening</td>
											<td>Today</td>
											<td>Tomorrow</td>
											<td>Planning</td>
											<td>X</td>
										</tr></a>
										</tbody>
									</table>
								</div>
								<!-- /.box-body -->
							</div>
						</div>
					</section>
				</div>

				<!-- /.content -->

			<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->
		<!-- ./wrapper -->

		<script>
			$("#myProjects").addClass("active");

			// IF USING GET METHOD FOR PROJECT ID
			// $("a.project").click(function() //redirect to individual project profile
      // {
			//	var $id = $(this).attr('data-id');

      //   // window.location.replace("<?php echo base_url("index.php/controller/projectGantt/?id="); ?>" + $id);
      // });

			// IF USING POST METHOD FOR PROJECT ID
			$(document).on("click", "a.project", function() {
				var $id = $(this).attr('data-id');
				$(".inputID").html("<input type='hidden' name='project_ID' value= " + $id + ">");
				// $("form").attr('id', 'x');
				// $("#prjID_" + $id).attr('name', 'project_ID');
				$("form").submit();
				});

			$(function () {
		    $('#projectList').DataTable({
		      'paging'      : true,
		      'lengthChange': false,
		      'searching'   : false,
		      'ordering'    : true,
		      'info'        : true,
		      'autoWidth'   : false
		    })
		  })
		</script>
	</body>
</html>
