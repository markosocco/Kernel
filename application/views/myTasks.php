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

					<div class="box" id="activityBox">
						<div class="box-header">
							<h3 class="box-title text-blue">Main and Sub Activities</h3>
						</div>
						<!-- /.box-header -->

						<div class="box-body">
							<!-- MAIN ACTIVITY TABLE -->
							<table id="activityList" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>R</th>
										<th>Activity Type</th>
										<th>Activity Title</th>
										<th>Project</th>
										<th>Start Date</th>
										<th>Target End Date</th>
										<th>Period <small>(Day/s)</small></th>
										<?php if($_SESSION['usertype_USERTYPEID'] != '5'):?>
											<th><i class="fa fa-users" style="margin-left:50%"></i></th>
										<?php endif;?>
									</tr>
								</thead>

								<tbody id="activityTable">
									<tr>
										<td>R</td>
										<td>Main/Sub</td>
										<td>Activity</td>
										<td>Project</td>
										<td>Start Date</td>
										<td>End Date</td>
										<td>Period</td>
										<td>Delegate</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<div class="box" id="taskBox">
						<div class="box-header">
							<h3 class="box-title text-blue">Tasks</h3>
						</div>
						<!-- /.box-header -->

						<div class="box-body">
							<!-- TASK TABLE -->
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
									<!-- AJAX HERE -->
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
			$('.select2').select2()
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
 	     })

 	     $('#endDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true,
				 startDate: new Date(),
				 orientation: 'bottom'
 	     })
		 });

		 $(document).ready(function() {

			 // MARK TASK AS DONE
			 $("body").on('click','.doneBtn',function(){
				 $("#remarks").val("");
				 var $id = $(this).attr('data-id');
				 var $title = $(this).attr('data-title');
				 var $start = new Date($(this).attr('data-start'));
				 var $end = new Date($(this).attr('data-end'));
				 var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;
				 $("#doneTitle").html($title);
				 $("#doneDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
				 if($diff <= '1')
				  $("#doneDates").append(" day)");
				 else
				  $("#doneDates").append(" days)");
				 $("#doneConfirm").attr("data-id", $id); //pass data id to confirm button
				 var isDelayed = $(this).attr('data-delay'); // 1 = delayed
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

			 $("body").on('click','.delegateBtn',function(){
				 var $id = $(this).attr('data-id');
				 var $title = $(this).attr('data-title');
				 var $start = new Date($(this).attr('data-start'));
				 var $end = new Date($(this).attr('data-end'));
				 var $diff = (($end - $start)/ 1000 / 60 / 60 / 24)+1;

				 $(".taskTitle").html($title);
				 $(".taskDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff);
				 if($diff <= '1')
				  $(".taskDates").append(" day)");
				 else
				  $(".taskDates").append(" days)");
			 });

			 $("body").on("click", function(){ // REMOVE ALL SELECTED IN DELEGATE MODAL & RFC
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

			 $("body").on('click','.delegateBtn',function(){
				 var $id = $(this).attr('data-id');
				 $("#confirmDelegateBtn").attr("data-id", $id); //pass data id to confirm button
			 });

			 $("#confirmDelegateBtn").on("click", function(){
				 var $id = $(this).attr('data-id');
				 $("#raciForm").attr("name", "formSubmit");
				 $("#raciForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
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

				 var $id = $(this).attr('data-id');
				 var $projectCount = $(this).attr('data-projectCount');
				 $("#workloadEmployee").html($(this).attr('data-name'));
				 $("#workloadProjects").html("Total Number of Project/s: " + $projectCount);
				 $('#workloadDiv').html("");
				 var info;

				 if($projectCount > 0)
				 {
					 // GET PROJECTS OF USER
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
				 }
				 else
				 {
					 $('#workloadDiv').html("<h4 class = 'box-title' style='text-align:center'> No projects to show</h4>");
				 }
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
				 if($diff <= '1')
				 	$("#rfcDates").append(" day)");
				else
					$("#rfcDates").append(" days)");
			 });

			 $("#rfcSubmit").click(function()
			 {
				 var $id = $(this).attr('data-id');
				 $("#requestForm").attr("name", "formSubmit");
				 $("#requestForm").append("<input type='hidden' name='task_ID' value= " + $id + ">");
			 });

		 });

		 $(function () {

			 loadTasks();

			 function loadTasks(){

				 $.ajax({
					 type:"POST",
					 url: "<?php echo base_url("index.php/controller/loadTasks"); ?>",
					 dataType: 'json',
					 success:function(data)
					 {
						 if(data['mainActivity'].length > 0 && data['subActivity'].length > 0)
						 {
							 $('#activityTable').html("");
							 if(data['mainActivity'].length > 0)
							 {
								 var role;

								 // MAIN ACTIVITY TABLE
								 for (var m = 0; m < data['mainActivity'].length; m++)
								 {
									 var taskID = data['mainActivity'][m].TASKID;
									 var taskDuration = parseInt(data['mainActivity'][m].taskDuration);
									 var taskStart = moment(data['mainActivity'][m].TASKSTARTDATE).format('MMM DD, YYYY');
									 var taskEnd = moment(data['mainActivity'][m].TASKENDDATE).format('MMM DD, YYYY');
									 switch(data['mainActivity'][m].ROLE)
		 							{
		 								case "1": role = "R"; break;
		 								case "2": role = "A"; break;
		 								case "3": role = "C"; break;
		 								case "4": role = "I"; break;
		 							}

		 							 $('#activityTable').append(
										 						"<tr id='" + taskID + "-" + role + "'>" +
																"<td>" + role + "</td>" +
									 							"<td align='center'>Main</td>" +
		 							 							"<td>" + data['mainActivity'][m].TASKTITLE +"</td>"+
		 														"<td>" + data['mainActivity'][m].PROJECTTITLE+"</td>"+
		 														"<td align='center'>" + taskStart +"</td>"+
		 														"<td align='center'>" + taskEnd +"</td>"+
		 														"<td align='center'>" + taskDuration+"</td>");

										// DELEGATE BUTTON
		 								if(data['mainActivity'][m].users_USERID == <?php echo $_SESSION['USERID'] ;?> && data['mainActivity'][m].ROLE == '1') //SHOW BUTTON for assignment
										{
											$('#' + taskID + "-" + role).append(
														'<td align="center"><button type="button" class="btn btn-primary btn-sm delegateBtn"' +
														'data-toggle="modal" data-target="#modal-delegate" data-id="' +
														taskID + '" data-title="' + data['mainActivity'][m].TASKTITLE +
														'" data-start="'+ data['mainActivity'][m].TASKSTARTDATE +
														'" data-end="'+ data['mainActivity'][m].TASKENDDATE +'">' +
														'<i class="fa fa-users"></i> Delegate</button></td></tr>');
										}
										else
											$('#' + taskID + "-" + role).append("<td></td></tr>");
								 }
							 }
							 if(data['subActivity'].length > 0)
							 {
								 // SUB ACTIVITY TABLE
								 for (var s = 0; s < data['subActivity'].length; s++)
								 {
									 var taskID = data['subActivity'][s].TASKID;
									 var taskDuration = parseInt(data['subActivity'][s].taskDuration);
									 var taskStart = moment(data['subActivity'][s].TASKSTARTDATE).format('MMM DD, YYYY');
									 var taskEnd = moment(data['subActivity'][s].TASKENDDATE).format('MMM DD, YYYY');
									 switch(data['subActivity'][s].ROLE)
									{
										case "1": role = "R"; break;
										case "2": role = "A"; break;
										case "3": role = "C"; break;
										case "4": role = "I"; break;
									}

		 							 $('#activityTable').append(
										 						"<tr id='" + taskID + "-" + role + "'>" +
																"<td>" + role + "</td>" +
									 							"<td align='center'>Sub</td>" +
		 							 							"<td>" + data['subActivity'][s].TASKTITLE +"</td>"+
		 														"<td>" + data['subActivity'][s].PROJECTTITLE+"</td>"+
		 														"<td align='center'>" + taskStart +"</td>"+
		 														"<td align='center'>" + taskEnd +"</td>"+
		 														"<td align='center'>" + taskDuration+"</td>");

										// DELEGATE BUTTON
		 								if(data['subActivity'][s].users_USERID == <?php echo $_SESSION['USERID'] ;?> && data['subActivity'][s].ROLE == '1') //SHOW BUTTON for assignment
										{
											$('#' + taskID + "-" + role).append(
														'<td align="center"><button type="button" class="btn btn-primary btn-sm delegateBtn"' +
														'data-toggle="modal" data-target="#modal-delegate" data-id="' +
														taskID + '" data-title="' + data['subActivity'][s].TASKTITLE +
														'" data-start="'+ data['subActivity'][s].TASKSTARTDATE +
														'" data-end="'+ data['subActivity'][s].TASKENDDATE +'">' +
														'<i class="fa fa-users"></i> Delegate</button></td></tr>');
										}
										else
											$('#' + taskID + "-" + role).append("<td></td></tr>");
								 }
							 }
						 }
						 else
						 {
							 $("#activityBox").hide(); //hide box if no main or sub activity is found
						 }

						 if(data['tasks'].length > 0)
						 {
							 // TASK TABLE
							 var table;
							 var role;
							 $('#taskTable').html("");
							 for(i=0; i<data['tasks'].length; i++)
	 						 {
								 var taskDuration = parseInt(data['tasks'][i].taskDuration);
								 var taskStart = moment(data['tasks'][i].TASKSTARTDATE).format('MMM DD, YYYY');
								 var taskEnd = moment(data['tasks'][i].TASKENDDATE).format('MMM DD, YYYY');

								switch(data['tasks'][i].ROLE)
								{
									case "1": role = "R"; break;
									case "2": role = "A"; break;
									case "3": role = "C"; break;
									case "4": role = "I"; break;
								}
	 							 $('#taskTable').append(
									 						"<tr id='" + data['tasks'][i].TASKID + "'>" +
								 							"<td align='center'>" + role + "</td>" +
	 							 							"<td>" + data['tasks'][i].TASKTITLE+"</td>"+
	 														"<td>" + data['tasks'][i].PROJECTTITLE+"</td>"+
	 														"<td align='center'>" + taskStart +"</td>"+
	 														"<td align='center'>" + taskEnd +"</td>"+
	 														"<td align='center'>" + taskDuration+"</td>");

									var startDate = data['tasks'][i].TASKSTARTDATE;
									var endDate = data['tasks'][i].TASKENDDATE;

									// DELEGATE BUTTON
	 								if(data['tasks'][i].users_USERID == <?php echo $_SESSION['USERID'] ;?> && data['tasks'][i].ROLE == '1') //SHOW BUTTON for assignment
									{
										$('#' +data['tasks'][i].TASKID).append(
													'<td align="center"><button type="button" class="btn btn-primary btn-sm delegateBtn"' +
													'data-toggle="modal" data-target="#modal-delegate" data-id="' +
													data['tasks'][i].TASKID + '" data-title="' + data['tasks'][i].TASKTITLE +
													'" data-start="'+ startDate +
													'" data-end="'+ endDate +'">' +
													'<i class="fa fa-users"></i> Delegate</button></td>');
									}
									else if (data['users'][0].userType != '5')
										$('#' + data['tasks'][i].TASKID).append('<td></td>');

									// RFC & DONE BUTTON
									if(data['tasks'][i].currentDate >= data['tasks'][i].PROJECTSTARTDATE) //SHOW BUTTON IF ONGOING PROJECT
									{
										var newDate = data['tasks'][i].currentDate >= data['tasks'][i].TASKSTARTDATE; //CHECK IF ONGOING TASK

										// RFC
										$('#' + data['tasks'][i].TASKID).append(
		 									'<td align="center"><button type="button"' +
											'class="btn btn-warning btn-sm rfcBtn" data-toggle="modal"' +
											'data-target="#modal-request" data-id="' + data['tasks'][i].TASKID +
											'" data-date="' + newDate + '" data-title="' + data['tasks'][i].TASKTITLE + '"' +
											' data-start="'+ startDate +
											'" data-end="'+ endDate +'"><i class="fa fa-warning"></i>' +
											' RFC</button></td>');

										if(data['tasks'][i].users_USERID == <?php echo $_SESSION['USERID'] ;?> && data['tasks'][i].ROLE == '1' && data['tasks'][i].CATEGORY == '3') //SHOW BUTTON for assignment
										{
											var isDelayed = data['tasks'][i].currentDate >= data['tasks'][i].TASKENDDATE;
											// DONE

											var taskID = data['tasks'][i].TASKID;
											var taskTitle = data['tasks'][i].TASKTITLE;
											var index = i;

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
													 for (var d = 0; d < dependencyData['dependencies'].length; d++)
													 {
														 var isComplete = 'true';
														 if(dependencyData['dependencies'][d].TASKSTATUS == 'Ongoing') // if there is a pre-requisite task that is ongoing
														 {
															 isComplete = 'false';
														 }
													 }
													 if(isComplete == 'true') // if all pre-requisite tasks are complete, task can be marked done
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
													 $('#' + dependencyData['taskID'].TASKID).append("<td></td>");
												 }
												 else
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
												 alert("There was a problem in retrieving the task dependencies");
											 }
										 });
										}
										else
										{
											$('#' + data['tasks'][i].TASKID).append("<td></td>"); // NO DONE BUTTON IF ROLE IS NOT RESPONSIBLE
										}
									}
									else
									$('#' + data['tasks'][i].TASKID).append('<td></td>' + '<td></td>'); // NO DONE & RFC BUTTON (Project is not ongoing)
						 		}
						 }
						 else
						 {
							 $("#taskBox").hide(); //hide box if no main or sub activity is found
						 }
						},
						error:function(XMLHTTPREQUEST)
						{
							alert("There was a problem in retrieving tasks");
						}
				 });
			 }

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
		 });

		</script>
	</body>
</html>
