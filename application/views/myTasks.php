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
	          <?php $dateToday = date('F d, Y | l');?>
	          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
	        </ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">

					<?php if($mainActivity == null && $subActivity == null && $tasks == null && $ACItasks == null):?>
						<h3 class="box-title" style="text-align:center">You have no tasks</h3>
					<?php endif;?>

					<!-- MAIN ACTIVITY TABLE -->

					<?php if($mainActivity != null):?> <!-- Show only if there are main activities assigned -->

						<div class="box box-danger" id="mainActivityBox">
							<div class="box-header">
								<h3 class="box-title">Main Activities</h3>
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
											<th>Period<br><small>(Day/s)</small></th>
											<th class="text-center"><i class="fa fa-edit"></i></th>
											<?php if($_SESSION['usertype_USERTYPEID'] != '5'):?>
												<th class="text-center"><i class="fa fa-users"></i></th>
											<?php endif;?>
										</tr>
									</thead>
									<form id = "addMain" action = 'projectGantt' method="POST">
										<input type ='hidden' name='myTasks' value='0'>
									</form>
									<tbody id="mainActivityTable">
									</tbody>
								</table>
							</div>
						</div>
					<?php endif;?>

					<!-- SUB ACTIVITY TABLE -->

					<?php if($subActivity != null):?> <!-- Show only if there are sub activities assigned -->

						<div class="box box-danger" id="subActivityBox">
							<div class="box-header">
								<h3 class="box-title">Sub Activities</h3>
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
											<th>Period<br><small>(Day/s)</small></th>
											<th class="text-center"><i class="fa fa-edit"></i></th>
											<?php if($_SESSION['usertype_USERTYPEID'] != '5'):?>
												<th class="text-center"><i class="fa fa-users"></i></th>
											<?php endif;?>
										</tr>
									</thead>
									<form id = "addSub" action = 'projectGantt' method="POST">
										<input type ='hidden' name='myTasks' value='0'>
									</form>
									<tbody id="subActivityTable">
									</tbody>
								</table>
							</div>
						</div>
					<?php endif;?>

					<?php if($tasks != null):?> <!-- Show only if there are tasks assigned -->
						<!-- TASK-R TABLE -->
						<div class="box box-danger" id="taskBox">
							<div class="box-header">
								<h3 class="box-title">Tasks - Responsible</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-body">
								<table id="taskList" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>Task</th>
											<th>Project</th>
											<th>Start Date</th>
											<th>Target End Date</th>
											<th>Period<br><small>(Day/s)</small></th>
											<?php if($_SESSION['usertype_USERTYPEID'] != '5'):?>
												<th class="text-center"><i class="fa fa-users"></i></th>
											<?php endif;?>
											<th class="text-center"><i class="fa fa-warning"></i></th>
											<th class="text-center"><i class="fa fa-check"></i></th>
										</tr>
									</thead>
									<tbody id="taskTable">
									</tbody>
								</table>
							</div>
						</div>
					<?php endif;?>

					<?php if($ACItasks != null):?> <!-- Show only if there are aci tasks assigned -->
						<!-- ACI TABLE -->
						<div class="box box-danger" id="taskBox">
							<div class="box-header">
								<h3 class="box-title">Tasks - Accountable, Consulted, Informed</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-body">
								<table id="aciList" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th></th>
											<th>Task</th>
											<th>Project</th>
											<th>Start Date</th>
											<th>Target End Date</th>
											<th>Period<br><small>(Day/s)</small></th>
											<th class="text-center"><i class="fa fa-briefcase"></i></th>
										</tr>
									</thead>

									<form id = "viewProject" action = 'projectGantt' method="POST">
										<input type ='hidden' name='myTasks' value='0'>
									</form>

									<tbody id="aciTable">
										<?php foreach($ACItasks as $ACItask):?>
											<?php switch($ACItask['ROLE'])
												{
													case 1: $role = 'R'; break;
													case 2: $role = 'A'; break;
													case 3: $role = 'C'; break;
													case 4: $role = 'I'; break;
													default: $role = 'A'; break;
												}
											;?>

										<?php
											if($ACItask['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
												$startDate = date_create($ACItask['TASKSTARTDATE']);
											else
												$startDate = date_create($ACItask['TASKADJUSTEDSTARTDATE']);

											if($ACItask['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
												$endDate = date_create($ACItask['TASKENDDATE']);
											else
												$endDate = date_create($ACItask['TASKADJUSTEDENDDATE']);

											if($ACItask['TASKADJUSTEDSTARTDATE'] != null && $ACItask['TASKADJUSTEDENDDATE'] != null)
												$taskDuration = $ACItask['adjustedTaskDuration2'];
											elseif($ACItask['TASKSTARTDATE'] != null && $ACItask['TASKADJUSTEDENDDATE'] != null)
												$taskDuration = $ACItask['adjustedTaskDuration1'];
											else
												$taskDuration = $ACItask['initialTaskDuration'];
										?>

										<tr>
											<td><?php echo $role;?></td>
											<td><?php echo $ACItask['TASKTITLE'];?></td>
											<td><?php echo $ACItask['PROJECTTITLE'];?></td>
											<td><?php echo date_format($startDate, "M d, Y");?></td>
											<td><?php echo date_format($endDate, "M d, Y");?></td>
											<td><?php echo $taskDuration;?></td>
											<td align="center"><button type="button" class="btn btn-primary btn-sm viewProjectBtn"
												data-id="<?php echo $ACItask['PROJECTID'];?>"><i class="fa fa-briefcase"></i> View Project</button>
											</td>
										</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					<?php endif;?>

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
																<?php $hasProjects = false;?>
																<?php foreach($projectCountR as $count): ;?>
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

																<?php $hasTasks = false;?>
																<?php foreach($taskCountR as $count): ;?>
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
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
										<button type="button" class="btn btn-success delegate" data-toggle="modal" data-target="#modal-delegateConfirm"><i class="fa fa-check"></i> Delegate Task</button>
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
										<button type="button" id="backWorkload" class="btn btn-default pull-left"><i class="fa fa-arrow-left"></i> Back</button>
									</div>

								</div>

								<!-- CONFIRM DELEGATE -->
								<div id="delegateConfirm">
									<div class="modal-body">
										<h4>Are you sure you want to delegate this task?</h4>
									</div>
									<div class="modal-footer">
										<button id="backConfirm" type="button" class="btn btn-default pull-left"><i class="fa fa-close"></i> Cancel</button>
										<button id = "confirmDelegateBtn" type="submit" class="btn btn-success" data-id=""><i class="fa fa-check"></i> Confirm</button>
									</div>
								</div>

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

			$(function ()
			{
				//Date picker
 	     $('#startDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true,
				 startDate: new Date(),
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
				 'searching'   : true,
				 'ordering'    : true,
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
				 'searching'   : true,
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
						$("#raciDelegate").show();
						$("#workloadAssessment").hide();
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

				// AJAX LOAD TASKS

				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/loadTasks"); ?>",
					dataType: 'json',
					success:function(data)
					{
						// MAIN ACTIVITY TABLE
						if(data['mainActivity'].length > 0)
						{
							$('#mainActivityTable').html("");
							for (var m = 0; m < data['mainActivity'].length; m++)
							{
								var taskID = data['mainActivity'][m].TASKID;

								if(data['mainActivity'][m].TASKADJUSTEDSTARTDATE == null) // check if start date has been previously adjusted
									var taskStart = moment(data['mainActivity'][m].TASKSTARTDATE).format('MMM DD, YYYY');
								else
									var taskStart = moment(data['mainActivity'][m].TASKADJUSTEDSTARTDATE).format('MMM DD, YYYY');

								if(data['mainActivity'][m].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
									var taskEnd = moment(data['mainActivity'][m].TASKENDDATE).format('MMM DD, YYYY');
								else
									var taskEnd = moment(data['mainActivity'][m].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');

								if(data['mainActivity'][m].TASKADJUSTEDSTARTDATE != null && data['mainActivity'][m].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(data['mainActivity'][m].adjustedTaskDuration2);
								if(data['mainActivity'][m].TASKSTARTDATE != null && data['mainActivity'][m].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(data['mainActivity'][m].adjustedTaskDuration1);
								else
									var taskDuration = parseInt(data['mainActivity'][m].initialTaskDuration);

								if (data['mainActivity'][m].usertype_USERTYPEID != 5)
								{
									$('#mainActivityTable').append(
															 "<tr>" +
															 "<td>" + data['mainActivity'][m].TASKTITLE +"</td>"+
															 "<td>" + data['mainActivity'][m].PROJECTTITLE+"</td>"+
															 "<td align='center'>" + taskStart +"</td>"+
															 "<td align='center'>" + taskEnd +"</td>"+
															 "<td align='center'>" + taskDuration +"</td>" +
															 '<td align="center"><button type="button" data-id="' + data['mainActivity'][m].PROJECTID +
															 '" class="btn btn-info btn-sm addMainsBtn"' +
				 											 'data-id="1"><i class="fa fa-edit"></i> Edit Project</button></td>' +
															 '<td align="center"><button type="button" class="btn btn-primary btn-sm delegateBtn"' +
															 'data-toggle="modal" data-target="#modal-delegate" data-id="' +
															 data['mainActivity'][m].TASKID + '" data-title="' + data['mainActivity'][m].TASKTITLE +
															 '" data-start="'+ taskStart +
															 '" data-end="'+ taskEnd +'">' +
															 '<i class="fa fa-users"></i> Delegate</button></td>'+ '</tr>');
								}
								else
								{
									$('#mainActivityTable').append(
															 "<tr>" +
															 "<td>" + data['mainActivity'][m].TASKTITLE +"</td>"+
															 "<td>" + data['mainActivity'][m].PROJECTTITLE+"</td>"+
															 "<td align='center'>" + taskStart +"</td>"+
															 "<td align='center'>" + taskEnd +"</td>"+
															 "<td align='center'>" + taskDuration +"</td>" +
															 '<td align="center"><button type="button" data-id="' + data['mainActivity'][m].PROJECTID +
															 '" class="btn btn-info btn-sm addMainsBtn"' +
															 'data-id="1"><i class="fa fa-edit"></i> Edit Project</button></td></tr>');
								}
							} // end of main activity for loop
						} // end of main activity if statement

						// SUB ACTIVITY TABLE
						if(data['subActivity'].length > 0)
						{
							$('#subActivityTable').html("");
							for (var s = 0; s < data['subActivity'].length; s++)
							{
								var taskID = data['subActivity'][s].TASKID;

								if(data['subActivity'][s].TASKADJUSTEDSTARTDATE == null) // check if start date has been previously adjusted
									var taskStart = moment(data['subActivity'][s].TASKSTARTDATE).format('MMM DD, YYYY');
								else
									var taskStart = moment(data['subActivity'][s].TASKADJUSTEDSTARTDATE).format('MMM DD, YYYY');

								if(data['subActivity'][s].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
									var taskEnd = moment(data['subActivity'][s].TASKENDDATE).format('MMM DD, YYYY');
								else
									var taskEnd = moment(data['subActivity'][s].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');

								if(data['subActivity'][s].TASKADJUSTEDSTARTDATE != null && data['subActivity'][s].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(data['subActivity'][s].adjustedTaskDuration2);
								if(data['subActivity'][s].TASKSTARTDATE != null && data['subActivity'][s].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(data['subActivity'][s].adjustedTaskDuration1);
								else
									var taskDuration = parseInt(data['subActivity'][s].initialTaskDuration);

								if (data['subActivity'][s].usertype_USERTYPEID != 5)
								{
									$('#subActivityTable').append(
															 "<tr><td>" + data['subActivity'][s].TASKTITLE +"</td>"+
															 "<td>" + data['subActivity'][s].PROJECTTITLE+"</td>"+
															 "<td align='center'>" + taskStart +"</td>"+
															 "<td align='center'>" + taskEnd +"</td>"+
															 "<td align='center'>" + taskDuration +"</td>" +
															 "<td align='center'><button type='button' data-id='" + data['subActivity'][s].PROJECTID +
															 "' class='btn btn-info btn-sm addSubsBtn'" +
				 											 "data-id='1'><i class='fa fa-edit'></i> Edit Project</button>" +
															 "<td align='center'><button type='button' class='btn btn-primary btn-sm delegateBtn'" +
															 "data-toggle='modal' data-target='#modal-delegate' data-id='" +
															 data['subActivity'][s].TASKID + "' data-title='" + data['subActivity'][s].TASKTITLE +
															 "' data-start='" + taskStart +
															 "' data-end='"+ taskEnd + "'>" +
															 "<i class='fa fa-users'></i> Delegate</button></td>" + "</tr>");
								}
								else
								{
									$('#subActivityTable').append(
															 "<tr><td>" + data['subActivity'][s].TASKTITLE +"</td>"+
															 "<td>" + data['subActivity'][s].PROJECTTITLE+"</td>"+
															 "<td align='center'>" + taskStart +"</td>"+
															 "<td align='center'>" + taskEnd +"</td>"+
															 "<td align='center'>" + taskDuration +"</td>" +
															 "<td align='center'><button type='button' data-id='" + data['subActivity'][s].PROJECTID +
															 "' class='btn btn-info btn-sm addSubsBtn'" +
				 											 "data-id='1'><i class='fa fa-edit'></i> Edit Project</button></tr>");
								}


							} // end of sub activity for loop
						}	// end of sub activity if statement

						// TASKS TABLE
						if(data['tasks'].length > 0)
						{
							$('#taskTable').html("");
							for(i=0; i<data['tasks'].length; i++)
							{
								if(data['tasks'][i].TASKADJUSTEDSTARTDATE == null) // check if start date has been previously adjusted
								{
									var taskStart = moment(data['tasks'][i].TASKSTARTDATE).format('MMM DD, YYYY');
									var startDate = data['tasks'][i].TASKSTARTDATE;
								}
								else
								{
									var taskStart = moment(data['tasks'][i].TASKADJUSTEDSTARTDATE).format('MMM DD, YYYY');
									var startDate = data['tasks'][i].TASKADJUSTEDSTARTDATE;
								}

								if(data['tasks'][i].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
								{
									var taskEnd = moment(data['tasks'][i].TASKENDDATE).format('MMM DD, YYYY');
									var endDate = data['tasks'][i].TASKENDDATE;
								}
								else
								{
									var taskEnd = moment(data['tasks'][i].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');
									var endDate = data['tasks'][i].TASKADJUSTEDENDDATE;
								}

								if(data['tasks'][i].TASKADJUSTEDSTARTDATE != null && data['tasks'][i].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(data['tasks'][i].adjustedTaskDuration2);
								if(data['tasks'][i].TASKSTARTDATE != null && data['tasks'][i].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(data['tasks'][i].adjustedTaskDuration1);
								else
									var taskDuration = parseInt(data['tasks'][i].initialTaskDuration);

								$('#taskTable').append(
														 "<tr id='" + data['tasks'][i].TASKID + "'>" +
														 "<td>" + data['tasks'][i].TASKTITLE+"</td>"+
														 "<td>" + data['tasks'][i].PROJECTTITLE+"</td>"+
														 "<td align='center'>" + taskStart +"</td>"+
														 "<td align='center'>" + taskEnd +"</td>"+
														 "<td align='center'>" + taskDuration +"</td>");

								 // DELEGATE BUTTON
								 if(data['tasks'][i].users_USERID == <?php echo $_SESSION['USERID'] ;?> && data['tasks'][i].ROLE == '1' && data['tasks'][i].usertype_USERTYPEID != '5') //SHOW BUTTON for assignment
								 {
									 $('#' +data['tasks'][i].TASKID).append(
												 '<td align="center"><button type="button" class="btn btn-primary btn-sm delegateBtn"' +
												 'data-toggle="modal" data-target="#modal-delegate" data-id="' +
												 data['tasks'][i].TASKID + '" data-title="' + data['tasks'][i].TASKTITLE +
												 '" data-start="'+ startDate +
												 '" data-end="'+ endDate +'">' +
												 '<i class="fa fa-users"></i> Delegate</button></td>');
								 }

								 // RFC & DONE BUTTON
								 if(data['tasks'][i].currentDate >= data['tasks'][i].PROJECTSTARTDATE) //SHOW BUTTON IF ONGOING PROJECT
								 {
									 var ongoing = data['tasks'][i].currentDate >= data['tasks'][i].TASKSTARTDATE; //CHECK IF ONGOING TASK

										// RFC
	 									$('#' + data['tasks'][i].TASKID).append(
	 	 									'<td align="center"><button type="button"' +
	 										'class="btn btn-warning btn-sm rfcBtn" data-toggle="modal"' +
	 										'data-target="#modal-request" data-id="' + data['tasks'][i].TASKID +
	 										'" data-date="' + ongoing + '" data-title="' + data['tasks'][i].TASKTITLE + '"' +
	 										' data-start="'+ startDate +
	 										'" data-end="'+ endDate +'"><i class="fa fa-warning"></i>' +
	 										' RFC</button></td>');

											// DONE
											var isDelayed = data['tasks'][i].currentDate >= data['tasks'][i].TASKENDDATE;
											var taskID = data['tasks'][i].TASKID;
											var taskTitle = data['tasks'][i].TASKTITLE;

											if(!ongoing)
											{
												$('#' + taskID).append(
													 '<td align="center"><button disabled type="button"' +
													 'class="btn btn-success btn-sm doneBtn" data-toggle="modal"' +
													 'data-target="#modal-done" data-id="' + taskID +
													 '" data-title="' + taskTitle + '"' +
													 'data-delay="' + isDelayed + '" data-start="'+ taskStart +
													 '" data-end="'+ taskEnd +'">' +
													 '<i class="fa fa-check"></i> Done</button></td></tr>');
											}
											else
											{
												// AJAX TO CHECK IF DEPENDENCIES ARE COMPLETE
												$.ajax({
							 					 type:"POST",
							 					 url: "<?php echo base_url("index.php/controller/getDependenciesByTaskID"); ?>",
												 data: {task_ID: taskID},
							 					 dataType: 'json',
							 					 success:function(dependencyData)
							 					 {
													 var taskID = dependencyData['taskID'].TASKID;
													 var taskTitle = dependencyData['taskID'].TASKTITLE;
													 var startDate = moment(dependencyData['taskID'].TASKSTARTDATE).format('MMM DD, YYYY');
													 var endDate = moment(dependencyData['taskID'].TASKENDDATE).format('MMM DD, YYYY');
													 var isDelayed = dependencyData['taskID'].currentDate > dependencyData['taskID'].TASKENDDATE;

													 if(dependencyData['dependencies'].length > 0)
													 {
														 var isComplete = 'true';
														 for (var d = 0; d < dependencyData['dependencies'].length; d++)
														 {
															 if(dependencyData['dependencies'][d].TASKSTATUS != 'Complete') // if there is a pre-requisite task that is ongoing
															 {
																 isComplete = 'false';
															 }
														 }

														 if(isComplete == 'true' ) // if all pre-requisite tasks are complete, task can be marked done
														 {
															 $('#' + dependencyData['taskID'].TASKID).append(
				 													'<td align="center"><button type="button"' +
																	'class="btn btn-success btn-sm doneBtn" data-toggle="modal"' +
																	'data-target="#modal-done" data-id="' + taskID +
																	'" data-title="' + taskTitle + '"' +
																	'data-delay="' + isDelayed + '" data-start="'+ startDate +
																	'" data-end="'+ endDate +'">' +
																	'<i class="fa fa-check"></i> Done</button></td></tr>');
														 }
														 else
														 {
															 $('#' + dependencyData['taskID'].TASKID).append(
																'<td align="center"><button disabled type="button"' +
															 	'class="btn btn-success btn-sm doneBtn" data-toggle="modal"' +
															 	'data-target="#modal-done" data-id="' + taskID +
															 	'" data-title="' + taskTitle + '"' +
															 	'data-delay="' + isDelayed + '" data-start="'+ startDate +
															 	'" data-end="'+ endDate +'">' +
															 	'<i class="fa fa-check"></i> Done</button></td></tr>');
														 }

													 }
													 else // if task has no prerequisites
													 {
														 $('#' + dependencyData['taskID'].TASKID).append(
				 												'<td align="center"><button type="button"' +
																'class="btn btn-success btn-sm doneBtn" data-toggle="modal"' +
																'data-target="#modal-done" data-id="' + taskID +
																'" data-title="' + taskTitle + '"' +
																'data-delay="' + isDelayed + '" data-start="'+ startDate +
																'" data-end="'+ endDate +'">' +
																'<i class="fa fa-check"></i> Done</button></td>');
													 }
												 },
												 error:function()
												 {
													 alert("There was a problem in checking the task dependencies");
												 }
											 }); // end of dependencies ajax
											}
								 } // end of if statment if ongoing
								 else
								 {
									 $('#' + data['tasks'][i].TASKID).append('<td></td>' + '<td></td>'); // NO DONE & RFC BUTTON (Project is not ongoing)
								 }

							 } // end of tasks for loop
						 }
					},
					error:function()
					{
						alert("There was a problem in retrieving the tasks");
					}
				});

				// DELEGATE SCRIPT

				$("#responsible").addClass("active");
				$(".raciDiv").hide();
				$("#responsibleDiv").show();
				$("#delegateConfirm").hide();


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
						$("#confirmDelegateBtn").attr("data-id", $id); //pass data id to confirm button
				 });

				 $("body").on('click','.delegate',function(){
					 $("#raciDelegate").hide();
					 $("#delegateConfirm").show();
				 });

				 $("#confirmDelegateBtn").on("click", function(){
					 var $id = $(this).attr('data-id');
					 $("#raciForm").attr("name", "formSubmit");
					 $("#raciForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
					 $("#raciForm").submit();
				 });

 				$("#backConfirm").click(function()
				{
 					$("#raciDelegate").show();
 					$("#delegateConfirm").hide();
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

				// WORKLOAD ASSESSMENT SCRIPT

				$("#workloadAssessment").hide();

				$(".moreInfo").click(function(){

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

					$("#raciDelegate").hide();
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

				// RFC SCRIPT

				$("#rfcForm").hide();

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

						 if($("#rfcSubmit").attr('data-date') == 'true') // IF TASK IS ONGOING
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
					var isDelayed = $(this).attr('data-delay'); // true = delayed
					if(isDelayed == 'false')
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

				// ACI SCRIPT
				$("body").on('click','.viewProjectBtn',function(){
					var $id = $(this).attr('data-id');
					$("#viewProject").attr("name", "formSubmit");
					$("#viewProject").append("<input type='hidden' name='project_ID' value= " + $id + ">");
					$("#viewProject").submit();
				});

				$("body").on('click','.addMainsBtn',function(){
					var $id = $(this).attr('data-id');
					$("#addMain").attr("name", "formSubmit");
					$("#addMain").append("<input type='hidden' name='project_ID' value= " + $id + ">");
					$("#addMain").submit();
				});

				$("body").on('click','.addSubsBtn',function(){
					var $id = $(this).attr('data-id');
					$("#addSub").attr("name", "formSubmit");
					$("#addSub").append("<input type='hidden' name='project_ID' value= " + $id + ">");
					$("#addSub").submit();
				});

			});
		</script>

	</body>
</html>
