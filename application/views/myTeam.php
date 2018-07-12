<html>
	<head>
		<title>Kernel - My Team</title>

		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTeamStyle.css")?>">
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

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myTeam"); ?>"><i class="fa fa-dashboard"></i> My Team</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>

				</section>

				<!-- Main content -->
				<section class="content container-fluid" style="padding-top:20px">
					<div id = "toggleView" class="pull-right">
						<a href="#" id = "toggleList" class="btn btn-default btn"><i class="fa fa-th-list"></i>
						<a href="#" id = "toggleGrid" class="btn btn-default btn"><i class="fa fa-th-large"></i></a>
					</div>

					<div id="gridView">

						<div class="btn-group"> <!-- SORT/LEGEND -->

							<button type="button" class="btn btn-primary">All</button>
							<button type="button" class="btn btn.bg-white">Draft</button>
							<button type="button" class="btn btn-warning">Planned</button>
							<button type="button" class="btn btn-success">Ongoing</button>
							<button type="button" class="btn btn-danger">Delayed</button>
							<button type="button" class="btn btn-default">Parked</button>

						</div>

						<!-- LIST AND GRID TOGGLE -->
						<!-- <div id = "toggleView" class="pull-right" style="margin-top:10px"> -->

						<br><br>

						<div class="row">

							<?php foreach ($ongoingProjects as $row):?>
								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "<?php echo $row['PROJECTID']; ?>">
									<div class="small-box bg-green">
										<div class="inner">
											<h2>82%</h2>

											<form action = 'teamGantt'  method="POST">
											</form>

											<p><b><?php echo $row['PROJECTTITLE']; ?></b><br><i><?php echo $row['datediff'] +1;?> day/s remaining</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-clipboard"></i>
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

											<form action = 'teamGantt' method="POST">
											</form>

											<?php //Compute for days remaining
											$startdate = date_create($row['PROJECTSTARTDATE']);
											?>
											<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Launch in <?php echo $row['datediff'] +1;?> day/s</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-lightbulb"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>
						</div>
					</div>

						<!-- LIST VIEW -->
						<br><br>

						<div id="listView">
							<div class="box">
								<div class="box-header" style="display:inline-block">
									<h3 class="box-title">
										<a href="<?php echo base_url("index.php/controller/newProject"); ?>">
											<button type="button" class="btn btn-primary"><i class="fa fa-upload"></i> Create Project</button>
										</a>
									</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<table id="projectList" class="table table-bordered table-hover">
										<thead>
										<tr>
											<th width="1%"></th>
											<th>Project Title</th>
											<th>Start Date</th>
											<th>Target End Date</th>
											<th>Progress</th>
											<th>Status</th>
										</tr>
										</thead>

										<tbody>

										<?php foreach ($ongoingProjects as $row):?>

											<?php // to fix date format
											$ongoingStart = date_create($row['PROJECTSTARTDATE']);
											$ongoingEnd = date_create($row['PROJECTENDDATE']);
											?>

										<tr class="project" data-id = "<?php echo $row['PROJECTID']; ?>">

											<form action = 'teamGantt' method="POST">
											</form>
											<td class="bg-green"></td>
											<td><?php echo $row['PROJECTTITLE']; ?></td>
											<td><?php echo date_format($ongoingStart, "M d, Y");?></td>
											<td><?php echo date_format($ongoingEnd, "M d, Y");?></td>
											<td>80%</td>
											<td><?php echo $row['PROJECTSTATUS']; ?></td>
										</tr>
									<?php endforeach;?>


										<?php foreach ($plannedProjects as $row):?>

											<?php // to fix date format
											$plannedStart = date_create($row['PROJECTSTARTDATE']);
											$plannedEnd = date_create($row['PROJECTENDDATE']);
											?>

										<tr class="project" data-id = "<?php echo $row['PROJECTID']; ?>">

											<form action = 'teamGantt' method="POST">
											</form>
											<td class="bg-red"></td>
											<td><?php echo $row['PROJECTTITLE']; ?></td>
											<td><?php echo date_format($plannedStart, "M d, Y");?></td>
											<td><?php echo date_format($plannedEnd, "M d, Y");?></td>
											<td>0%</td>
											<td><?php echo $row['PROJECTSTATUS']; ?></td>
										</tr>
									<?php endforeach;?>

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
			$("#listView").hide();
			$("#toggleGrid").hide();
			$("#myTeam").addClass("active");


			// IF USING GET METHOD FOR PROJECT ID
			// $("a.project").click(function() //redirect to individual project profile
      // {
			//	var $id = $(this).attr('data-id');

      //   // window.location.replace("<?php echo base_url("index.php/controller/teamGantt/?id="); ?>" + $id);
      // });

			// IF USING POST METHOD FOR PROJECT ID
			$(document).on("click", ".project", function() {
				var $id = $(this).attr('data-id');
				$("form").attr("name", "formSubmit");
				$("form").append("<input type='hidden' name='project_ID' value= " + $id + ">");
				$("form").submit();
				});

			$("#toggleView").click(function(){
				if($("#gridView").css("display") == "block")
				{
					$("#gridView").hide();
					$("#listView").show();
					$("#toggleGrid").show();
					$("#toggleList").hide();
				}
				else
				{
					$("#listView").hide();
					$("#gridView").show();
					$("#toggleGrid").hide();
					$("#toggleList").show();
				}
			});

			$(function () {
		    $('#projectList').DataTable({
		      'paging'      : false,
		      'lengthChange': false,
		      'searching'   : true,
		      'ordering'    : true,
		      'info'        : false,
		      'autoWidth'   : false
		    });
				$('#projectList').DataTable().columns(-1).order('asc').draw();
		  })
		</script>
	</body>
</html>
