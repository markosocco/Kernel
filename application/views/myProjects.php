<html>
	<head>
		<title>Kernel - My Projects</title>

		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myProjectsStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						My Projects
						<small>What are my projects?</small>
					</h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>

				</section>
				<!-- Main content -->
				<section class="content container-fluid" style="padding-top:20px">

					<!-- LIST AND GRID TOGGLE -->
					<div id = "toggleDisplayView" class="pull-right">
						<a href="#" id = "toggleList" class="btn btn-default btn"><i class="fa fa-th-list"></i>
						<a href="#" id = "toggleGrid" class="btn btn-default btn"><i class="fa fa-th-large"></i></a>
					</div>

					<!-- PROJECT AND TEAM TOGGLE -->
					<div id = "toggleTypeView" class="pull-right">
						<a href="#" id = "toggleProject" class="btn btn-default btn"><i class="fa fa-users"></i>
						<a href="#" id = "toggleTeam" class="btn btn-default btn"><i class="fa fa-briefcase"></i></a>
					</div>

					<div id="projectView">

						<div id="projectGridView">

							<div class="btn-group"> <!-- SORT/LEGEND -->

								<button type="button" class="btn btn-default">All</button>
								<button type="button" class="btn bg-silver">Draft</button>
								<button type="button" class="btn btn-info">Parked</button>
								<button type="button" class="btn btn-warning">Planned</button>
								<button type="button" class="btn btn-success">Ongoing</button>
								<button type="button" class="btn btn-danger">Delayed</button>

							</div>

							<br><br>

							<div class="row">
								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a href="<?php echo base_url("index.php/controller/newProject"); ?>">
									<div class="small-box bg-blue">
										<div class="inner">
											<h2>Create</h2>
											<p>New<br>Project</p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-plus"></i>
										</div>

										<!-- <div class="progress">
											<div class="progress-bar" style="width: 70%"></div>
										</div> -->
									</div>
								</a>
								</div>
								<!-- ./col -->

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "">
									<div class="small-box bg-orange">
										<div class="inner">

											<h2>Project Title</h2>

											<form action = 'projectGantt'  method="POST">
											</form>

											<p>Completed<br>March 21, 1996</p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-browsers"></i>
										</div>
									</div>
								</a>
								</div>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "">
									<div class="small-box bg-black">
										<div class="inner">

											<h2>Project Title Here</h2>

											<form action = 'projectGantt'  method="POST">
											</form>

											<p></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-plus"></i>
										</div>
									</div>
								</a>
								</div>
						</div>

						<hr style="height:1px; background-color:black">

						<div class="row">
							<?php foreach ($completedProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box bg-teal">
										<div class="inner">

											<h2>100%</h2>

											<form action = 'projectGantt'  method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Archiving in <?php echo $value['datediff'] +1;?> day/s</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-checkmark"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

							<?php foreach ($delayedProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box bg-red">
										<div class="inner">

											<h2>
												<?php
													foreach ($delayedProjectProgress as $row)
													{
														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
														{
															echo $row['projectProgress'];
														}
													}
												?>%</h2>

											<form action = 'projectGantt'  method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i><?php echo $value['datediff'];?> day/s delayed</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-alert-circled"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

							<?php foreach ($ongoingProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box bg-green">
										<div class="inner">

											<h2>
												<?php
													foreach ($ongoingProjectProgress as $row)
													{
														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
														{
															echo $row['projectProgress'];
														}
													}
												?>%</h2>

											<form action = 'projectGantt'  method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i><?php echo $value['datediff'] +1;?> day/s remaining</i></p>
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

											<form action = 'projectGantt' method="POST">
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

							<?php foreach ($parkedProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box btn-info">
										<div class="inner">

											<h2>
												<?php
													foreach ($parkedProjectProgress as $row)
													{
														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
														{
															echo $row['projectProgress'];
														}
													}
												?>%</h2>

											<form action = 'projectGantt' method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Parked</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-clock"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

							<?php foreach ($draftedProjects as $row):?>
								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "<?php echo $row['PROJECTID']; ?>">
										<div class="small-box bg-green">
										<div class="inner">
											<h2><?php echo $row['PROJECTTITLE']; ?></h2>

											<form action = 'projectGantt' method="POST">
											</form>

											<?php //Compute for days remaining
											$startdate = date_create($row['PROJECTSTARTDATE']);
											?>
											<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Draft</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-clock"></i>
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
							<div id="projectListView">
								<div class="box">
									<div class="box-header" style="display:inline-block">
										<h3 class="box-title">
											<a href="<?php echo base_url("index.php/controller/newProject"); ?>">
												<button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Create Project</button>
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

												<?php foreach ($completedProjects as $key=> $value):?>

													<?php // to fix date format
													$completedStart = date_create($value['PROJECTSTARTDATE']);
													$completedEnd = date_create($value['PROJECTENDDATE']);
													?>

												<tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

													<form action = 'projectGantt' method="POST">
													</form>

													<td class="bg-blue"></td>
													<td><?php echo $value['PROJECTTITLE']; ?></td>
													<td><?php echo date_format($completedStart, "M d, Y");?></td>
													<td><?php echo date_format($completedEnd, "M d, Y");?></td>
													<td>100%</td>
													<td>Complete</td>
												</tr>
											<?php endforeach;?>

												<?php foreach ($delayedProjects as $key=> $value):?>

													<?php // to fix date format
													$delayedStart = date_create($value['PROJECTSTARTDATE']);
													$delayedEnd = date_create($value['PROJECTENDDATE']);
													?>

												<tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

													<form action = 'projectGantt' method="POST">
													</form>

													<td class="bg-red"></td>
													<td><?php echo $value['PROJECTTITLE']; ?></td>
													<td><?php echo date_format($delayedStart, "M d, Y");?></td>
													<td><?php echo date_format($delayedEnd, "M d, Y");?></td>
													<td>
														<?php
															foreach ($delayedProjectProgress as $row)
															{
																if ($value['PROJECTID'] == $row['projects_PROJECTID'])
																{
																	echo $row['projectProgress'];
																}
															}
														?>%</td>
													<td><?php echo "Delayed"; ?></td>
												</tr>
											<?php endforeach;?>

												<?php foreach ($ongoingProjects as $key=> $value):?>

													<?php // to fix date format
													$ongoingStart = date_create($value['PROJECTSTARTDATE']);
													$ongoingEnd = date_create($value['PROJECTENDDATE']);
													?>

												<tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

													<form action = 'projectGantt' method="POST">
													</form>

													<td class="bg-green"></td>
													<td><?php echo $value['PROJECTTITLE']; ?></td>
													<td><?php echo date_format($ongoingStart, "M d, Y");?></td>
													<td><?php echo date_format($ongoingEnd, "M d, Y");?></td>
													<td>
														<?php
															foreach ($ongoingProjectProgress as $row)
															{
																if ($value['PROJECTID'] == $row['projects_PROJECTID'])
																{
																	echo $row['projectProgress'];
																}
															}
														?>%</td>
														<td><?php echo $value['PROJECTSTATUS']; ?></td>
												</tr>
											<?php endforeach;?>


											<?php foreach ($plannedProjects as $row):?>

												<?php // to fix date format
												$plannedStart = date_create($row['PROJECTSTARTDATE']);
												$plannedEnd = date_create($row['PROJECTENDDATE']);
												?>

											<tr class="project" data-id = "<?php echo $row['PROJECTID']; ?>">

												<form action = 'projectGantt' method="POST">
												</form>

												<td class="bg-yellow"></td>
												<td><?php echo $row['PROJECTTITLE']; ?></td>
												<td><?php echo date_format($plannedStart, "M d, Y");?></td>
												<td><?php echo date_format($plannedEnd, "M d, Y");?></td>
												<td>0.00%</td>
												<td><?php echo $row['PROJECTSTATUS']; ?></td>
											</tr>
										<?php endforeach;?>

										<?php foreach ($parkedProjects as $key=> $value):?>

											<?php // to fix date format
											$parkedStart = date_create($value['PROJECTSTARTDATE']);
											$parkedEnd = date_create($value['PROJECTENDDATE']);
											?>

										<tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

											<form action = 'projectGantt' method="POST">
											</form>

											<td class="bg-aqua"></td>
											<td><?php echo $value['PROJECTTITLE']; ?></td>
											<td><?php echo date_format($parkedStart, "M d, Y");?></td>
											<td><?php echo date_format($parkedEnd, "M d, Y");?></td>
											<td>
												<?php
													foreach ($parkedProjectProgress as $row)
													{
														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
														{
															echo $row['projectProgress'];
														}
													}
												?>%</td>
												<td><?php echo "Parked"; ?></td>
										</tr>
									<?php endforeach;?>

									<?php foreach ($draftedProjects as $key=> $value):?>

										<?php // to fix date format
										$draftedStart = date_create($value['PROJECTSTARTDATE']);
										$draftedEnd = date_create($value['PROJECTENDDATE']);
										?>

									<tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

										<form action = 'projectGantt' method="POST">
										</form>

										<td class="bg-silver"></td>
										<td><?php echo $value['PROJECTTITLE']; ?></td>
										<td><?php echo date_format($draftedStart, "M d, Y");?></td>
										<td><?php echo date_format($draftedEnd, "M d, Y");?></td>
										<td>0.00%</td>
										<td><?php echo "Draft"; ?></td>
									</tr>
								<?php endforeach;?>

											</tbody>
										</table>
									</div>
									<!-- /.box-body -->
								</div>
							</div>

					<!-- /.content -->

					</div>
					<!-- END PROJECT VIEW -->

					<div id="teamView">

					</div>
					<!-- END TEAM VIEW -->
				</section>
				</div>

			<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->
		<!-- ./wrapper -->

		<script>
			$("#projectListView").hide();
			$("#toggleGrid").hide();
			$("#toggleProject").hide();
			$("#teamGridView").hide();
			$("#teamListView").hide();
			$("#myProjects").addClass("active");
			// $("#projects").addClass("active");

			// IF USING POST METHOD FOR PROJECT ID
			$(document).on("click", ".project", function() {
				var $id = $(this).attr('data-id');
				$("form").attr("name", "formSubmit");
				$("form").append("<input type='hidden' name='project_ID' value= " + $id + ">");
				$("form").submit();
				});

			$("#toggleDisplayView").click(function(){
				if($("#projectGridView").css("display") == "block")
				{
					$("#projectGridView").hide();
					$("#projectListView").show();
					$("#teamGridView").hide();
					$("#teamListView").show();
					$("#toggleGrid").show();
					$("#toggleList").hide();
				}
				else
				{
					$("#projectListView").hide();
					$("#projectGridView").show();
					$("#teamGridView").show();
					$("#teamListView").hide();
					$("#toggleGrid").hide();
					$("#toggleList").show();
				}
			});

			$("#toggleTypeView").click(function(){
				if($("#teamGridView").css("display") == "block")
				{
					$("#projectGridView").hide();
					$("#projectListView").show();
					$("#teamGridView").hide();
					$("#teamListView").show();
					$("#toggleGrid").show();
					$("#toggleList").hide();
				}
				else
				{
					$("#projectListView").hide();
					$("#projectGridView").show();
					$("#teamGridView").show();
					$("#teamListView").hide();
					$("#toggleGrid").hide();
					$("#toggleList").show();
				}
			});

			$(function () {
		    $('#projectList').DataTable({
		      'paging'      : false,
		      'lengthChange': false,
		      'searching'   : false,
		      'ordering'    : false,
		      'info'        : false,
		      'autoWidth'   : false
		    });
				$('#projectList').DataTable().columns(-1).order('asc').draw();
		  })
		</script>
	</body>
</html>
