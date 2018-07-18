<html>
	<head>
		<title>Kernel - My Tasks</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTasksStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						My Tasks
						<small>What do I need to do?</small>
					</h1>
					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myTasks"); ?>"><i class="fa fa-dashboard"></i> My Tasks</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">

					<!-- MAIN ACTIVITY TABLE -->

					<div class="box" id="mainActivityBox">
						<div class="box-header">
							<h3 class="box-title text-blue">Main Activities</h3>
						</div>
						<!-- /.box-header -->

						<div class="box-body">
							<table id="mainActivityList" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>Title</th>
										<th>Project</th>
										<th>Start Date</th>
										<th>Target End Date</th>
										<th>Period <small>(Day/s)</small></th>
										<?php if($_SESSION['usertype_USERTYPEID'] != '5'):?>
											<th><i class="fa fa-users" style="margin-left:50%"></i></th>
										<?php endif;?>
									</tr>
								</thead>

								<tbody id="mainActivityTable">

									<?php foreach ($mainActivity as $mainAct):?>

										<?php
										$mainStart = date_create($mainAct['TASKSTARTDATE']);
										$mainEnd = date_create($mainAct['TASKENDDATE']);
										?>

										<tr>
											<td><?php echo $mainAct['TASKTITLE'];?></td>
											<td><?php echo $mainAct['PROJECTTITLE'];?></td>
											<td><?php echo date_format($mainStart, "M d, Y");?></td>
											<td><?php echo date_format($mainEnd, "M d, Y");?></td>
											<td><?php echo $mainAct['taskDuration'];?></td>

											<td align="center">
												<button type="button" class="btn btn-primary btn-sm delegateBtn"
																data-toggle="modal" data-target="#modal-delegate" data-id="
																<?php echo $mainAct['TASKID'];?>" data-title=" <?php echo $mainAct['TASKTITLE'];?>"
																data-start="<?php echo $mainAct['TASKSTARTDATE'];?>"
																data-end="<?php echo $mainAct['TASKENDDATE'];?>"
																<i class="fa fa-users"></i> Delegate</button>
											</td>
										</tr>

									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- SUB ACTIVITY TABLE -->

					<div class="box" id="subActivityBox">
						<div class="box-header">
							<h3 class="box-title text-blue">Sub Activities</h3>
						</div>
						<!-- /.box-header -->

						<div class="box-body">
							<table id="subActivityList" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>Title</th>
										<th>Project</th>
										<th>Start Date</th>
										<th>Target End Date</th>
										<th>Period <small>(Day/s)</small></th>
										<?php if($_SESSION['usertype_USERTYPEID'] != '5'):?>
											<th><i class="fa fa-users" style="margin-left:50%"></i></th>
										<?php endif;?>
									</tr>
								</thead>

								<tbody id="subActivityTable">
									<?php foreach ($subActivity as $subAct):?>

										<?php
										$subStart = date_create($subAct['TASKSTARTDATE']);
										$subEnd = date_create($subAct['TASKENDDATE']);
										?>

										<tr>
											<td><?php echo $subAct['TASKTITLE'];?></td>
											<td><?php echo $subAct['PROJECTTITLE'];?></td>
											<td><?php echo date_format($subStart, "M d, Y");?></td>
											<td><?php echo date_format($subEnd, "M d, Y");?></td>
											<td><?php echo $subAct['taskDuration'];?></td>
											<td align="center">
												<button type="button" class="btn btn-primary btn-sm delegateBtn"
																data-toggle="modal" data-target="#modal-delegate" data-id="
																<?php echo $subAct['TASKID'];?>" data-title=" <?php echo $subAct['TASKTITLE'];?>"
																data-start="<?php echo $subAct['TASKSTARTDATE'];?>"
																data-end="<?php echo $subAct['TASKENDDATE'];?>"
																<i class="fa fa-users"></i> Delegate
												</button>
											</td>
										</tr>

									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- TASK TABLE -->

					<div class="box" id="taskBox">
						<div class="box-header">
							<h3 class="box-title text-blue">Tasks</h3>
						</div>
						<!-- /.box-header -->

						<div class="box-body">
							<table id="taskList" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th></th>
										<th>Task</th>
										<th>Project</th>
										<th>Start Date</th>
										<th>Target End Date</th>
										<th>Period <small>(Day/s)</small></th>
										<?php if($_SESSION['usertype_USERTYPEID'] != '5'):?>
											<th><i class="fa fa-users" style="margin-left:50%"></i></th>
										<?php endif;?>
										<th><i class="fa fa-warning" style="margin-left:50%"></i></th>
										<th><i class="fa fa-check" style="margin-left:50%"></i></th>
									</tr>
								</thead>
								<tbody id="taskTable">
									<?php foreach($tasks as $task):?>

										<?php
										$taskStart = date_create($task['TASKSTARTDATE']);
										$taskEnd = date_create($task['TASKENDDATE']);
										switch($task['ROLE'])
										{
											case "1": $role = "R"; break;
											case "2": $role = "A"; break;
											case "3": $role = "C"; break;
											case "4": $role = "I"; break;
										}
										?>

										<tr>
											<td><?php echo $role?></td>
											<td><?php echo $task['TASKTITLE'];?></td>
											<td><?php echo $task['PROJECTTITLE'];?></td>
											<td><?php echo date_format($taskStart, "M d, Y");?></td>
											<td><?php echo date_format($taskEnd, "M d, Y");?></td>
											<td><?php echo $task['taskDuration'];?></td>

											<?php if($role == 'R'):?>
												<td align="center">
													<button type="button" class="btn btn-primary btn-sm delegateBtn"
														data-toggle="modal" data-target="#modal-delegate"
														data-id="<?php echo $task['TASKID'];?>"
														data-title="<?php echo $task['TASKTITLE'];?>"
														data-start="<?php echo $task['TASKSTARTDATE'];?>"
														data-end="<?php echo $task['TASKENDDATE'];?>">
														<i class="fa fa-users"></i> Delegate
													</button>
												</td>
											<?php else:?>
												<td></td>
											<?php endif;?>

											<?php if($task['currentDate'] >= $task['PROJECTSTARTDATE']):?>
												<?php $newDate = $task['currentDate'] >= $task['TASKSTARTDATE'];?> <!-- CHECK IF ONGOING TASK -->
												<td align="center">
													<button type="button"
														class="btn btn-warning btn-sm rfcBtn" data-toggle="modal"
														data-target="#modal-request" data-id="<?php echo $task['TASKID'];?>"
														data-date="<?php echo $newDate;?>" data-title="<?php echo $task['TASKTITLE'];?>"
														data-start="<?php echo $task['TASKSTARTDATE'];?>"
														data-end="<?php echo $task['TASKENDDATE'];?>">
														<i class="fa fa-warning"></i> RFC
													</button>
												</td>

												<!-- INSERT AJAX TO CHECK FOR DEPENDENCIES -->
												<?php $isDelayed = $task['currentDate'] >= $task['TASKENDDATE'];?>
												<td align="center">
													<button type="button"
															class="btn btn-success btn-sm doneBtn" data-toggle="modal"
															data-target="#modal-done" data-id="<?php echo $task['TASKID'];?>"
															data-title="<?php echo $task['TASKTITLE'];?>"
															data-delay="<?php echo $isDelayed;?>" data-start="<?php echo $task['TASKSTARTDATE'];?>"
															data-end="<?php echo $task['TASKENDDATE'];?>"
															<i class="fa fa-check"></i> Done
													</button>
												</td>

											<?php else:?>
												<td></td>
												<td></td>
											<?php endif;?>
										</tr>

									<?php endforeach;?>
								</tbody>
							</table>
						</div>
					</div>

						<!-- RFC MODAL -->
						<div class="modal fade" id="modal-request" tabindex="-1">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h2 class="modal-title" id = "rfcTitle">Request for Change</h2>
										<h4 class="taskDates" id="rfcDates">Start Date - End Date (Days)</h4>
		              </div>
		              <div class="modal-body">
		                <form id = "requestForm" action = "submitRFC" method = "POST" style="margin-bottom:0;">
											<div class="form-group">
			                  <label>Request Type</label>
			                  <select class="form-control" id="rfcType" name="rfcType">
													<option disabled selected value> -- Select Request Type -- </option>
			                    <option value="1">Change Task Performer</option>
			                    <option value="2">Change Task Dates</option>
			                  </select>
			                </div>

									<div id="rfcForm">
											<!-- DISPLAY IF CHANGE TASK DATE OPTION -->
											<div id ="newDateDiv">
											<div class="form-group">
				                <label class ="start">New Start Date</label>

				                <div class="input-group date start">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control pull-right" id="startDate" name="startDate" >
				                </div>
				                <!-- /.input group -->
				              </div>
				              <!-- /.form group -->
				              <div class="form-group">
				                <label class="end">New Target End Date</label>

				                <div class="input-group date end">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control pull-right" id="endDate" name ="endDate" >
				                </div>
				                <!-- /.input group -->
				              </div>
										</div>

											<!-- DISPLAY ON BOTH OPTIONS -->
											<div class="form-group">
			                  <label>Reason</label>
			                  <textarea id="rfcReason" class="form-control" name = "reason" placeholder="State your reason here" required></textarea>
			                </div>
									</div>

		              <div class="modal-footer">
		                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
		                <button type="submit" class="btn btn-success" id="rfcSubmit" data-date=""><i class="fa fa-check"></i> Submit Request</button>
		              </div>
								</form>
								</div>
		            </div>
		            <!-- /.modal-content -->
		          </div>
		          <!-- /.modal-dialog -->
		        </div>
		        <!-- /.modal -->

						<!-- DELEGATE MODAL -->
						<div class="modal fade" id="modal-delegate">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h2 class="modal-title taskTitle">Task Name</h2>
										<h4 class="taskDates">Start Date - End Date (Days)</h4>
									</div>

									<div class="modal-body">
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
												<form id="raciForm" action="delegateTask" method="POST">

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

																<td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>" data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>" data-projectCount = "<?php echo $hasProjects;?>"><a class="btn moreBtn" data-toggle="modal" data-target="#modal-moreInfo"><i class="fa fa-info-circle"></i> More Info</a></td>
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
																<td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>" data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>" data-projectCount = "<?php echo $hasProjects;?>">
																<a class="btn moreBtn" data-toggle="modal" data-target="#modal-moreInfo">
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
														<td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>" data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>" data-projectCount = "<?php echo $hasProjects;?>">
															<a class="btn moreBtn" data-toggle="modal" data-target="#modal-moreInfo">
																<i class="fa fa-info-circle"></i> More Info</a></td>
													</tr>
												<?php endforeach;?>
											</tbody>
										</table>
									</div>

									<!-- INFORMED DIV -->
									<div class="form-group raciDiv" id = "informedDiv">
										<label>Select Department/s: (optional)</label>
										<select class="form-control select2" multiple="multiple" name = "informedDept[]" data-placeholder="Select Departments" style="width:100%">

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
													<td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>" data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>" data-projectCount = "<?php echo $hasProjects;?>">
														<a class="btn moreBtn" data-toggle="modal" data-target="#modal-moreInfo">
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
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
										<button type="submit" class="btn btn-success" id="confirmDelegateBtn" data-toggle="modal" data-target="#modal-delegateConfirm"><i class="fa fa-check"></i> Delegate Task</button>
									</div>
								</form>
								</div>
							</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->

						<!-- CONFIRM MODAL -->
						<div class="modal fade" id="modal-delegateConfirm">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title">Delegate Task</h4>
									</div>
									<div class="modal-body">
										<p>Are you sure you want to delegate this task?</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
										<button type="submit" class="btn btn-success" data-id=""><i class="fa fa-check"></i> Confirm</button>
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->

						<!-- WORKLOAD ASSESSMENT MODAL -->
						<div class="modal fade" id="modal-moreInfo">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h2 class="modal-title" id ="workloadEmployee">Employee Name</h2>
										<h4 id = "workloadProjects">Total Number of Project/s: </h4>
									</div>
									<div class="modal-body" id = "workloadDiv">

									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->

						<!-- DONE MODAL -->
						<div class="modal fade" id="modal-done" tabindex="-1">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h2 class="modal-title" id = "doneTitle">Task Finished</h2>
										<h4 id="doneDates">Start Date - End Date (Days)</h4>
		              </div>
		              <div class="modal-body">
										<h3 id ="delayed" style="color:red; margin-top:0">Task is Delayed</h3>
										<h4 id ="early" style="margin-top:0">Are you sure this task is done?</h4>
										<form id = "doneForm" action="doneTask" method="POST" style="margin-bottom:0;">
											<div class="form-group">
												<textarea id = "remarks" name = "remarks" class="form-control" placeholder="Enter remarks" required=""></textarea>
											</div>
											<div class="modal-footer">
				                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
				                <button id = "doneConfirm" type="submit" class="btn btn-success" data-id=""><i class="fa fa-check"></i> Confirm</button>
				              </div>
										</form>
		              </div>
		            </div>
		            <!-- /.modal-content -->
		          </div>
		          <!-- /.modal-dialog -->
		        </div>
		        <!-- /.modal -->

				</section>
			</div>
				<?php require("footer.php"); ?>
		</div>

		<script>

			$("#myTasks").addClass("active");
			$('.select2').select2();
			$("#responsible").addClass("active");
			$(".raciDiv").hide();
			$("#responsibleDiv").show();
			$("#rfcForm").hide();

			$(function ()
			{
				//Date picker
 	     $('#startDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true,
				 orientation: 'bottom'
 	     });

 	     $('#endDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true,
				 startDate: new Date(),
				 orientation: 'bottom'
 	     });

			 $('#activityList').DataTable({
				 'paging'      : false,
				 'lengthChange': false,
				 'searching'   : false,
				 'ordering'    : false,
				 'info'        : false,
				 'autoWidth'   : false
			 });

			 $('#taskList').DataTable({
				 'paging'      : false,
				 'lengthChange': false,
				 'searching'   : false,
				 'ordering'    : false,
				 'info'        : false,
				 'autoWidth'   : false
			 });

			 $('#employeeList').DataTable({
				 'paging'      : false,
				 'lengthChange': false,
				 // 'searching'   : true,
				 'ordering'    : true,
				 'info'        : false,
				 'autoWidth'   : false
			 });

			 $('#projectList').DataTable().columns(-1).order('asc').draw();

			 $("#inputID").submit(function(e){
				  e.preventDefault();

					successAlert();
				});

		 	});

			$(document).ready(function() {

				$("body").on("click", function(){ // REMOVE ALL SELECTED IN MODAL
					if($("#modal-delegate").css("display") == 'none')
					{
						$(".radioEmp").prop("checked", false);
						$(".checkEmp").prop("checked", false);
						$(".select2").val(null).trigger("change");
						$(".raciBtn").removeClass('active');
						$("#responsible").addClass("active");
						$(".raciDiv").hide();
						$("#responsibleDiv").show();
					}
					if($("#modal-request").css("display") == 'none')
					{
						$("#rfcType").val("");
						$("#rfcReason").val("");
						$("#rfcForm").hide();
						$("#startDate").val("");
						$("#endDate").val("");
					}
				});

				// DELEGATE SCRIPT

				$("body").on('click','.delegateBtn',function(){
					 var $id = $(this).attr('data-id');
					 var $title = $(this).attr('data-title');
					 var $start = new Date($(this).attr('data-start'));
					 var $end = new Date($(this).attr('data-end'));
					 var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;

					 $(".taskTitle").html($title);
					 $(".taskDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
					 if($diff > 1)
					 	$(".taskDates").append(" days)");
					 else
					 	$(".taskDates").append(" day)");

				 });

				 $("body").on('click','.delegateBtn',function(){
					 var $id = $(this).attr('data-id');
					 $("#confirmDelegateBtn").attr("data-id", $id); //pass data id to confirm button
				 });

				 $("#confirmDelegateBtn").on("click", function(){
					 var $id = $(this).attr('data-id');
					 $("#raciForm").attr("name", "formSubmit");
					 $("#raciForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
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

				// RFC SCRIPT

				$("body").on('click','.rfcBtn',function()
				 {
					 var $id = $(this).attr('data-id');
					 var $date = $(this).attr('data-date');
					 var $title = $(this).attr('data-title');
					 var $start = new Date($(this).attr('data-start'));
					 var $end = new Date($(this).attr('data-end'));
					 var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;
					 $("#rfcSubmit").attr("data-id", $id); //pass data id to confirm button
					 $("#rfcSubmit").attr("data-date", $date); //pass data date boolean to confirm button
					 $("#rfcTitle").html($title);
					 $("#rfcDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
					 if($diff>1)
					 	$("#rfcDates").append(" days)");
					 else
					 	$("#rfcDates").append(" day)");
				 });

				 $("#rfcSubmit").click(function()
				 {
					 var $id = $(this).attr('data-id');
					 $("#requestForm").attr("name", "formSubmit");
					 $("#requestForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
				 });

				 $("body").on('change','#rfcType',function(){
					 if($(this).val() == "1") //if Change Task Performer is selected
					 {
						 $("#rfcForm").show();
						 $("#newDateDiv").hide();
						 $("#rfcReason").show();
						 $("#startDate").attr("required", false);
						 $("#endDate").attr("required", false);
					 }
					 else // if Change Task Dates is selected
					 {
						 $("#rfcForm").show();
						 $("#newDateDiv").show();
						 $("#rfcReason").show();

						 if($("#rfcSubmit").attr('data-date') == '1') // IF TASK IS ONGOING
						 {
							 $(".start").hide();
							 $("#endDate").attr("required", true);
						 }
						 else
						 {
							 $(".start").show();
							 $(".end").show();
							 $("#endDate").attr("required", true);
						 }
					 }
				 });

				 // DONE SCRIPT

				 $("body").on('click','.doneBtn',function(){
					$("#remarks").val("");
					var $id = $(this).attr('data-id');
					var $title = $(this).attr('data-title');
					var $start = new Date($(this).attr('data-start'));
					var $end = new Date($(this).attr('data-end'));
					var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;
					$("#doneTitle").html($title);
					$("#doneDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
					if($diff > 1)
						$("#doneDates").append(" days)");
					else
						$("#doneDates").append(" day)");
					$("#doneConfirm").attr("data-id", $id); //pass data id to confirm button
					var isDelayed = $(this).attr('data-delay'); // 1 = delayed
					if(isDelayed == '0')
					{
						$("#delayed").hide();
						$("#early").show();
						$("#remarks").attr("required", false);
						$("#remarks").attr("placeholder", "Enter remarks (optional)");
					}
					else
					{
						$("#early").hide();
						$("#delayed").show();
						$("#remarks").attr("required", true);
						$("#remarks").attr("placeholder", "Why were you not able to accomplish the task before the target date?");
					}
				});

				$("body").on('click','#doneConfirm',function(){
					var $id = $("#doneConfirm").attr('data-id');
					$("#doneForm").attr("name", "formSubmit");
					$("#doneForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
				});



			});


		</script>

	</body>
</html>
