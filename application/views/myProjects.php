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
						<small>What are my projects?</small>
						<small>(GREEN = ONGOING; ORANGE = PLANNED; RED = DELAYED; GRAY = PARKED; WHITE = DRAFT)</small>
					</h1>


					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>

					<!-- LIST AND GRID TOGGLE -->
					<div id = "toggleView" class="pull-right" style="margin-top:10px">
						<a href="#" id = "toggleList" class="btn btn-default btn"><i class="fa fa-th-list"></i>
						<a href="#" id = "toggleGrid" class="btn btn-default btn"><i class="fa fa-th-large"></i></a>
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

											<form action = 'projectGantt'  method="POST">
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
					</div>

					<div class="box box-solid bg-teal-gradient">
            <div class="box-header">
              <i class="fa fa-th"></i>

              <h3 class="box-title">Sales Graph</h3>

            </div>
            <div class="box-body border-radius-none">
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-border">
              <div class="row">
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                  <input type="text" class="knob" data-readonly="true" value="20%" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">

                  <div class="knob-label">Progress</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center" style="border-right: 1px solid #f4f4f4">
                  <input type="text" class="knob" data-readonly="true" value="50%" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">

                  <div class="knob-label">Completeness</div>
                </div>
                <!-- ./col -->
                <div class="col-xs-4 text-center">
                  <input type="text" class="knob" data-readonly="true" value="30%" data-width="60" data-height="60"
                         data-fgColor="#39CCCC">

                  <div class="knob-label">Timeliness</div>
                </div>
                <!-- ./col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->


						<!-- LIST VIEW -->

						<div id="listView">
							<div class="box">
								<div class="box-header">
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

										<tr class="btn-success project" data-id = "<?php echo $row['PROJECTID']; ?>">

											<form name = "projectID_<?php echo $row['PROJECTID']; ?>" action = 'projectGantt' method="POST">
												<input type = "hidden" class = "inputID">
											</form>

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

										<tr class="btn-warning project" data-id = "<?php echo $row['PROJECTID']; ?>">

											<form name = "projectID_<?php echo $row['PROJECTID']; ?>" action = 'projectGantt' method="POST">
												<input type = "hidden" class = "inputID">
											</form>

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
			$("#myProjects").addClass("active");

			// IF USING GET METHOD FOR PROJECT ID
			// $("a.project").click(function() //redirect to individual project profile
      // {
			//	var $id = $(this).attr('data-id');

      //   // window.location.replace("<?php echo base_url("index.php/controller/projectGantt/?id="); ?>" + $id);
      // });

			// IF USING POST METHOD FOR PROJECT ID
			$(document).on("click", ".project", function() {
				var $id = $(this).attr('data-id');

				// alert($id);
				$(".inputID").html("<input type='hidden' name='project_ID' value= " + $id + ">");
				// $("form").attr('id', 'x');
				// $("#prjID_" + $id).attr('name', 'project_ID');
				// $("form").attr('name', 'formSub');
				// $(".inputID").attr('name', 'projectID');
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
		      'searching'   : false,
		      'ordering'    : true,
		      'info'        : false,
		      'autoWidth'   : false
		    });
				$('#projectList').DataTable().columns(-1).order('asc').draw();
		  })
		</script>
	</body>
</html>
