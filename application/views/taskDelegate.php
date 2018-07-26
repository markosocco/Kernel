<html>
	<head>
		<title>Kernel - Delegate Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/notificationsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Delegate Tasks
					<small>What tasks are to be done by my team?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
        <!-- START HERE -->
				<a id = "viewAll" class = "pull-right">View All Tasks >></a> <br><br>

				<div id = "filteredTasks">
					<div class="row">
						<!-- TO DO -->

						<?php if ($editProjects != NULL): ?>
						<div class="col-md-6">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">To Do</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin">
											<thead>
											<tr>
												<th>Project</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($editProjects as $editProject):?>
													<?php $startdate = date_create($editProject['PROJECTSTARTDATE']);?>

													<tr class="viewProject" data-id="<?php echo $editProject['PROJECTID'] ;?>">
														<td><?php echo $editProject['PROJECTTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center">
															<button type="button" class="btn bg-teal btn-sm editBtn"
															data-id="<?php echo $editProject['PROJECTID'];?>">
																<i class="fa fa-edit"></i>
															</button>
															<button type="button" class="btn btn-primary btn-sm delegateBtn"
															data-toggle="modal" data-target="#modal-delegate"
															data-id="<?php echo $editProject['PROJECTID'];?>"
															data-title="<?php echo $editProject['PROJECTTITLE'];?>"
															data-start="<?php echo $editProject['PROJECTSTARTDATE'];?>"
															data-end="<?php echo $editProject['PROJECTENDDATE'];?>">
																<i class="fa fa-users"></i>
															</button>
														</td>
													</tr>
												<?php endforeach;?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					<?php else:?>
						<div class="col-md-6">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">To Do</h3>
								</div>
								<div class="box-body">
									<h4 align="center">You have no tasks due in 3 days</h4>
								</div>
							</div>
						</div>
					<?php endif;?>

						<!-- RECENTLY ADDED -->

						<?php if ($editProjects != NULL): ?>
						<div class="col-md-6">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Recently Added</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin">
											<thead>
											<tr>
												<th>Project</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($editProjects as $editProject):?>
													<?php $startdate = date_create($editProject['PROJECTSTARTDATE']);?>

													<tr class="viewProject" data-id="<?php echo $editProject['PROJECTID'] ;?>">
														<td><?php echo $editProject['PROJECTTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center">
															<button type="button" class="btn bg-teal btn-sm editBtn"
															data-id="<?php echo $editProject['PROJECTID'];?>">
																<i class="fa fa-edit"></i>
															</button>
															<button type="button" class="btn btn-primary btn-sm delegateBtn"
															data-toggle="modal" data-target="#modal-delegate"
															data-id="<?php echo $editProject['PROJECTID'];?>"
															data-title="<?php echo $editProject['PROJECTTITLE'];?>"
															data-start="<?php echo $editProject['PROJECTSTARTDATE'];?>"
															data-end="<?php echo $editProject['PROJECTENDDATE'];?>">
																<i class="fa fa-users"></i>
															</button>
														</td>
													</tr>
												<?php endforeach;?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					<?php endif;?>

					</div> <!-- CLOSING ROW -->
				</div>

				<div id = "allTasks">
					<!-- ALL TASKS -->

					<?php if ($editProjects != NULL): ?>
					<div class = "row">
						<div class="col-md-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">All Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin">
											<thead>
											<tr>
												<th>Project</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">Target End Date</th>
												<th class="text-center">Period <small>(day/s)</small></th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($editProjects as $editProject):?>
													<?php
													$startdate = date_create($editProject['PROJECTSTARTDATE']);
													$enddate = date_create($editProject['PROJECTENDDATE']);
													$diff = date_diff($enddate, $startdate);
													$period = $diff->format("%a")+1;
													?>

													<tr class="viewProject" data-id="<?php echo $editProject['PROJECTID'] ;?>">
														<td><?php echo $editProject['PROJECTTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
														<td align = "center"><?php echo $period;?></td>
														<td align="center">
															<button type="button" class="btn bg-teal btn-sm editBtn"
															data-id="<?php echo $editProject['PROJECTID'];?>">
																<i class="fa fa-edit"></i>
															</button>
															<button type="button" class="btn btn-primary btn-sm delegateBtn"
															data-toggle="modal" data-target="#modal-delegate"
															data-id="<?php echo $editProject['PROJECTID'];?>"
															data-title="<?php echo $editProject['PROJECTTITLE'];?>"
															data-start="<?php echo $editProject['PROJECTSTARTDATE'];?>"
															data-end="<?php echo $editProject['PROJECTENDDATE'];?>">
																<i class="fa fa-users"></i>
															</button>
														</td>
													</tr>
												<?php endforeach;?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endif;?>
				</div>

				<form id='viewProject' action = 'projectGantt' method="POST">
					<input type ='hidden' name='delegateTask' value='0'>
				</form>

			</section>
		</div>
		  <?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
			$("#tasks").addClass("active");
			$("#taskDelegate").addClass("active");
			$("#allTasks").hide();


			$(document).on("click", "#viewAll", function()
			{
				$("#allTasks").toggle();
				$("#filteredTasks").toggle();
				if($("#allTasks").css("display") == "none")
					$("#viewAll").html("View All Tasks >>");
				else
					$("#viewAll").html("Hide All Tasks >>");
			});

			$(document).on("click", ".viewProject", function() {
				var $projectID = $(this).attr('data-id');
				$("#viewProject").attr("name", "formSubmit");
				$("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				$("#viewProject").submit();
			});

			$("body").on("click", ".editBtn", function() {
				alert("Forward to Edit Project");
				// var $projectID = $(this).attr('data-id');
				// $("#viewProject").attr("name", "formSubmit");
				// $("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				// $("#viewProject").submit();
			});

		</script>
	</body>
</html>
