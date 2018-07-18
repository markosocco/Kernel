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
					<div id = "toggleTypeView" class="pull-right">
						<a href="#" id = "toggleList" class="btn btn-default btn"><i class="fa fa-th-list"></i>
						<a href="#" id = "toggleGrid" class="btn btn-default btn"><i class="fa fa-th-large"></i></a>
					</div>

					<div id="teamGridView">

						<div class="btn-group"> <!-- SORT/LEGEND -->

							<button type="button" class="btn btn-primary">All</button>
							<button type="button" class="btn btn.bg-white">Draft</button>
							<button type="button" class="btn btn-warning">Planned</button>
							<button type="button" class="btn btn-success">Ongoing</button>
							<button type="button" class="btn btn-danger">Delayed</button>
							<button type="button" class="btn btn-default">Parked</button>

						</div>

						<!-- LIST AND GRID TOGGLE -->
						<!-- <div id = "toggleTypeView" class="pull-right" style="margin-top:10px"> -->

						<br><br>

						<div class="row">

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

											<form action = 'teamGantt'  method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i><?php echo $value['datediff'];?> day/s delayed</i></p>
										</div>
										<div class="icon">
											<i class="ion ion-beaker"></i>
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

											<form action = 'teamGantt'  method="POST">
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

							<?php foreach ($parkedProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box btn-default">
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

											<form action = 'teamGantt' method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Parked</i></p>
										</div>
										<div class="icon">
											<i class="ion ion-beaker"></i>
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
									<div class="small-box btn.bg-white">
										<div class="inner">
											<h2><?php echo $row['PROJECTTITLE']; ?></h2>

											<form action = 'teamGantt' method="POST">
											</form>

											<?php //Compute for days remaining
											$startdate = date_create($row['PROJECTSTARTDATE']);
											?>
											<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Draft</i></p>
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

						<!-- LIST VIEW -->
						<br><br>

						<div id="teamListView">
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

											<?php foreach ($delayedProjects as $key=> $value):?>

												<?php // to fix date format
												$delayedStart = date_create($value['PROJECTSTARTDATE']);
												$delayedEnd = date_create($value['PROJECTENDDATE']);
												?>

											<tr data-id = "<?php echo $value['PROJECTID']; ?>">

												<form action = 'teamGantt' method="POST">
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

										<tr data-id = "<?php echo $value['PROJECTID']; ?>">

											<form action = 'teamGantt' method="POST">
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

										<form action = 'teamGantt' method="POST">
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

									<tr data-id = "<?php echo $value['PROJECTID']; ?>">

										<form action = 'teamGantt' method="POST">
										</form>

										<td class="bg-blue"></td>
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

								<tr data-id = "<?php echo $value['PROJECTID']; ?>">

									<form action = 'teamGantt' method="POST">
									</form>

									<td class="bg-blue"></td>
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

					</section>
				</div>

				<!-- /.content -->

			<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->
		<!-- ./wrapper -->

		<script>
			$("#teamListView").hide();
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

			$("#toggleTypeView").click(function(){
				if($("#teamGridView").css("display") == "block")
				{
					$("#teamGridView").hide();
					$("#teamListView").show();
					$("#toggleGrid").show();
					$("#toggleList").hide();
				}
				else
				{
					$("#teamListView").hide();
					$("#teamGridView").show();
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
