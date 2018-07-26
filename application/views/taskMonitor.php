<html>
	<head>
		<title>Kernel - Monitor Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/notificationsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Monitor Tasks
					<small>What should I keep my eye on?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
        <!-- START HERE -->

				<div id = "divGridListMyProjects">
          <a href="#" id = "buttonListProjects" class="btn btn-default btn pull-left"><i class="fa fa-th-list"></i>
          <a href="#" id = "buttonGridProjects" class="btn btn-default btn pull-left"><i class="fa fa-th-large"></i></a>
					<a id = "viewAll" class = "pull-right">View All Tasks >></a> <br><br>
        </div>


				<div id = "filteredTasks">

					<div class="row">
						<!-- COMPLETED -->

						<?php if ($ACItasks != NULL): ?>
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
												<th>Role</th>
												<th>Responsible</th>
												<th>Project</th>
												<th>Task</th>
												<th class="text-center">Actual End Date</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($ACItasks as $ACItask):?>
													<?php
													$startdate = date_create($ACItask['TASKSTARTDATE']);
													$enddate = date_create($ACItask['TASKENDDATE']);
													$diff = date_diff($enddate, $startdate);
													$period = $diff->format("%a")+1;
													?>

													<tr class="viewProject" data-id="<?php echo $ACItask['PROJECTID'] ;?>">
														<?php
														switch($ACItask['ROLE'])
														{
															case '2': $role = "A"; break;
															case '3': $role = "C"; break;
															case '4': $role = "I"; break;
														}
														;?>
														<td><?php echo $role;?></td>
														<td><?php echo $ACItask['FIRSTNAME'];?> <?php echo $ACItask['LASTNAME'];?></td>
														<td><?php echo $ACItask['PROJECTTITLE'];?></td>
														<td><?php echo $ACItask['TASKTITLE'];?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
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
									<h4 align="center">You have no Tasks due in 3 days</h4>
								</div>
							</div>
						</div>
					<?php endif;?>
					<div class="col-md-2">
						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Total: <br><br><b><?php echo count($ACItasks);?></b></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->

					<div class="row">
						<!-- ONGOING-->

						<?php if ($ACItasks != NULL): ?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Ongoing Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin">
											<thead>
											<tr>
												<th>Role</th>
												<th>Responsible</th>
												<th>Task</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">End Date</th>
												<th class="text-center">Period<br><small>(day/s)</small></th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($ACItasks as $ACItask):?>
													<?php
													$startdate = date_create($ACItask['TASKSTARTDATE']);
													$enddate = date_create($ACItask['TASKENDDATE']);
													$diff = date_diff($enddate, $startdate);
													$period = $diff->format("%a")+1;
													?>

													<tr class="viewProject" data-id="<?php echo $ACItask['PROJECTID'] ;?>">
														<?php
														switch($ACItask['ROLE'])
														{
															case '2': $role = "A"; break;
															case '3': $role = "C"; break;
															case '4': $role = "I"; break;
														}
														;?>
														<td><?php echo $role;?></td>
														<td><?php echo $ACItask['FIRSTNAME'];?> <?php echo $ACItask['LASTNAME'];?></td>
														<td><?php echo $ACItask['TASKTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
														<td align="center"><?php echo $period;?></td>
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
									<h4 align="center">You have no ongoing tasks</h4>
								</div>
							</div>
						</div>
					<?php endif;?>
					<div class="col-md-2">
						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Total: <br><br><b><?php echo count($ACItasks);?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed: <br><br><span style='color:red'><b><?php echo count($ACItasks);?></b></span></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->

					<div class="row">
						<!-- COMPLETED -->

						<?php if ($ACItasks != NULL): ?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Completed Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin">
											<thead>
											<tr>
												<th>Role</th>
												<th>Responsible</th>
												<th>Project</th>
												<th>Task</th>
												<th class="text-center">Actual End Date</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($ACItasks as $ACItask):?>
													<?php
													$startdate = date_create($ACItask['TASKSTARTDATE']);
													$enddate = date_create($ACItask['TASKENDDATE']);
													$diff = date_diff($enddate, $startdate);
													$period = $diff->format("%a")+1;
													?>

													<tr class="viewProject" data-id="<?php echo $ACItask['PROJECTID'] ;?>">
														<?php
														switch($ACItask['ROLE'])
														{
															case '2': $role = "A"; break;
															case '3': $role = "C"; break;
															case '4': $role = "I"; break;
														}
														;?>
														<td><?php echo $role;?></td>
														<td><?php echo $ACItask['FIRSTNAME'];?> <?php echo $ACItask['LASTNAME'];?></td>
														<td><?php echo $ACItask['PROJECTTITLE'];?></td>
														<td><?php echo $ACItask['TASKTITLE'];?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
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
									<h4 align="center">You have no Tasks due in 3 days</h4>
								</div>
							</div>
						</div>
					<?php endif;?>
					<div class="col-md-2">
						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Total: <br><br><b><?php echo count($ACItasks);?></b></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->
				</div>

				<div id = "allTasks">

					<div class="row">
						<!-- VIEW All -->

						<?php if ($ACItasks != NULL): ?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title"><?php echo $ACItasks[3]['PROJECTTITLE'];?></h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin">
											<thead>
											<tr>
												<th>Role</th>
												<th>Responsible</th>
												<th>Task</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">End Date</th>
												<th class="text-center">Period<br><small>(day/s)</small></th>
												<th class="text-center">Status</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($ACItasks as $ACItask):?>
													<?php
													$startdate = date_create($ACItask['TASKSTARTDATE']);
													$enddate = date_create($ACItask['TASKENDDATE']);
													$diff = date_diff($enddate, $startdate);
													$period = $diff->format("%a")+1;
													?>

													<tr class="viewProject" data-id="<?php echo $ACItask['PROJECTID'] ;?>">
														<?php
														switch($ACItask['ROLE'])
														{
															case '2': $role = "A"; break;
															case '3': $role = "C"; break;
															case '4': $role = "I"; break;
														}
														;?>
														<td><?php echo $role;?></td>
														<td><?php echo $ACItask['FIRSTNAME'];?> <?php echo $ACItask['LASTNAME'];?></td>
														<td><?php echo $ACItask['TASKTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
														<td align="center"><?php echo $period;?></td>
														<td><?php echo $ACItask['TASKSTATUS'];?></td>
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
									<h4 align="center">You have no Tasks due in 3 days</h4>
								</div>
							</div>
						</div>
					<?php endif;?>
					<div class="col-md-2">
						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Total: <br><br><b><?php echo count($ACItasks);?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed: <br><br><span style='color:red'><b><?php echo count($ACItasks);?></b></span></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->
				</div>

				<form id='viewProject' action = 'projectGantt' method="POST">
					<input type ='hidden' name='monitorTasks' value='0'>
				</form>

			</section>
		</div>
		  <?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
      $("#tasks").addClass("active");
      $("#taskMonitor").addClass("active");
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
		</script>
	</body>
</html>
