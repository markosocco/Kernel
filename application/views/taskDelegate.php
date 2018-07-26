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

											<div class="form-group raciDiv" id = "responsibleDiv">
											<table id="responsibleList" class="table table-bordered table-hover">
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
																		<input class = "radioEmp" type="radio" name="responsibleEmp" value="<?php echo $department['DEPARTMENTID'];?>" required>
																	</label>
																</div>
																</td>
																<td>
																	<div class="checkbox">
																	<label>
																		<input class = "checkEmp" type="checkbox" name="responsibleEmp" value="<?php echo $department['DEPARTMENTID'];?>" required>
																	</label>
																</div>
																</td>
																<td>
																	<div class="checkbox">
																	<label>
																		<input class = "checkEmp" type="checkbox" name="responsibleEmp" value="<?php echo $department['DEPARTMENTID'];?>" required>
																	</label>
																</div>
																</td>
																<td>
																	<div class="checkbox">
																	<label>
																		<input class = "checkEmp" type="checkbox" name="responsibleEmp" value="<?php echo $department['DEPARTMENTID'];?>" required>
																	</label>
																</div>
																</td>
															</tr>
														<?endif;?>
													<?php endforeach;?>
												</tbody>
											</table>
											</div>

											<!-- RESPONSIBLE DIV -->
											<div class="form-group raciDiv" id = "responsibleDiv">
											<table id="responsibleList" class="table table-bordered table-hover">
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
													<?php foreach($deptEmployees as $employee):?>
														<tr>
															<td class='moreInfo'><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?></td>
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
																	<input class = "checkEmp" type="checkbox" name="responsibleEmp" value="<?php echo $employee['USERID'];?>" required>
																</label>
															</div>
															</td>
															<td>
																<div class="checkbox">
																<label>
																	<input class = "checkEmp" type="checkbox" name="responsibleEmp" value="<?php echo $employee['USERID'];?>" required>
																</label>
															</div>
															</td>
															<td>
																<div class="checkbox">
																<label>
																	<input class = "checkEmp" type="checkbox" name="responsibleEmp" value="<?php echo $employee['USERID'];?>" required>
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

															<!-- <td class="btn moreInfo" data-id="<?php echo $employee['USERID'];?>"
																data-name="<?php echo $employee['FIRSTNAME'];?> <?php echo $employee['LASTNAME'];?>"
																data-projectCount = "<?php echo $hasProjects;?>"
																data-taskCount = "<?php echo $hasTasks;?>">
																<a class="btn moreBtn" data-toggle="modal">
																<i class="fa fa-info-circle"></i> More Info</a>
															</td> -->
														</tr>
													<?php endforeach;?>
												</tbody>
											</table>
											</div>



									<!-- /.box-body -->
								</div>
							<!-- </div> -->

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
					$("#viewAll").html("View All Tasks >>");
				else
					$("#viewAll").html("Hide All Tasks >>");
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

		</script>
	</body>
</html>
