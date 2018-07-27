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
				<button id = "viewAll" class="btn btn-default pull-right" data-toggle="tooltip" data-placement="top" title="View All"><i class="fa fa-eye"></i></button>

				<br><br>

				<div id = "filteredTasks">
					<div class="row">
						<!-- TO DO -->

						<?php
							$totalToDoProjects=0;
							$totalToDoTasks=0;
						?>

						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">To Do</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">

						<?php if ($delegateTasksByProject != NULL): ?>
							<?php foreach($delegateTasksByProject as $project):?>
								<?php if($project['threshold'] <= $project['PROJECTSTARTDATE']):?> <!-- show only activities before the project start date -->

										<?php
										$startDate = date_create($project['PROJECTSTARTDATE']);
										$endDate = date_create($project['PROJECTENDDATE']);
										?>

											<?php $totalToDoProjects = $totalToDoProjects+1;?>

											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title">
														<?php echo $project['PROJECTTITLE'];?>
														(<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)
													</h3>
												</div>
												<!-- /.box-header -->
												<div class="box-body">
													<div class="table-responsive">
														<table class="table table-hover no-margin">
															<thead>
															<tr>
																<th colspan='2'>Activity</th>
																<th class="text-center">Start Date</th>
																<th class="text-center">Action</th>
															</tr>
															</thead>
															<tbody id="taskDelegateToDo">


																<?php foreach($delegateTasksByMainActivity as $mainActivity):?>
																	<?php $startdateMain = date_create($mainActivity['TASKSTARTDATE']);?>
																	<?php if($mainActivity['projects_PROJECTID'] == $project['projects_PROJECTID']):?>
																		<?php $totalToDoTasks = $totalToDoTasks+1;?>
																		<tr class="viewProject" data-id="<?php echo $mainActivity['TASKID'] ;?>">
																			<td><?php echo $mainActivity['TASKTITLE'];?></td>
																			<td></td>
																			<td align="center"><?php echo date_format($startdateMain, 'M d, Y');?></td>
																			<td align="center">
																				<button type="button" class="btn bg-teal btn-sm editBtn"
																				data-id="<?php echo $mainActivity['TASKID'];?>" data-toggle="tooltip"
																				data-placement="top" title="Edit">
																					<i class="fa fa-edit"></i>
																				</button>
																				<span data-toggle="modal" data-target="#modal-delegate">
																				<button type="button" class="btn btn-primary btn-sm delegateBtn" data-toggle="tooltip" data-placement="top" title="Delegate"
																				data-id="<?php echo $mainActivity['TASKID'];?>"
																				data-title="<?php echo $mainActivity['TASKTITLE'];?>"
																				data-start="<?php echo $mainActivity['TASKSTARTDATE'];?>"
																				data-end="<?php echo $mainActivity['TASKENDDATE'];?>">
																					<i class="fa fa-users"></i>
																				</button>
																				</span>
																			</td>
																		</tr>
																	<?php endif;?>

																	<?php foreach($delegateTasksBySubActivity as $subActivity):?>
																		<?php $startdateSub = date_create($subActivity['TASKSTARTDATE']);?>

																		<?php if($subActivity['tasks_TASKPARENT'] == $mainActivity['TASKID']):?>
																			<?php if($subActivity['projects_PROJECTID'] == $project['PROJECTID']):?>
																			<?php $totalToDoTasks = $totalToDoTasks+1;?>
																			<tr class="viewProject" data-id="<?php echo $subActivity['TASKID'] ;?>">
																				<td></td>
																				<td><?php echo $subActivity['TASKTITLE'];?></td>
																				<td align="center"><?php echo date_format($startdateSub, 'M d, Y');?></td>
																				<td align="center">
																					<button type="button" class="btn bg-teal btn-sm editBtn"
																					data-id="<?php echo $subActivity['TASKID'];?>" data-toggle="tooltip"
																					data-placement="top" title="Edit">
																						<i class="fa fa-edit"></i>
																					</button>
																					<span data-toggle="modal" data-target="#modal-delegate">
																					<button type="button" class="btn btn-primary btn-sm delegateBtn" data-toggle="tooltip" data-placement="top" title="Delegate"
																					data-id="<?php echo $subActivity['TASKID'];?>"
																					data-title="<?php echo $subActivity['TASKTITLE'];?>"
																					data-start="<?php echo $subActivity['TASKSTARTDATE'];?>"
																					data-end="<?php echo $subActivity['TASKENDDATE'];?>">
																						<i class="fa fa-users"></i>
																					</button>
																					</span>
																				</td>
																			</tr>
																		<?php endif;?>
																	<?php endif;?>
																	<?php endforeach;?> <!-- END SUB ACTIVITY -->

																<?php endforeach;?> <!-- END MAIN ACTIVITY -->

															</tbody>
														</table>
													</div>
												</div>
											</div>
										<?php endif;?>
									<?php endforeach;?> <!-- END PROJECT -->
								<?php endif;?>

									<?php if($totalToDoProjects == 0):?>

										<div class="box-body">
											<h4 align="center">You have no tasks due in 2 days</h4>
										</div>
									<?php endif;?>

								</div>
							</div>
						</div>

						<div class="col-md-2">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="toDoProjects"> Projects <br><br><b><?php echo $totalToDoProjects;?></b></h4>
									</div>
								</div>
							</div>

							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id ="toDoTasks"> Activities <br><br><b><?php echo $totalToDoTasks;?></b></span></h4>
									</div>
								</div>
							</div>
						</div>


					</div> <!-- CLOSING ROW -->
				</div>

				<div id = "allTasks">
					<!-- ALL TASKS -->
					<div class='row'>

						<?php
							$totalToDoProjects=0;
							$totalToDoTasks=0;
						?>

					<div class="col-md-10">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">All Activities</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<?php if ($delegateTasksByProject != NULL): ?>
								<?php foreach($delegateTasksByProject as $project):?>

									<?php
									$startDate = date_create($project['PROJECTSTARTDATE']);
									$endDate = date_create($project['PROJECTENDDATE']);
									?>

										<?php $totalToDoProjects = $totalToDoProjects+1;?>

										<div class="box">
											<div class="box-header with-border">
												<h3 class="box-title">
													<?php echo $project['PROJECTTITLE'];?>
													(<?php echo date_format($startDate, "F d, Y");?> - <?php echo date_format($endDate, "F d, Y");?>)
												</h3>
											</div>
											<!-- /.box-header -->
											<div class="box-body">
												<div class="table-responsive">
													<table class="table table-hover no-margin">
														<thead>
														<tr>
															<th colspan='2'>Activity</th>
															<th class="text-center">Start Date</th>
															<th class="text-center">Action</th>
														</tr>
														</thead>
														<tbody id="taskDelegateToDo">


															<?php foreach($delegateTasksByMainActivity as $mainActivity):?>
																<?php $startdateMain = date_create($mainActivity['TASKSTARTDATE']);?>
																<?php if($mainActivity['projects_PROJECTID'] == $project['projects_PROJECTID']):?>
																	<?php $totalToDoTasks = $totalToDoTasks+1;?>
																	<tr class="viewProject" data-id="<?php echo $mainActivity['TASKID'] ;?>">
																		<td><?php echo $mainActivity['TASKTITLE'];?></td>
																		<td></td>
																		<td align="center"><?php echo date_format($startdateMain, 'M d, Y');?></td>
																		<td align="center">
																			<button type="button" class="btn bg-teal btn-sm editBtn"
																			data-id="<?php echo $mainActivity['TASKID'];?>" data-toggle="tooltip"
																			data-placement="top" title="Edit">
																				<i class="fa fa-edit"></i>
																			</button>
																			<span data-toggle="modal" data-target="#modal-delegate">
																			<button type="button" class="btn btn-primary btn-sm delegateBtn" data-toggle="tooltip" data-placement="top" title="Delegate"
																			data-id="<?php echo $mainActivity['TASKID'];?>"
																			data-title="<?php echo $mainActivity['TASKTITLE'];?>"
																			data-start="<?php echo $mainActivity['TASKSTARTDATE'];?>"
																			data-end="<?php echo $mainActivity['TASKENDDATE'];?>">
																				<i class="fa fa-users"></i>
																			</button>
																			</span>
																		</td>
																	</tr>
																<?php endif;?>

																<?php foreach($delegateTasksBySubActivity as $subActivity):?>
																	<?php $startdateSub = date_create($subActivity['TASKSTARTDATE']);?>

																	<?php if($subActivity['tasks_TASKPARENT'] == $mainActivity['TASKID'] && $subActivity['projects_PROJECTID'] == $mainActivity['projects_PROJECTID']):?>
																		<?php if($subActivity['projects_PROJECTID'] == $project['PROJECTID']):?>
																		<?php $totalToDoTasks = $totalToDoTasks+1;?>
																		<tr class="viewProject" data-id="<?php echo $subActivity['TASKID'] ;?>">
																			<td></td>
																			<td><?php echo $subActivity['TASKTITLE'];?></td>
																			<td align="center"><?php echo date_format($startdateSub, 'M d, Y');?></td>
																			<td align="center">
																				<button type="button" class="btn bg-teal btn-sm editBtn"
																				data-id="<?php echo $subActivity['TASKID'];?>" data-toggle="tooltip"
																				data-placement="top" title="Edit">
																					<i class="fa fa-edit"></i>
																				</button>
																				<span data-toggle="modal" data-target="#modal-delegate">
																				<button type="button" class="btn btn-primary btn-sm delegateBtn" data-toggle="tooltip" data-placement="top" title="Delegate"
																				data-id="<?php echo $subActivity['TASKID'];?>"
																				data-title="<?php echo $subActivity['TASKTITLE'];?>"
																				data-start="<?php echo $subActivity['TASKSTARTDATE'];?>"
																				data-end="<?php echo $subActivity['TASKENDDATE'];?>">
																					<i class="fa fa-users"></i>
																				</button>
																				</span>
																			</td>
																		</tr>
																	<?php endif;?>
																<?php endif;?>
																<?php endforeach;?> <!-- END SUB ACTIVITY -->

															<?php endforeach;?> <!-- END MAIN ACTIVITY -->

														</tbody>
													</table>
												</div>
											</div>
										</div>
								<?php endforeach;?> <!-- END PROJECT -->

							<?php endif;?>

								<?php if($totalToDoProjects == 0):?>

									<div class="box-body">
										<h4 align="center">You have no tasks</h4>
									</div>
								<?php endif;?>

							</div>
						</div>
					</div>

				<div class="col-md-2">
					<div class="box box-danger">
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<h4 align="center" id="toDoProjects"> Projects <br><br><b><?php echo count($delegateTasksByProject);?></b></h4>
							</div>
						</div>
					</div>

					<div class="box box-danger">
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<h4 align="center" id ="toDoTasks"> Activities <br><br><b><?php echo count($delegateTasksByMainActivity) + count($delegateTasksBySubActivity);?></b></span></h4>
							</div>
						</div>
					</div>
				</div>

				</div>

				<form id='viewProject' action = 'projectGantt' method="POST">
					<input type ='hidden' name='delegateTask' value='0'>
				</form>

				<!-- MODALS -->

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
								<!-- <div class="box box-danger"> -->
									<!-- /.box-header -->
									<div class="box-body">
										<form id="raciForm" action="delegateTask" method="POST">

											<div class="form-group raciDiv" id = "deptDiv">
											<table id="deptList" class="table table-bordered table-hover">
												<thead>
												<tr>
													<th>Department</th>
													<th>R</th>
													<th>A</th>
													<th>C</th>
													<th>I</th>
												</tr>
												</thead>

												<tbody>
													<?php foreach($departments as $department):?>
														<?php if($department['DEPARTMENTID'] != $_SESSION['departments_DEPARTMENTID']):?>
															<tr>
																<td><?php echo $department['DEPARTMENTNAME'];?></td>
																<td>
																	<div class="radio">
																	<label>
																		<input class = "radioEmp" type="radio" name="responsibleEmp" value="<?php echo $department['users_DEPARTMENTHEAD'];?>" required>
																	</label>
																</div>
																</td>
																<td>
																	<div class="checkbox">
																	<label>
																		<input class = "checkEmp" type="checkbox" name="accountableEmp" value="<?php echo $department['users_DEPARTMENTHEAD'];?>" required>
																	</label>
																</div>
																</td>
																<td>
																	<div class="checkbox">
																	<label>
																		<input class = "checkEmp" type="checkbox" name="consultedEmp" value="<?php echo $department['users_DEPARTMENTHEAD'];?>" required>
																	</label>
																</div>
																</td>
																<td>
																	<div class="checkbox">
																	<label>
																		<input class = "checkEmp" type="checkbox" name="informedEmp" value="<?php echo $department['users_DEPARTMENTHEAD'];?>" required>
																	</label>
																</div>
																</td>
															</tr>
														<?endif;?>
													<?php endforeach;?>
												</tbody>
											</table>
											</div>

											<!-- TEAM DIV -->
											<div class="form-group raciDiv" id = "teamDiv">
											<table id="teamList" class="table table-bordered table-hover">
												<thead>
												<tr>
													<th>Name</th>
													<th>R</th>
													<th>A</th>
													<th>C</th>
													<th>I</th>
													<th>No. of Projects (Ongoing & Planned)</th>
													<th>No. of Tasks (Ongoing & Planned)</th>
												</tr>
												</thead>

												<tbody>
													<?php foreach($wholeDept as $employee):?>
														<tr>
															<?php $hasProjects = false;?>
															<?php foreach($projectCount as $count): ;?>
																<?php $hasProjects = false;?>
																<?php if ($count['USERID'] == $employee['USERID']):?>
																	<?php $hasProjects = $count['projectCount'];?>
																	<?php break;?>
																<?php endif;?>
															<?php endforeach;?>
															<?php if ($hasProjects <= '0'):?>
																<?php $hasProjects = 0;?>
															<?php endif;?>

															<?php $hasTasks = false;?>
															<?php foreach($taskCount as $count): ;?>
																<?php $hasTasks = false;?>
																<?php if ($count['USERID'] == $employee['USERID']):?>
																	<?php $hasTasks = $count['taskCount'];?>
																	<?php break;?>
																<?php endif;?>
															<?php endforeach;?>
															<?php if ($hasTasks <= '0'):?>
																<?php $hasTasks = 0;?>
															<?php endif;?>

															<td class='moreInfo' data-id="<?php echo $employee['USERID'];?>"
															data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>"
															data-projectCount = "<?php echo $hasProjects;?>"
															data-taskCount = "<?php echo $hasTasks;?>"><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?></td>
															<td>
																<div class="radio">
																<label>
																	<input class = "radioEmp" type="radio" name="responsibleEmp" value="<?php echo $employee['USERID'];?>" required>
																</label>
															</div>
															</td>
															<td>
																<div class="checkbox">
																<label>
																	<input class = "checkEmp" type="checkbox" name="acountableEmp" value="<?php echo $employee['USERID'];?>" required>
																</label>
															</div>
															</td>
															<td>
																<div class="checkbox">
																<label>
																	<input class = "checkEmp" type="checkbox" name="consultedEmp" value="<?php echo $employee['USERID'];?>" required>
																</label>
															</div>
															</td>
															<td>
																<div class="checkbox">
																<label>
																	<input class = "checkEmp" type="checkbox" name="informedEmp" value="<?php echo $employee['USERID'];?>" required>
																</label>
															</div>
															</td>

															<?php $hasProjects = false;?>
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

															<?php $hasTasks = false;?>
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
														</tr>
													<?php endforeach;?>
												</tbody>
											</table>
											</div>



									<!-- /.box-body -->
								</div>
							<!-- </div> -->

							<div class="modal-footer">
								<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i></button>
								<button type="button" class="btn btn-success delegate" data-toggle="modal" data-target="#modal-delegateConfirm"><i class="fa fa-check"></i></button>
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
			</div>


				<!-- END MODALS -->

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
				{
					$("#viewAll").html("<i class='fa fa-eye'></i>");
					$("#viewAll").attr('title', 'View All');
				}
				else
				{
					$("#viewAll").html("<i class='fa fa-eye-slash'></i>");
					$("#viewAll").attr('title', 'Filter');
				}
			});

			// $(document).on("click", ".viewProject", function() {
			// 	var $projectID = $(this).attr('data-id');
			// 	$("#viewProject").attr("name", "formSubmit");
			// 	$("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
			// 	$("#viewProject").submit();
			// });

			$("body").on("click", ".editBtn", function() {
				alert("Forward to Edit Project");
				// var $projectID = $(this).attr('data-id');
				// $("#viewProject").attr("name", "formSubmit");
				// $("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				// $("#viewProject").submit();
			});

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

			 $("#delegateConfirm").hide();
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

		</script>
	</body>
</html>
