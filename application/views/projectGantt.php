<html>
	<head>
		<title>Kernel - <?php echo  $projectProfile['PROJECTTITLE'];?></title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectGanttStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<div style="margin-bottom:10px">

						<?php if(isset($_SESSION['dashboard'])): ?>
								<a href="<?php echo base_url("index.php/controller/dashboard"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to Dashboard</a>
						<?php elseif(isset($_SESSION['archives'])): ?>
								<a href="<?php echo base_url("index.php/controller/archives"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to Archives</a>
						<?php elseif(isset($_SESSION['changeRequest']) || isset($_SESSION['userRequest'])): ?>
								<a href="<?php echo base_url("index.php/controller/rfc"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to Change Requests</a>
						<?php elseif(isset($_SESSION['rfc'])): ?>
								<a href="<?php echo base_url("index.php/controller/rfc"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to Change Requests</a>
						<?php elseif(isset($_SESSION['myTasks'])): ?>
								<a href="<?php echo base_url("index.php/controller/myTasks"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to My Tasks</a>
						<?php elseif(isset($_SESSION['templates'])): ?>
								<a href="<?php echo base_url("index.php/controller/templates"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to Templates</a>
						<?php elseif(isset($_SESSION['monitorTasks'])): ?>
								<a href="<?php echo base_url("index.php/controller/taskMonitor"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to Monitor Tasks</a>
						<?php else: ?>
								<a href="<?php echo base_url("index.php/controller/myProjects"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to My Projects</a>
						<?php endif; ?>

					</div>

				<?php if(isset($_SESSION['rfc']) && !isset($_SESSION['userRequest'])): ?>

					<!-- RFC GANTT START -->
					<div id="rfcGantt">
						<div class="row">
								<div class="col-md-6">
									<div class="box box-danger">
										<div class="box-header">
											<h3 class="box-title">Change Request Details</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<form id = "approvalForm" action="approveDenyRFC" method="POST" style="margin-bottom:0;"
											data-request="<?php echo $changeRequest['REQUESTID'];?>"
											data-project="<?php echo $changeRequest['PROJECTID'];?>"
											data-task="<?php echo $changeRequest['TASKID'];?>"
											data-type="<?php echo $changeRequest['REQUESTTYPE'];?>"
											data-requestor="<?php echo $changeRequest['users_REQUESTEDBY'];?>">

												<?php $dateRequested = date_create($changeRequest['REQUESTEDDATE']);

												if($changeRequest['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
													$startDate = date_create($changeRequest['TASKSTARTDATE']);
												else
													$startDate = date_create($changeRequest['TASKADJUSTEDSTARTDATE']);

												if($changeRequest['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
													$endDate = date_create($changeRequest['TASKENDDATE']);
												else
													$endDate = date_create($changeRequest['TASKADJUSTEDENDDATE']);

												$newStartDate = date_create($changeRequest['NEWSTARTDATE']);
												$newEndDate = date_create($changeRequest['NEWENDDATE']);
												?>
												<?php if($changeRequest['REQUESTTYPE'] == 1)
													$type = "Change Performer";
												else
													$type = "Change Date/s";?>

												<table class="table">
													<tr>
														<td>
															<label>Date Requested</label>
															<p><?php echo date_format($dateRequested, "F d, Y");?></p>
														</td>
														<td>
															<label>Request Type</label>
															<p>
																<?php if($changeRequest['REQUESTTYPE'] == 1):?>
																	<i class="fa fa-user-times"></i>
																<?php else:?>
																	<i class="fa fa-calendar"></i>
																<?php endif;?>
																<?php echo $type;?>
															</p>
														</td>
														<td colspan='2'>
															<label>Requester</label>
															<p><?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'] ;?></p>
														</td>
													</tr>

													<tr>
														<td>
															<label>Project</label>
															<p><?php echo $changeRequest['PROJECTTITLE'];?></p>
														</td>
														<td>
															<label>Task Name</label>
															<p><?php echo $changeRequest['TASKTITLE'];?></p>
														</td>
														<td>
															<label>Start Date</label>
															<p><?php echo date_format($startDate, "F d, Y");?></p>
														</td>
														<td>
															<label>End Date</label>
															<p><?php echo date_format($endDate, "F d, Y");?></p>
														</td>
													</tr>

													<tr>
														<td colspan='4'>
															<label>Reason</label>
															<p><?php echo $changeRequest['REASON'];?></p>
														</td>
													</tr>

													<?php if($changeRequest['REQUESTTYPE'] == '2'):?>
														<tr>
															<td colspan='2'>
																<label>Requested Start Date</label>
																<?php if($changeRequest['NEWSTARTDATE'] != ""):?>
																	<p><?php echo date_format($newStartDate, "F d, Y");?></p>
																<?php else:?>
																	<p>-</p>
																<?php endif;?>
															</td>
															<td colspan='2'>
																<label>Requested End Date</label>
																<?php if($changeRequest['NEWENDDATE'] != ""):?>
																	<p><?php echo date_format($newEndDate, "F d, Y");?></p>
																<?php else:?>
																	<p>-</p>
																<?php endif;?>
															</td>
														</tr>
													<?php endif;?>

												</table>
										</div>
										<!-- /.box-body -->
										<!-- /.box-footer -->
									</div>
									<!-- /.box -->
								</div>

								<div class="col-md-6">
									<div class="box box-danger">
										<!-- /.box-header -->
										<div class="box-body">
												<div class="form-group">
													<textarea id = "remarks" name = "remarks" class="form-control" rows="5" placeholder="Enter remarks (Optional)"></textarea>
												</div>
												<button id = "denyRequest" type="button" class="btn btn-danger pull-left" style="display:block" data-toggle="modal" data-target="#modal-deny"><i class="fa fa-close"></i></button>
												<button id = "approveRequest" type="button" class="btn btn-success pull-right" style="display:block;" data-toggle="modal" data-target="#modal-approve"><i class="fa fa-check" ></i></button>
										</div>
										<!-- /.box-body -->
										<!-- /.box-footer -->
									</div>
									<!-- /.box -->
								</div>
						</div>
						<hr style="height:1px; background-color:black">
					</div>
					<!-- RFC GANTT END -->

				<!-- CONFIRM DENY MODAL -->
				<div class="modal fade" id="modal-deny">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title taskTitle"><?php echo $changeRequest['TASKTITLE'];?></h2>
								<h4 class="taskDates"><?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>
									(<?php
										if($changeRequest['TASKADJUSTEDSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
											$taskDuration = $changeRequest['adjustedTaskDuration2'];
										elseif($changeRequest['TASKSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
											$taskDuration = $changeRequest['adjustedTaskDuration1'];
										else
											$taskDuration = $changeRequest['initialTaskDuration'];

									echo $taskDuration;?>
									<?php if($taskDuration > 1):?>
										 Days)
									<?php else:?>
										Day)
									<?php endif;?>
								<h4>Current Task Responsible: <?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'];?></h4>
							</div>
							<div class="modal-body">
								<div id="denyConfirm">
									<h4>Are you sure you want to deny this request?</h4>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i></button>
										<button id = "confirmDenyBtn" type="submit" class="btn btn-success" data-id=""><i class="fa fa-check"></i></button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

					<!-- APPROVE MODAL -->
					<div class="modal fade" id="modal-approve">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h2 class="modal-title taskTitle"><?php echo $changeRequest['TASKTITLE'];?></h2>
									<h4 class="taskDates"><?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>
										(<?php
											if($changeRequest['TASKADJUSTEDSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
												$taskDuration = $changeRequest['adjustedTaskDuration2'];
											elseif($changeRequest['TASKSTARTDATE'] != null && $changeRequest['TASKADJUSTEDENDDATE'] != null)
												$taskDuration = $changeRequest['adjustedTaskDuration1'];
											else
												$taskDuration = $changeRequest['initialTaskDuration'];

										echo $taskDuration;?>
										<?php if($taskDuration > 1):?>
											 Days)
										<?php else:?>
											Day)
										<?php endif;?>
									</h4>

									<h4>Current Task Responsible: <?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'];?></h4>
								</div>

							<div class="modal-body">

								<!-- CONFIRM APPROVE -->
								<div id="approveConfirm">
									<h4>Are you sure you want to approve this request?</h4>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i></button>
										<button id = "confirmApproveBtn" type="submit" class="btn btn-success" data-id=""><i class="fa fa-check"></i></button>
										<?php if ($changeRequest['REQUESTTYPE'] == '1'):?>
											<button id="delegateBtn" type="button" class="btn btn-success pull-left" style="margin-right: 15%"><i class="fa fa-check"></i></button>
										<?php endif;?>
									</div>
								</div>

								<!-- CONFIRM DELEGATE -->
								<div id="delegateConfirm">
									<h4>Are you sure you want to delegate this task and approve this request?</h4>
									<div class="modal-footer">
										<button id = "backConfirmDelegate" type="button" class="btn btn-default pull-left"><i class="fa fa-close"></i></button>
										<button id = "confirmDelegateBtn" type="submit" class="btn btn-success" data-id=""><i class="fa fa-check"></i></button>
									</div>
								</div>

								<!-- DELEGATE -->

								<div id="raciDelegate">
								<div class="box">
									<div class="box-header" style="display:inline-block">
										<h3 class="box-title">
											<div class="btn-group">
												<button type="button" class="btn btn-default btn-sm raciBtn" id="responsible">Responsible</button>
												<button type="button" class="btn btn-default btn-sm raciBtn" id="accountable">Accountable</button>
												<button type="button" class="btn btn-default btn-sm raciBtn" id="consulted">Consulted</button>
												<button type="button" class="btn btn-default btn-sm raciBtn" id="informed">Informed</button>
											</div>
										</h3>
									</div>
									<!-- /.box-header -->

									<div class="box-body">
										<!-- <form id="raciForm" action="delegateTask" method="POST"> -->

										<!-- RESPONSIBLE DIV -->
										<div class="form-group raciDiv" id = "responsibleDiv">
										<table id="responsibleList" class="table table-bordered table-hover">
											<thead>
											<tr>
												<th></th>
												<th>Name</th>
												<th align="center">No. of Projects <small><br>(Planned & Ongoing)</small></th>
												<th align="center">No. of Tasks <small><br>(Planned & Ongoing)</small></th>
												<th></th>
											</tr>
											</thead>
											<tbody>
												<?php foreach($deptEmployees as $employee):?>
													<tr>
														<td><div class="radio">
															<label>
																<input class = "radioEmp" type="radio" name="responsibleEmp" value="<?php echo $employee['USERID'];?>" required>
															</label>
														</div></td>
														<td><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?></td>
														<?php foreach($projectCount as $count): ;?>
															<?php $hasProjects = false;?>
															<?php if ($count['USERID'] == $employee['USERID']):?>
																<td align="center"><?php echo $count['projectCount'];?></td>
																<?php $hasProjects = $count['projectCount'];?>
																<?php break;?>
															<?php endif;?>
														<?php endforeach;?>
														<?php if ($hasProjects <= '0'):?>
															<?php $hasProjects = 0;?>
															<td align="center">0</td>
														<?php endif;?>

														<?php foreach($taskCount as $count): ;?>
															<?php $hasTasks = false;?>
															<?php if ($count['USERID'] == $employee['USERID']):?>
																<td align="center"><?php echo $count['taskCount'];?></td>
																<?php $hasTasks = $count['taskCount'];?>
																<?php break;?>
															<?php endif;?>
														<?php endforeach;?>
														<?php if ($hasTasks <= '0'):?>
															<?php $hasTasks = 0;?>
															<td align="center">0</td>
														<?php endif;?>

														<td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>"
															data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>"
															data-projectCount = "<?php echo $hasProjects;?>"
															data-taskCount = "<?php echo $hasTasks;?>">
															<a class="btn moreBtn" data-toggle="modal">
															<i class="fa fa-info-circle"></i> More Info</a>
														</td>
													</tr>
												<?php endforeach;?>
											</tbody>
										</table>
									</div>

									<!-- ACCOUNTABLE DIV -->
									<div class="form-group raciDiv" id = "accountableDiv">
										<label>Select Department/s: (optional)</label>
										<select class="form-control select2" multiple="multiple" name = "accountableDept[]" data-placeholder="Select Departments" style="width:100%">

											<?php foreach ($departments as $row): ?>

												<option value="<?php echo $row['users_DEPARTMENTHEAD']; ?>">
													<?php echo $row['DEPARTMENTNAME']; ?>
												</option>

											<?php endforeach; ?>
										</select>
										<br><br>

									<table id="accountableList2" class="table table-bordered table-hover">
										<thead>
										<tr>
											<th></th>
											<th>Name</th>
											<th align="center">No. of Projects <small><br>(Planned & Ongoing)</small></th>
											<th align="center">No. of Tasks <small><br>(Planned & Ongoing)</small></th>
											<th></th>
										</tr>
										</thead>
										<tbody>
											<?php foreach($wholeDept as $employee):?>
												<tr>
													<td><div class="checkbox">
														<label>
															<input class = "checkEmp" type="checkbox" name="accountableEmp[]" value="<?php echo $employee['USERID'];?>">
														</label>
													</div></td>
													<td><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?></td>
													<?php foreach($projectCount as $count): ;?>
														<?php $hasProjects = false;?>
														<?php if ($count['USERID'] == $employee['USERID']):?>
															<td align="center"><?php echo $count['projectCount'];?></td>
															<?php $hasProjects = $count['projectCount'];?>
															<?php break;?>
														<?php endif;?>
													<?php endforeach;?>
													<?php if ($hasProjects <= '0'):?>
														<?php $hasProjects = 0;?>
														<td align="center">0</td>
													<?php endif;?>

													<?php foreach($taskCount as $count): ;?>
														<?php $hasTasks = false;?>
														<?php if ($count['USERID'] == $employee['USERID']):?>
															<td align="center"><?php echo $count['taskCount'];?></td>
															<?php $hasTasks = $count['taskCount'];?>
															<?php break;?>
														<?php endif;?>
													<?php endforeach;?>
													<?php if ($hasTasks <= '0'):?>
														<?php $hasTasks = 0;?>
														<td align="center">0</td>
													<?php endif;?>
														<td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>"
															data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>"
															data-projectCount = "<?php echo $hasProjects;?>"
															data-taskCount = "<?php echo $hasTasks;?>">
														<a class="btn moreBtn" data-toggle="modal">
															<i class="fa fa-info-circle"></i> More Info</a></td>
												</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								</div>

								<!-- CONSULTED DIV -->
								<div class="form-group raciDiv" id = "consultedDiv">

									<label>Select Department/s: (optional)</label>
									<select class="form-control select2" multiple="multiple" name = "consultedDept[]" data-placeholder="Select Departments" style="width:100%">

										<?php foreach ($departments as $row): ?>

											<option value="<?php echo $row['users_DEPARTMENTHEAD']; ?>">
												<?php echo $row['DEPARTMENTNAME']; ?>
											</option>

										<?php endforeach; ?>
									</select>
									<br><br>

								<table id="consultedList2" class="table table-bordered table-hover">
									<thead>
									<tr>
										<th></th>
										<th>Name</th>
										<th align="center">No. of Projects <small><br>(Planned & Ongoing)</small></th>
										<th align="center">No. of Tasks <small><br>(Planned & Ongoing)</small></th>
										<th></th>
									</tr>
									</thead>
									<tbody>
										<?php foreach($wholeDept as $employee):?>
											<tr>
												<td><div class="checkbox">
													<label>
														<input class = "checkEmp" type="checkbox" name="consultedEmp[]" value="<?php echo $employee['USERID'];?>">
													</label>
												</div></td>
												<td><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?></td>
												<?php foreach($projectCount as $count): ;?>
													<?php $hasProjects = false;?>
													<?php if ($count['USERID'] == $employee['USERID']):?>
														<td align="center"><?php echo $count['projectCount'];?></td>
														<?php $hasProjects = $count['projectCount'];?>
														<?php break;?>
													<?php endif;?>
												<?php endforeach;?>
												<?php if ($hasProjects <= '0'):?>
													<?php $hasProjects = 0;?>
													<td align="center">0</td>
												<?php endif;?>

												<?php foreach($taskCount as $count): ;?>
													<?php $hasTasks = false;?>
													<?php if ($count['USERID'] == $employee['USERID']):?>
														<td align="center"><?php echo $count['taskCount'];?></td>
														<?php $hasTasks = $count['taskCount'];?>
														<?php break;?>
													<?php endif;?>
												<?php endforeach;?>
												<?php if ($hasTasks <= '0'):?>
													<?php $hasTasks = 0;?>
													<td align="center">0</td>
												<?php endif;?>
												<td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>"
													data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>"
													data-projectCount = "<?php echo $hasProjects;?>"
													data-taskCount = "<?php echo $hasTasks;?>">
													<a class="btn moreBtn" data-toggle="modal">
														<i class="fa fa-info-circle"></i> More Info</a></td>
											</tr>
										<?php endforeach;?>
									</tbody>
								</table>
							</div>

							<!-- INFORMED DIV -->
							<div class="form-group raciDiv" id = "informedDiv">
								<label>Select Department/s: (optional)</label>
								<select id="nami" class="form-control select2" multiple="multiple" name = "informedDept[]" data-placeholder="Select Departments" style="width:100%">

									<?php foreach ($departments as $row): ?>

										<option value="<?php echo $row['users_DEPARTMENTHEAD']; ?>">
											<?php echo $row['DEPARTMENTNAME']; ?>
										</option>

									<?php endforeach; ?>
								</select>
								<br><br>

							<table id="informedList2" class="table table-bordered table-hover">
								<thead>
								<tr>
									<th></th>
									<th>Name</th>
									<th align="center">No. of Projects <small><br>(Planned & Ongoing)</small></th>
									<th align="center">No. of Tasks <small><br>(Planned & Ongoing)</small></th>
									<th></th>
								</tr>
								</thead>
								<tbody>
									<?php foreach($wholeDept as $employee):?>
										<tr>
											<td><div class="checkbox">
												<label>
													<input class = "checkEmp" type="checkbox" name="informedEmp[]" value="<?php echo $employee['USERID'];?>">
												</label>
											</div></td>
											<td><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?></td>
											<?php foreach($projectCount as $count): ;?>
												<?php $hasProjects = false;?>
												<?php if ($count['USERID'] == $employee['USERID']):?>
													<td align="center"><?php echo $count['projectCount'];?></td>
													<?php $hasProjects = $count['projectCount'];?>
													<?php break;?>
												<?php endif;?>
											<?php endforeach;?>
											<?php if ($hasProjects <= '0'):?>
												<?php $hasProjects = 0;?>
												<td align="center">0</td>
											<?php endif;?>

											<?php foreach($taskCount as $count): ;?>
												<?php $hasTasks = false;?>
												<?php if ($count['USERID'] == $employee['USERID']):?>
													<td align="center"><?php echo $count['taskCount'];?></td>
													<?php $hasTasks = $count['taskCount'];?>
													<?php break;?>
												<?php endif;?>
											<?php endforeach;?>
											<?php if ($hasTasks <= '0'):?>
												<?php $hasTasks = 0;?>
												<td align="center">0</td>
											<?php endif;?>
											<td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>"
												data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>"
												data-projectCount = "<?php echo $hasProjects;?>"
												data-taskCount = "<?php echo $hasTasks;?>">
												<a class="btn moreBtn" data-toggle="modal">
													<i class="fa fa-info-circle"></i> More Info</a></td>
										</tr>
									<?php endforeach;?>
								</tbody>
							</table>
						</div>

									<!-- /.box-body -->
								</div>
							</div>

							<div class="modal-footer">
								<button id="backDelegate" type="button" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i></button>
								<button id = "delegateTask" type="button" class="btn btn-success delegate" data-id="<?php $changeRequest['tasks_REQUESTEDTASK'];?>"><i class="fa fa-check"></i></button>
							</div>
						</form>
						</div>

								<!-- WORKLOAD ASSESSMENT -->
								<div id="workloadAssessment">

									<div class="modal-header">
										<h3 class="modal-title" id ="workloadEmployee">Employee Name</h3>
										<h4 id = "workloadProjects">Total Number of Projects: </h4>
										<h4 id = "workloadTasks">Total Number of Tasks: </h4>
									</div>
									<div class="modal-body" id = "workloadDiv">
									</div>
									<div class="modal-footer">
										<button type="button" id="backWorkload" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i></button>
									</div>
								</div>
							</div>
							</div>
						</div>
					</div> <!-- END OF MODAL DIV -->

				<?php endif;?>


					<!-- <div class="pull-right" style="text-align:center;">
						<div class="progress" data-percentage="20">
                <span class="progress-left">
                    <span class="progress-bar"></span>
                </span>
                <span class="progress-right">
                    <span class="progress-bar"></span>
                </span>
                <div class="progress-value">
                    <div>
                        20%<br>
                        <span>Completed</span>
                    </div>
								</div>
					</div> -->

					<h1>
						<?php echo $projectProfile['PROJECTTITLE']; ?>
							<?php if ($projectProfile['PROJECTSTATUS'] == 'Planning'): ?>
								<a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"><i class="fa fa-edit"></i></a>
							<?php endif; ?>
					</h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<li class="active"><?php echo $projectProfile['PROJECTTITLE']; ?></li>
					</ol>

					<div class="pull-right" style="margin-left:50px; margin-right:40px;">
						<div class="circlechart" id="completeness" data-percentage="50.79">Completeness</div>
					</div>
					<div class="pull-right">
						<div class="circlechart" id="completeness" data-percentage="70.79">Timeliness</div>
					</div>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">

					<h4>Project Owner: <?php echo $projectProfile['FIRSTNAME']; ?> <?php echo $projectProfile['LASTNAME']; ?></h4>
					<h4>Description: <?php echo $projectProfile['PROJECTDESCRIPTION']; ?></h4>
					<div>

						<?php
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
						$current = date_create(date("Y-m-d")); // get current date
						?>

						<h4>Initial Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($enddate, "F d, Y"); ?>
							(<?php echo $projectProfile['duration'];?>
							<?php if($projectProfile['duration'] > 1):?>
								days)
							<?php else:?>
								day)
							<?php endif;?>
						</h4>

						<?php if ($projectProfile['PROJECTSTATUS'] == 'Archived' || $projectProfile['PROJECTSTATUS'] == 'Complete'):?>
							<?php $actualEnd = date_create($projectProfile['PROJECTACTUALENDDATE']);?>

							<h4 style="color:red">Actual Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($actualEnd, "F d, Y"); ?>
								(<?php echo $projectProfile['actualDuration'];?>
								<?php if($projectProfile['actualDuration'] > 1):?>
									days)
								<?php else:?>
									day)
								<?php endif;?>
							</h4>

						<?php else:?>

							<h4 style="color:red">
								<?php if ($current >= $startdate && $current <= $enddate && $projectProfile['PROJECTSTATUS'] == 'Ongoing'):?>
									<?php echo $projectProfile['remaining'];?>
									<?php if($projectProfile['remaining'] > 1):?>
										days remaining
									<?php else:?>
										day remaining
									<?php endif;?>
								<?php elseif ($current < $startdate && $projectProfile['PROJECTSTATUS'] == 'Planning'):?>
									Launch in <?php echo $projectProfile['launching'];?>
									<?php if($projectProfile['launching'] > 1):?>
										days
									<?php else:?>
										day
									<?php endif;?>
								<?php elseif ($current >= $startdate && $current >= $enddate && $projectProfile['PROJECTSTATUS'] == 'Ongoing'):?>
									<?php echo $projectProfile['delayed'];?>
									<?php if($projectProfile['delayed'] > 1):?>
										days delayed
									<?php else:?>
										day delayed
									<?php endif;?>
								<?php endif;?>
							</h4>

						<?php endif;?>

						<form name="gantt" action ='projectDocuments' method="POST" id ="prjID">
							<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
							<input type="hidden" name="projectID_logs" value="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>

						<!-- IF USING GET METHOD
						<a href="<?php echo base_url("index.php/controller/projectDocuments/?id=") . $projectProfile['PROJECTID']; ?>" name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a> -->
						<!-- <a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"class="btn btn-default btn-xs"><i class="fa fa-flag"></i> View Logs</a> -->

						<a name="PROJECTID" class="btn btn-success btn" id="projectDocu"><i class="fa fa-folder"></i></a>

						<a name="PROJECTID_logs" class="btn btn-success btn" id="projectLog"><i class="fa fa-flag"></i></a>

						<?php if ($projectProfile['PROJECTSTATUS'] == 'Complete'): ?>

							<form action = 'archiveProject' method="POST">
							</form>
							<a name="" class="btn btn-primary btn" id="archiveProject"><i class="fa fa-archive"></i></a>

						<?php elseif($projectProfile['PROJECTSTATUS'] == 'Archived' && !isset($_SESSION['templates']) && !isset($_SESSION['templateProjectGantt'])): ?>

							<form action = 'templateProject' method="POST">
							</form>
							<a name="" class="btn btn-default btn" id="templateProject"><i class="fa fa-window-maximize"></i></a>

						<?php elseif (isset($_SESSION['templates']) || isset($_SESSION['templateProjectGantt'])): ?>
							<form action = 'newProject' method="POST">
							</form>
							<a name="" class="btn btn-default btn" id="useTemplate"><i class="fa fa-window-maximize"></i></a>
						<?php endif; ?>

						<?php if($projectProfile['PROJECTSTATUS'] == 'Ongoing'): ?>
							<a name="" class="btn btn-default btn" id="parkProject"><i class="fa fa-clock-o"></i></a>
						<?php endif;?>

						<?php if($projectProfile['PROJECTSTATUS'] == 'Parked'): ?>
							<a name="" class="btn btn-default btn" id="continueProject"><i class="fa fa-clock-o"></i></a>
						<?php endif;?>

					</div>
					<br>
					<div id="container" style="height: 600px;"></div>

					<!-- </section> -->
				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>

		$(document).on("click", "#archiveProject", function() {
			var $id = <?php echo $projectProfile['PROJECTID']; ?>;
			$("form").attr("name", "formSubmit");
			$("form").append("<input type='hidden' name='project_ID' value= " + $id + ">");
			$("form").submit();
			});

			$(document).on("click", "#templateProject", function() {
				var $id = <?php echo $projectProfile['PROJECTID']; ?>;
				$("form").attr("name", "formSubmit");
				$("form").append("<input type='hidden' name='project_ID' value= " + $id + ">");
				$("form").submit();
				});

				$(document).on("click", "#useTemplate", function() {
					var $id = <?php echo $projectProfile['PROJECTID']; ?>;
					$("form").attr("name", "formSubmit");
					$("form").append("<input type='hidden' name='project_ID' value= " + $id + ">");
					$("form").submit();
					});

			$("#myProjects").addClass("active");

			// $("#projectDocu").click(function()
			// {
			// 	("#gantt").submit();
      // });

			// RFC APPROVAL SCRIPT

			$("body").on("click", function(){ // REMOVE ALL SELECTED IN MODAL
				if($("#modal-approve").css("display") == 'none')
				{
					$(".radioEmp").prop("checked", false);
					$(".checkEmp").prop("checked", false);
					$(".select2").val(null).trigger("change");
					$(".raciBtn").removeClass('active');
					$("#responsible").addClass("active");
					$(".raciDiv").hide();
					$("#workloadAssessment").hide();
					$("#raciDelegate").hide();
					$("#delegateConfirm").hide();
					$("#approveConfirm").show();
				}
			});

			$(document).on("click", "#confirmDelegateBtn", function() { // approve with delegate
				var $request = $("#approvalForm").attr('data-request');
				var $project = $("#approvalForm").attr('data-project');
				var $task = $("#approvalForm").attr('data-task');
				var $type = $("#approvalForm").attr('data-type');
				var $requestor = $("#approvalForm").attr('data-requestor');
				$("#approvalForm").attr("name", "formSubmit");
				$("#approvalForm").append("<input type='hidden' name='request_ID' value= '" + $request + "'>");
				$("#approvalForm").append("<input type='hidden' name='request_type' value= '" + $type + "'>");
				$("#approvalForm").append("<input type='hidden' name='project_ID' value= '" + $project + "'>");
				$("#approvalForm").append("<input type='hidden' name='task_ID' value= '" + $task + "'>");
				$("#approvalForm").append("<input type='hidden' name='requestor_ID' value= '" + $requestor + "'>");
				$("#approvalForm").append("<input type='hidden' name='status' value= 'Approved'>");
				$("#approvalForm").submit();
				});

			$(document).on("click", "#confirmDenyBtn", function() { // deny
				var $request = $("#approvalForm").attr('data-request');
				var $project = $("#approvalForm").attr('data-project');
				var $task = $("#approvalForm").attr('data-task');
				var $type = $("#approvalForm").attr('data-type');
				var $requestor = $("#approvalForm").attr('data-requestor');
				$("#approvalForm").attr("name", "formSubmit");
				$("#approvalForm").append("<input type='hidden' name='request_ID' value= '" + $request + "'>");
				$("#approvalForm").append("<input type='hidden' name='request_type' value= '" + $type + "'>");
				$("#approvalForm").append("<input type='hidden' name='project_ID' value= '" + $project + "'>");
				$("#approvalForm").append("<input type='hidden' name='task_ID' value= '" + $task + "'>");
				$("#approvalForm").append("<input type='hidden' name='requestor_ID' value= '" + $requestor + "'>");
				$("#approvalForm").append("<input type='hidden' name='status' value= 'Denied'>");
				$("#approvalForm").submit();
				});

			$(document).on("click", "#confirmApproveBtn", function() { // approve without delegate
				var $request = $("#approvalForm").attr('data-request');
				var $project = $("#approvalForm").attr('data-project');
				var $task = $("#approvalForm").attr('data-task');
				var $type = $("#approvalForm").attr('data-type');
				var $requestor = $("#approvalForm").attr('data-requestor');
				$("#approvalForm").attr("name", "formSubmit");
				$("#approvalForm").append("<input type='hidden' name='request_ID' value= '" + $request + "'>");
				$("#approvalForm").append("<input type='hidden' name='request_type' value= '" + $type + "'>");
				$("#approvalForm").append("<input type='hidden' name='project_ID' value= '" + $project + "'>");
				$("#approvalForm").append("<input type='hidden' name='task_ID' value= '" + $task + "'>");
				$("#approvalForm").append("<input type='hidden' name='requestor_ID' value= '" + $requestor + "'>");
				$("#approvalForm").append("<input type='hidden' name='status' value= 'Approved'>");
				$("#approvalForm").submit();
				});

			$(".moreInfo").click(function(){
				$("#raciDelegate").hide();

				function loadWorkloadTasks($projectID)
				{
					$.ajax({
						type:"POST",
						url: "<?php echo base_url("index.php/controller/getUserWorkloadTasks"); ?>",
						data: {userID: $id, projectID: $projectID},
						dataType: 'json',
						success:function(data)
						{
							for(t=0; t<data['workloadTasks'].length; t++)
							{
								var taskStart = moment(data['workloadTasks'][t].TASKSTARTDATE).format('MMM DD, YYYY');
								var taskEnd = moment(data['workloadTasks'][t].TASKENDDATE).format('MMM DD, YYYY');

								$("#project_" + $projectID).append("<tr>" +
												 "<td>" + data['workloadTasks'][t].TASKTITLE + "</td>" +
												 "<td>" + taskStart + "</td>" +
												 "<td>" + taskEnd + "</td>" +
												 "<td>" + data['workloadTasks'][t].TASKSTATUS + "</td>" +
												 "</tr>");
							}
						},
						error:function()
						{
							alert("Failed to retrieve user data.");
						}
					});
				 }

				var $id = $(this).attr('data-id');
				var $projectCount = $(this).attr('data-projectCount');
				var $taskCount = $(this).attr('data-taskCount');
				$("#workloadEmployee").html($(this).attr('data-name'));
				$("#workloadProjects").html("Total Number of Projects: " + $projectCount);
				$("#workloadTasks").html("Total Number of Tasks: " + $taskCount);
				$('#workloadDiv').html("");
				$("#workloadAssessment").show();

				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/getUserWorkloadProjects"); ?>",
					data: {userID: $id},
					dataType: 'json',
					success:function(data)
					{
						$('#workloadDiv').html("");
						for(p=0; p<data['workloadProjects'].length; p++)
						{
							var $projectID = data['workloadProjects'][p].PROJECTID;
							$('#workloadDiv').append("<div class = 'box'>" +
											 "<div class = 'box-header'>" +
												 "<h3 class = 'box-title text-blue'> " + data['workloadProjects'][p].PROJECTTITLE + "</h3>" +
											 "</div>" +
											 "<div class = 'box-body table-responsive no-padding'>" +
												 "<table class='table table-hover' id='project_" + $projectID + "'>" +
													 "<th>Task Name</th>" +
													 "<th>Start Date</th>" +
													 "<th>End Date</th>" +
													 "<th>Status</th>");

							 loadWorkloadTasks($projectID);

							 $('#workloadDiv').append("</table>" +
																				 "</div>" +
																			 "</div>");
						}
					},
					error:function()
					{
						alert("Failed to retrieve user data.");
					}
				});

			});

			$("#backWorkload").click(function(){

				$("#raciDelegate").show();
				$("#workloadAssessment").hide();

			});

			$("#backDelegate").click(function(){

				$("#approveConfirm").show();
				$("#raciDelegate").hide();
				$(".raciDiv").hide();
				$("#responsibleDiv").show();
			});

			$("#backConfirmDelegate").click(function(){
				$(".raciBtn").removeClass('active');
				$("#responsible").addClass("active");
				$("#responsibleDiv").show();
				$("#raciDelegate").show();
				$("#delegateConfirm").hide();
			});

			$("#delegateBtn").click(function(){
				$(".raciBtn").removeClass('active');
				$("#responsible").addClass("active");
				$("#responsibleDiv").show();
				$("#raciDelegate").show();
				$("#approveConfirm").hide();
			});

			$("#responsible").on("click", function(){
				$(".raciBtn").removeClass('active');
				$(this).addClass("active");
				$(".raciDiv").hide();
				$("#responsibleDiv").show();
			});

			$("#accountable").on("click", function(){
				$(".raciBtn").removeClass('active');
				$(this).addClass("active");
				$(".raciDiv").hide();
				$("#accountableDiv").show();
			});

			$("#consulted").on("click", function(){
				$(".raciBtn").removeClass('active');
				$(this).addClass("active");
				$(".raciDiv").hide();
				$("#consultedDiv").show();
			});

			$("#informed").on("click", function(){
				$(".raciBtn").removeClass('active');
				$(this).addClass("active");
				$(".raciDiv").hide();
				$("#informedDiv").show();
			});

			$("#delegateTask").on("click", function(){
				$("#delegateConfirm").show();
				$("#raciDelegate").hide();
			});

		</script>

		<!-- Javascript for Tasks -->

		<script>

			$('.select2').select2()
			$('.circlechart').circlechart(); // Initialization

			$(function ()
			{
				//Date picker
 	     $('#startDate').datepicker({
 	       autoclose: true
 	     });

 	     $('#endDate').datepicker({
 	       autoclose: true
 	     });
		 });

			$("#projectDocu").click(function() //redirect to individual project profile
      {
				$("#prjID").submit();
      });

			$("#projectLog").click(function() //redirect to individual project logs
      {
				$("#prjID").attr("action","projectLogs");
				$("#prjID").submit();
      });

			$("#parkProject").click(function() //submitPark
      {
				$("#prjID").attr("action","parkProject");
				$("#prjID").submit();
      });

			$("#continueProject").click(function() //submitPark
      {
				alert("Continue Project");
      });

		</script>

		<script>

			anychart.onDocumentReady(function (){

				var rawData = [
					<?php

					foreach ($ganttData as $key => $value) {

						// START: Formatting of TARGET START date
						$startDate = $value['TASKSTARTDATE'];
						$formatted_startDate = date('M d, Y', strtotime($startDate));
						// END: Formatting of TARGET START date

						// START: Formatting of TARGET END date
						$endDate = $value['TASKENDDATE'];
						$formatted_endDate = date('M d, Y', strtotime($endDate));
						// END: Formatting of TARGET END date

						// START: Formatting of ACTUAL START date
						$actualStartDate = $value['TASKACTUALSTARTDATE'];
						$formatted_actualStartDate = date('M d, Y', strtotime($actualStartDate));
						// END: Formatting of ACTUAL START date

						// START: Formatting of ACTUAL END date
						$actualEndDate = $value['TASKACTUALENDDATE'];
						$formatted_actualEndDate = date('M d, Y', strtotime($actualEndDate));
						// END: Formatting of ACTUAL END date

						// START: Checks for progress value
						$progress = '0';
						if($value['TASKSTATUS'] == 'Complete' && $value['CATEGORY'] == 3){
							$progress = 100;
						}
						// END: Checks for progress value

						// START: Checks for parent
						$parent = '0';
						if($value['tasks_TASKPARENT'] != NULL){
							$parent = $value['tasks_TASKPARENT'];
							// echo "<script>console.log(".$parent.");</script>";
						}
						// END: Checks for parent

						// START: Checks for period
						$period = '';
						if($value['TASKADJUSTEDSTARTDATE'] == NULL && $value['TASKADJUSTEDENDDATE'] == NULL){
							$period = $value['initialTaskDuration'];
						} else if ($value['TASKADJUSTEDSTARTDATE'] == NULL && $value['TASKADJUSTEDENDDATE'] != NULL){
							$period = $value['adjustedTaskDuration1'];
						} else {
							$period = $value['adjustedTaskDuration2'];
						}
						// END: Checks for period

						// START: Checks for dependecies
						$dependency = '';
						$type = '';
						if($dependencies != NULL){
							foreach ($dependencies as $data) {
								if($data['PRETASKID'] == $value['TASKID']){
									$dependency = $data['tasks_POSTTASKID'];
									$type = 'finish-start';
								}
							}
						}
						// END: Checks for dependecies

						// START: Checks for responsible
						$responsiblePerson = '';
						foreach ($responsible as $r) {
							if($r['tasks_TASKID'] == $value['TASKID']){
								$responsiblePerson = $r['FIRSTNAME'] . " " . $r['LASTNAME'];
							}
						}
						// END: Checks for responsible

						// START: Checks for accountable
						$accountablePerson = '';
						foreach ($accountable as $a) {
							if($a['tasks_TASKID'] == $value['TASKID']){
								$accountablePerson = $a['FIRSTNAME'] . " " . $a['LASTNAME'];
							}
						}
						// END: Checks for accountable

						// START: Checks for consulted
						$consultedPerson = '';
						foreach ($consulted as $c) {
							if($c['tasks_TASKID'] == $value['TASKID']){
								$consultedPerson = $c['FIRSTNAME'] . " " . $c['LASTNAME'];
							}
						}
						// END: Checks for consulted

						// START: Checks for informed
						$informedPerson = '';
						foreach ($informed as $i) {
							if($i['tasks_TASKID'] == $value['TASKID']){
								$informedPerson = $c['FIRSTNAME'] . " " . $i['LASTNAME'];
							}
						}
						// END: Checks for informed



						//START: CHECKS IF RACI IS EMPTY
						if($accountable == NULL || $consulted == NULL || $informed == NULL ){
							echo "
							{
								'id': " . $value['TASKID'] . ",
								'name': '" . $value['TASKTITLE'] . "',
								'actualStart': '" . $formatted_startDate . "',
								'actualEnd': '" . $formatted_endDate . "',
								'responsible': '',
								'accountable': '',
								'consulted': '',
								'informed': '',
								'period': '" . $progress . "',
								'progressValue': '0%'
							},";
						} else { // START: RACI IS NOT EMPTY
							// START: CHECKS IF MAIN OR SUB
							if($value['CATEGORY'] == 2 || $value['CATEGORY'] == 1){
								// START: Planning - no baseline since task have not yet started
								if(($value['TASKACTUALSTARTDATE'] == NULL)){
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $period . "',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "'
									},";
								} // END: Planning - no baseline since task have not yet started

								// START: Ongoing tasks - baselineEnd is the date today
								else if($value['TASKACTUALENDDATE'] == NULL){
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $period . "',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
										'baselineEnd': '" . date('M d, Y') . "'
									},";
								} // END: Ongoing tasks - baselineEnd is the date today

								// START: Completed tasks - baselineStart and baselineEnd are present
								else {
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $period . "',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
										'baselineEnd': '" . $formatted_actualEndDate . "'
									},";
								} // END: Completed tasks - baselineStart and baselineEnd are present

							} else { // START: IF TASK
								if(($value['TASKACTUALSTARTDATE'] == NULL)){
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $period . "',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "'
									},";
								} // END: Planning - no baseline since task have not yet started

								// START: Ongoing tasks - baselineEnd is the date today
								else if($value['TASKACTUALENDDATE'] == NULL){
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $period . "',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
										'baselineEnd': '" . date('M d, Y') . "'
									},";
								} // END: Ongoing tasks - baselineEnd is the date today

								// START: Completed tasks - baselineStart and baselineEnd are present
								else {
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $period . "',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
										'baselineEnd': '" . $formatted_actualEndDate . "'
									},";
								} // END: Completed tasks - baselineStart and baselineEnd are present
							} // END: CHECKS FOR CATEGORY
						} // END: CHECKS IF RACI IS EMPTY OR NOT
					} // END: Foreach
					?>
				];

				// data tree settings
				var treeData = anychart.data.tree(rawData, "as-table");
				var chart = anychart.ganttProject();      // chart type
				chart.data(treeData);                     // chart data

				// data grid getter
				var dataGrid = chart.dataGrid();

				// create custom column
				var columnTitle = dataGrid.column(1);
				columnTitle.title("Task Name");
				columnTitle.setColumnFormat("name", "text");
				columnTitle.width(300);

				var columnStartDate = dataGrid.column(2);
				columnStartDate.title("Target Start Date");
				columnStartDate.setColumnFormat("actualStart", "dateCommonLog");
				columnStartDate.width(100);

				var columnEndDate = dataGrid.column(3);

				columnEndDate.title("Target End Date");
				columnEndDate.setColumnFormat("actualEnd", "dateCommonLog");
				columnEndDate.width(100);

				var columnPeriod = dataGrid.column(4);
				columnPeriod.title("Period");
				columnPeriod.setColumnFormat("period", "text");
				columnPeriod.width(80);

				var columnResponsible = dataGrid.column(5);
				columnResponsible.title("Responsible");
				columnResponsible.setColumnFormat("responsible", "text");
				columnResponsible.width(100);

				var columnAccountable = dataGrid.column(6);
				columnAccountable.title("Accountable");
				columnAccountable.setColumnFormat("accountable", "text");
				columnAccountable.width(100);

				var columnConsulted = dataGrid.column(7);
				columnConsulted.title("Consulted");
				columnConsulted.setColumnFormat("consulted", "text");
				columnConsulted.width(100);

				var columnInformed = dataGrid.column(9);
				columnInformed.title("Informed");
				columnInformed.setColumnFormat("informed", "text");
				columnInformed.width(100);

				//get chart timeline link to change color

				chart.zoomTo("week", 3, "firstDate");
				chart.splitterPosition(650);
				chart.container('container').draw();      // set container and initiate drawing

			});

		</script>
	</body>
</html>
