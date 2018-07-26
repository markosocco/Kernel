<html>
	<head>
		<title>Kernel - Tasks To Do</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/notificationsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Tasks To Do
					<small>What do I need to get done?</small>
				</h1>
			</section>

      <!-- Main content -->
			<section class="content container-fluid">
        <!-- START HERE -->
				<a id = "viewAll" class = "pull-right">View All Tasks >></a> <br><br>

				<div id = "filteredTasks">

					<div class="row">
						<!-- TO DO -->

						<?php if ($tasks != NULL): ?>
						<div class="col-md-10">
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
												<th>Task</th>
												<th>Project</th>
												<th class="text-center">End Date</th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($tasks as $task):?>
													<?php $enddate = date_create($task['TASKENDDATE']);?>

													<tr class="viewProject" data-id="<?php echo $task['PROJECTID'] ;?>">
														<td><?php echo $task['TASKTITLE'];?></td>
														<td><?php echo $task['PROJECTTITLE'];?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
														<td align="center">
															<!-- <button type="button" class="btn btn-primary btn-sm delegateBtn"
															data-toggle="modal" data-target="#modal-delegate"
															data-id="<?php echo $task['TASKID'];?>"
															data-title="<?php echo $task['TASKTITLE'];?>"
															data-start="<?php echo $task['TASKSTARTDATE'];?>"
															data-end="<?php echo $task['TASKENDDATE'];?>">
																<i class="fa fa-users"></i> -->
															</button>
															<button type="button" class="btn btn-warning btn-sm editBtn"
															data-id="<?php echo $task['TASKID'];?>">
																<i class="fa fa-warning"></i>
															</button>
															<button type="button" class="btn btn-success btn-sm delegateBtn"
															data-toggle="modal" data-target="#modal-delegate"
															data-id="<?php echo $task['TASKID'];?>"
															data-title="<?php echo $task['TASKTITLE'];?>"
															data-start="<?php echo $task['TASKSTARTDATE'];?>"
															data-end="<?php echo $task['TASKENDDATE'];?>">
																<i class="fa fa-check"></i>
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
						<div class="col-md-10">
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
					<div class="col-md-2">
						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Total: <br><br><b><?php echo count($tasks);?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed: <br><br><span style='color:red'><b><?php echo count($tasks);?></b></span></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->

					<div class="row">

						<!-- RECENTLY ADDED -->

						<?php if ($tasks != NULL): ?>
						<div class="col-md-10">
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
												<th>Task</th>
												<th>Project</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($tasks as $task):?>
													<?php $startdate = date_create($task['TASKSTARTDATE']);?>

													<tr class="viewProject" data-id="<?php echo $task['PROJECTID'] ;?>">
														<td><?php echo $task['TASKTITLE'];?></td>
														<td><?php echo $task['PROJECTTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center">
															<!-- <button type="button" class="btn btn-primary btn-sm delegateBtn"
															data-toggle="modal" data-target="#modal-delegate"
															data-id="<?php echo $task['TASKID'];?>"
															data-title="<?php echo $task['TASKTITLE'];?>"
															data-start="<?php echo $task['TASKSTARTDATE'];?>"
															data-end="<?php echo $task['TASKENDDATE'];?>">
																<i class="fa fa-users"></i>
															</button> -->
															<button type="button" class="btn btn-warning btn-sm editBtn"
															data-id="<?php echo $task['TASKID'];?>">
																<i class="fa fa-warning"></i>
															</button>
															<!-- <button type="button" class="btn btn-success btn-sm delegateBtn"
															data-toggle="modal" data-target="#modal-delegate"
															data-id="<?php echo $task['TASKID'];?>"
															data-title="<?php echo $task['TASKTITLE'];?>"
															data-start="<?php echo $task['TASKSTARTDATE'];?>"
															data-end="<?php echo $task['TASKENDDATE'];?>">
																<i class="fa fa-check"></i>
															</button> -->
														</td>
													</tr>
												<?php endforeach;?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Total: <br><br><b><?php echo count($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>
					<?php endif;?>

					</div> <!-- CLOSING ROW -->
				</div>

				<div id = "allTasks">
					<!-- ALL TASKS -->
					<div class = 'row'>
						<div class="col-md-2 pull-right">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Total: <br><br><b><?php echo count($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-right">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed: <br><br><b><?php echo count($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>
					</div>


					<?php if ($tasks != NULL): ?>
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
												<th>Task</th>
												<th>Project</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">Target End Date</th>
												<th class="text-center">Period <small>(day/s)</small></th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($tasks as $task):?>
													<?php
													$startdate = date_create($task['TASKSTARTDATE']);
													$enddate = date_create($task['TASKENDDATE']);
													$diff = date_diff($enddate, $startdate);
													$period = $diff->format("%a")+1;
													?>

													<tr class="viewProject" data-id="<?php echo $task['TASKID'] ;?>">
														<td><?php echo $task['TASKTITLE'];?></td>
														<td><?php echo $task['PROJECTTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
														<td align = "center"><?php echo $period;?></td>
														<td align="center">
															<button type="button" class="btn btn-warning btn-sm editBtn"
															data-id="<?php echo $task['TASKID'];?>">
																<i class="fa fa-warning"></i>
															</button>
															<button type="button" class="btn btn-success btn-sm delegateBtn"
															data-toggle="modal" data-target="#modal-delegate"
															data-id="<?php echo $task['TASKID'];?>"
															data-title="<?php echo $task['TASKTITLE'];?>"
															data-start="<?php echo $task['TASKSTARTDATE'];?>"
															data-end="<?php echo $task['TASKENDDATE'];?>">
																<i class="fa fa-check"></i>
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
      $("#taskTodo").addClass("active");
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

			$("body").on("click", ".editBtn", function(e) {
				alert("Forward to Edit Project");
				// var $projectID = $(this).attr('data-id');
				// $("#viewProject").attr("name", "formSubmit");
				// $("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				// $("#viewProject").submit();
			});
		</script>
	</body>
</html>
