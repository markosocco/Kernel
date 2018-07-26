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

				<button id = "viewAll" class="btn btn-default pull-right"><i class="fa fa-eye"></i></button>

				<br><br>

				<div id = "filteredTasks">

					<div class="row">
						<!-- ONGOING-->

						<?php $delayedTasks=0;?>
						<?php if ($uniqueOngoingACItasks != NULL): ?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Ongoing Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin" id="ongoingTaskTable">
											<thead>
											<tr>
												<th width="1%"></th>
												<th width="4%" class="text-center">Role</th>
												<th width="20%">Responsible</th>
												<th width="27.5%">Project</th>
												<th width="27.5%">Task</th>
												<!-- <th class="text-center">Start Date</th> -->
												<th width="12%" class="text-center">End Date</th>
												<!-- <th class="text-center">Status</th> -->
												<th width="8%" class="text-center">Days Delayed</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($uniqueOngoingACItasks as $uniqueOngoingACItask):?>
													<?php
													if($uniqueOngoingACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
														$endDate = $uniqueOngoingACItask['TASKENDDATE'];
													else
														$endDate = $uniqueOngoingACItask['TASKADJUSTEDENDDATE'];

													if($uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
														$startDate = $uniqueOngoingACItask['TASKSTARTDATE'];
													else
														$startDate = $uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'];

													if($uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'] != null && $uniqueOngoingACItask['TASKADJUSTEDENDDATE'] != null)
														$taskDuration = $uniqueOngoingACItask['adjustedTaskDuration2'];
													elseif($uniqueOngoingACItask['TASKSTARTDATE'] != null && $uniqueOngoingACItask['TASKADJUSTEDENDDATE'] != null)
														$taskDuration = $uniqueOngoingACItask['adjustedTaskDuration1'];
													else
														$taskDuration = $uniqueOngoingACItask['initialTaskDuration'];

													$startdate = date_create($startDate);
													$enddate = date_create($endDate);
													$curdate = date_create(date('Y-m-d'));
													$diff = date_diff($startdate, $curdate);
													$delay = $diff->format("%a")+1;
													?>

													<tr class="viewProject clickable" data-id="<?php echo $uniqueOngoingACItask['PROJECTID'] ;?>">

														<?php
														$role="";
														foreach($allOngoingACItasks as $currTask)
														{
															if($uniqueOngoingACItask['TASKID'] == $currTask['TASKID'])
															{
																switch($currTask['ROLE'])
																{
																	case '2': $type = "A"; break;
																	case '3': $type = "C"; break;
																	case '4': $type = "I"; break;
																}
																$role .= $type;
															}
														}
														if($role == null)
														{
															switch($uniqueOngoingACItask['ROLE'])
															{
																case '2': $role = "A"; break;
																case '3': $role = "C"; break;
																case '4': $role = "I"; break;
															}
														}
														?>
														<?php if($taskDuration >= $delay):?>
															<td class="bg-green"></td>
														<?php else:?>
															<td class="bg-red"></td>
															<?php $delayedTasks++;?>
														<?php endif;?>
														<td align="center"><?php echo $role;?></td>
														<td><?php echo $uniqueOngoingACItask['FIRSTNAME'];?> <?php echo $uniqueOngoingACItask['LASTNAME'];?></td>
														<td><?php echo $uniqueOngoingACItask['PROJECTTITLE'];?></td>
														<td><?php echo $uniqueOngoingACItask['TASKTITLE'];?></td>
														<!-- <td align="center"><?php echo date_format($startdate, 'M d, Y');?></td> -->
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
														<?php if($delay-$taskDuration <= 0):?>
															<td align="center">0</td>
														<?php else:?>
															<td align="center" style="color:red"><?php echo $delay - $taskDuration;?></td>
														<?php endif;?>
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
									<h3 class="box-title">Ongoing Tasks</h3>
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
									<h4 align="center"> Total <br><br><b><?php echo count($uniqueOngoingACItasks);?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed <br><br><span style='color:red'><b><?php echo $delayedTasks;?></b></span></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->

					<div class="row">
						<!-- COMPLETED-->

						<?php $delayedCompletedTasks=0;?>
						<?php if ($uniqueCompletedACItasks != NULL): ?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Completed Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin" id="completedTaskTable">
											<thead>
											<tr>
												<th width="1%"></th>
												<th width="4%" class="text-center">Role</th>
												<th width="20%">Responsible</th>
												<th width="27.5%">Project</th>
												<th width="27.5%">Task</th>
												<!-- <th class="text-center">Start Date</th> -->
												<th width="12%" class="text-center">Actual<br>End Date</th>
												<!-- <th class="text-center">Status</th> -->
												<th width="8%" class="text-center">Days Delayed</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($uniqueCompletedACItasks as $uniqueCompletedACItask):?>
													<?php
													if($uniqueCompletedACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
														$endDate = $uniqueCompletedACItask['TASKENDDATE'];
													else
														$endDate = $uniqueCompletedACItask['TASKADJUSTEDENDDATE'];

													if($uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
														$startDate = $uniqueCompletedACItask['TASKSTARTDATE'];
													else
														$startDate = $uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'];

													if($uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'] != null && $uniqueCompletedACItask['TASKADJUSTEDENDDATE'] != null)
														$taskDuration = $uniqueCompletedACItask['adjustedTaskDuration2'];
													elseif($uniqueCompletedACItask['TASKSTARTDATE'] != null && $uniqueCompletedACItask['TASKADJUSTEDENDDATE'] != null)
														$taskDuration = $uniqueCompletedACItask['adjustedTaskDuration1'];
													else
														$taskDuration = $uniqueCompletedACItask['initialTaskDuration'];

													$actualEndDate = date_create($uniqueCompletedACItask['TASKACTUALENDDATE']);
													$start = date_create($startDate);
													$end = date_create($endDate);
													$curdate = date_create(date('Y-m-d'));
													$diff = date_diff($start, $curdate);
													$delay = $diff->format("%a")+1;
													?>

													<tr class="viewProject clickable" data-id="<?php echo $uniqueCompletedACItask['PROJECTID'] ;?>">

														<?php
														$role="";
														foreach($allCompletedACItasks as $currTask)
														{
															if($uniqueCompletedACItask['TASKID'] == $currTask['TASKID'])
															{
																switch($currTask['ROLE'])
																{
																	case '2': $type = "A"; break;
																	case '3': $type = "C"; break;
																	case '4': $type = "I"; break;
																}
																$role .= $type;
															}
														}
														if($role == null)
														{
															switch($uniqueCompletedACItask['ROLE'])
															{
																case '2': $role = "A"; break;
																case '3': $role = "C"; break;
																case '4': $role = "I"; break;
															}
														}
														?>
														<td class="bg-teal"></td>
														<td align="center"><?php echo $role;?></td>
														<td><?php echo $uniqueCompletedACItask['FIRSTNAME'];?> <?php echo $uniqueCompletedACItask['LASTNAME'];?></td>
														<td><?php echo $uniqueCompletedACItask['PROJECTTITLE'];?></td>
														<td><?php echo $uniqueCompletedACItask['TASKTITLE'];?></td>
														<!-- <td align="center"><?php echo date_format($start, 'M d, Y');?></td>
														<td align="center"><?php echo date_format($end, 'M d, Y');?></td> -->
														<td align="center"><?php echo date_format($actualEndDate, 'M d, Y');?></td>
														<?php if($delay-$taskDuration <= 0):?>
															<td align="center">0</td>
														<?php else:?>
															<td align="center"><?php echo $delay - $taskDuration;?></td>
														<?php endif;?>
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
									<h3 class="box-title">Completed Tasks</h3>
								</div>
								<div class="box-body">
									<h4 align="center">You have no completed tasks</h4>
								</div>
							</div>
						</div>
					<?php endif;?>
					<div class="col-md-2">
						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Total <br><br><b><?php echo count($uniqueCompletedACItasks);?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed <br><br><span style='color:red'><b><?php echo $delayedCompletedTasks;?></b></span></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->
				</div>

				<div id = "allTasks">

					<div class = 'row'>
						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Total <br><br><b><?php echo count($uniqueCompletedACItasks)+count($uniqueOngoingACItasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Completed <br><br><b><?php echo count($uniqueCompletedACItasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing <br><br><b><?php echo count($uniqueOngoingACItasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed <br><br><span style='color:red'><b><?php echo $delayedTasks;?></b></span></h4>
									</div>
								</div>
							</div>
						</div>


					</div>

					<div class="row">
						<!-- ALL-->

						<?php if ($uniqueOngoingACItasks != NULL || $uniqueCompletedACItasks != NULL): ?>
						<div class="col-md-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">All Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin" id="allTaskTable">
											<thead>
											<tr>
												<th width="1%"></th>
												<th width="4%" class="text-center">Role</th>
												<th width="17%">Responsible</th>
												<th width="20%">Project</th>
												<th width="20%">Task</th>
												<th width="10%" class="text-center">Start Date</th>
												<th width="10%" class="text-center">End Date</th>
												<th width="10%" class="text-center">Actual<br>End Date</th>
												<th width="8%" class="text-center">Days Delayed</th>
											</tr>
											</thead>
											<tbody>

												<?php foreach($uniqueOngoingACItasks as $uniqueOngoingACItask):?>
													<?php
													if($uniqueOngoingACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
														$endDate = $uniqueOngoingACItask['TASKENDDATE'];
													else
														$endDate = $uniqueOngoingACItask['TASKADJUSTEDENDDATE'];

													if($uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
														$startDate = $uniqueOngoingACItask['TASKSTARTDATE'];
													else
														$startDate = $uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'];

													if($uniqueOngoingACItask['TASKADJUSTEDSTARTDATE'] != null && $uniqueOngoingACItask['TASKADJUSTEDENDDATE'] != null)
														$taskDuration = $uniqueOngoingACItask['adjustedTaskDuration2'];
													elseif($uniqueOngoingACItask['TASKSTARTDATE'] != null && $uniqueOngoingACItask['TASKADJUSTEDENDDATE'] != null)
														$taskDuration = $uniqueOngoingACItask['adjustedTaskDuration1'];
													else
														$taskDuration = $uniqueOngoingACItask['initialTaskDuration'];

													$startdate = date_create($startDate);
													$enddate = date_create($endDate);
													$curdate = date_create(date('Y-m-d'));
													$diff = date_diff($startdate, $curdate);
													$delay = $diff->format("%a")+1;
													?>

													<tr class="viewProject" data-id="<?php echo $uniqueOngoingACItask['PROJECTID'] ;?>">

														<?php
														$role="";
														foreach($allOngoingACItasks as $currTask)
														{
															if($uniqueOngoingACItask['TASKID'] == $currTask['TASKID'])
															{
																switch($currTask['ROLE'])
																{
																	case '2': $type = "A"; break;
																	case '3': $type = "C"; break;
																	case '4': $type = "I"; break;
																}
																$role .= $type;
															}
														}
														if($role == null)
														{
															switch($uniqueOngoingACItask['ROLE'])
															{
																case '2': $role = "A"; break;
																case '3': $role = "C"; break;
																case '4': $role = "I"; break;
															}
														}
														?>
														<?php if($taskDuration >= $delay):?>
															<td class="bg-green"></td>
														<?php else:?>
															<td class="bg-red"></td>
															<?php $delayedTasks++;?>
														<?php endif;?>
														<td align="center"><?php echo $role;?></td>
														<td><?php echo $uniqueOngoingACItask['FIRSTNAME'];?> <?php echo $uniqueOngoingACItask['LASTNAME'];?></td>
														<td><?php echo $uniqueOngoingACItask['PROJECTTITLE'];?></td>
														<td><?php echo $uniqueOngoingACItask['TASKTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
														<td align="center">-</td>
														<?php if($delay-$taskDuration <= 0):?>
															<td align="center">0</td>
														<?php else:?>
															<td align="center" style="color:red"><?php echo $delay - $taskDuration;?></td>
														<?php endif;?>
													</tr>
												<?php endforeach;?>

												<?php foreach($uniqueCompletedACItasks as $uniqueCompletedACItask):?>
													<?php
													if($uniqueCompletedACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
														$endDate = $uniqueCompletedACItask['TASKENDDATE'];
													else
														$endDate = $uniqueCompletedACItask['TASKADJUSTEDENDDATE'];

													if($uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
														$startDate = $uniqueCompletedACItask['TASKSTARTDATE'];
													else
														$startDate = $uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'];

													if($uniqueCompletedACItask['TASKADJUSTEDSTARTDATE'] != null && $uniqueCompletedACItask['TASKADJUSTEDENDDATE'] != null)
														$taskDuration = $uniqueCompletedACItask['adjustedTaskDuration2'];
													elseif($uniqueCompletedACItask['TASKSTARTDATE'] != null && $uniqueCompletedACItask['TASKADJUSTEDENDDATE'] != null)
														$taskDuration = $uniqueCompletedACItask['adjustedTaskDuration1'];
													else
														$taskDuration = $uniqueCompletedACItask['initialTaskDuration'];

													$startdate = date_create($startDate);
													$enddate = date_create($endDate);
													$actualEndDate = date_create($uniqueCompletedACItask['TASKACTUALENDDATE']);
													$curdate = date_create(date('Y-m-d'));
													$diff = date_diff($startdate, $curdate);
													$delay = $diff->format("%a")+1;
													?>

													<tr class="viewProject" data-id="<?php echo $uniqueCompletedACItask['PROJECTID'] ;?>">

														<?php
														$role="";
														foreach($allCompletedACItasks as $currTask)
														{
															if($uniqueCompletedACItask['TASKID'] == $currTask['TASKID'])
															{
																switch($currTask['ROLE'])
																{
																	case '2': $type = "A"; break;
																	case '3': $type = "C"; break;
																	case '4': $type = "I"; break;
																}
																$role .= $type;
															}
														}
														if($role == null)
														{
															switch($uniqueCompletedACItask['ROLE'])
															{
																case '2': $role = "A"; break;
																case '3': $role = "C"; break;
																case '4': $role = "I"; break;
															}
														}
														?>
														<td class="bg-teal"></td>
														<td align="center"><?php echo $role;?></td>
														<td><?php echo $uniqueCompletedACItask['FIRSTNAME'];?> <?php echo $uniqueCompletedACItask['LASTNAME'];?></td>
														<td><?php echo $uniqueCompletedACItask['PROJECTTITLE'];?></td>
														<td><?php echo $uniqueCompletedACItask['TASKTITLE'];?></td>
														<td align="center"><?php echo date_format($startdate, 'M d, Y');?></td>
														<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
														<td align="center"><?php echo date_format($actualEndDate, 'M d, Y');?></td>

														<?php if($taskDuration > $delay):?>
														<?php else:?>
															<?php $delayedCompletedTasks++;?>
														<?php endif;?>

														<?php if($delay-$taskDuration <= 0):?>
															<td align="center">0</td>
														<?php else:?>
															<td align="center"><?php echo $delay - $taskDuration;?></td>
														<?php endif;?>
													</tr>
												<?php endforeach;?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					<?php endif;?>




					</div> <!-- All tasks closing row -->

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
					$("#viewAll").html("<i class='fa fa-eye'></i>");
				else
					$("#viewAll").html("<i class='fa fa-eye-slash'></i>");
			});

			$(document).on("click", ".viewProject", function() {
				var $projectID = $(this).attr('data-id');
				$("#viewProject").attr("name", "formSubmit");
				$("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				$("#viewProject").submit();
			});

			$('#ongoingTaskTable').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false
			});

			$('#allTaskTable').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false
			});

			$('#completedTaskTable').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false
			});
		</script>
	</body>
</html>
