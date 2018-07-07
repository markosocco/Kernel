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
					<!-- <div id="filterButtons">
						<h5>Arrange by</h5>
					</div> -->
							<div class="box">
								<div class="box-header">
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
											<th>Period <small>(Day/s)</small></th>
											<?php if($_SESSION['usertype_USERTYPEID'] != '5'):?>
												<th><i class="fa fa-users" style="margin-left:50%"></i></th>
											<?php endif;?>
											<th><i class="fa fa-warning" style="margin-left:50%"></i></th>
											<th><i class="fa fa-check" style="margin-left:50%"></i></th>
										</tr>
										</thead>
										<tbody id="taskTable">

										</tbody>
									</table>
								</div>
								<!-- /.box-body -->
							</div>
							<!-- /.box -->

		        </div>

						<!-- RFC MODAL -->
						<div class="modal fade" id="modal-request" tabindex="-1">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h4 class="modal-title">Request for Change</h4>
		              </div>
		              <div class="modal-body">
		                <form>
											<div class="form-group">
			                  <label>Request Type</label>
			                  <select class="form-control">
													<option disabled selected value> -- Select Request Type -- </option>
			                    <option>Change Task Performer</option>
			                    <option>Change Task Dates</option>
			                  </select>
			                </div>

											<!-- DISPLAY IF CHANGE TASK DATE OPTION -->
											<!-- IF()...AJAX? -->
											<div class="form-group">
				                <label>New Start Date</label>

				                <div class="input-group date">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control pull-right" id="startDate" name="startDate" required>
				                </div>
				                <!-- /.input group -->
				              </div>
				              <!-- /.form group -->
				              <div class="form-group">
				                <label>New Target End Date</label>

				                <div class="input-group date">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control pull-right" id="endDate" name ="endDate" required>
				                </div>
				                <!-- /.input group -->
				              </div>

											<!-- DISPLAY ON BOTH OPTIONS -->
											<div class="form-group">
			                  <label>Reason</label>
			                  <textarea class="form-control" placeholder="State your reason here"></textarea>
			                </div>
										</form>
		              </div>
		              <div class="modal-footer">
		                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
		                <button type="button" class="btn btn-success"><i class="fa fa-check"></i> Submit Request</button>
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
												<div class="form-group" id = "responsibleDiv">

													<select id = "depts" class="form-control" name = "department[]" align="center" required>
													<option disabled selected value> -- Select Department -- </option>

													<?php foreach ($departments as $row): ?>

														<option>
															<?php echo $row['DEPARTMENTNAME']; ?>
														</option>

													<?php endforeach; ?>
													</select>

												<table id="responsibleList" class="table table-bordered table-hover">
													<thead>
													<tr>
														<th></th>
														<th>Name</th>
														<th align="center">No. of Projects</th>
														<th align="center">Progress</th>
														<th></th>
													</tr>
													</thead>
													<tbody>
														<?php foreach($deptEmployees as $employee):?>
															<tr>
																<td><div class="radio">
							                    <label>
																		<input class = "radioEmp" type="radio" name="deptEmployees[]" value="<?php echo $employee['USERID'];?>">
							                    </label>
							                  </div></td>
																<td><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?></td>
																<td align="center">N</td>
																<td align="center">N%</td>
																<td class="btn moreInfo"><a class="btn moreBtn" data-toggle="modal" data-target="#modal-moreInfo"><i class="fa fa-info-circle"></i> More Info</a></td>
															</tr>
														<?php endforeach;?>
													</tbody>
												</table>
											</div>

											<div class="form-group" id = "accountableDiv">

												<select id = "depts" class="form-control" name = "department[]" align="center" required>
												<option disabled selected value> -- Select Department -- </option>

												<?php foreach ($departments as $row): ?>

													<option>
														<?php echo $row['DEPARTMENTNAME']; ?>
													</option>

												<?php endforeach; ?>
												</select>

											<table id="accountableList" class="table table-bordered table-hover">
												<thead>
												<tr>
													<th></th>
													<th>Name</th>
													<th align="center">No. of Projects</th>
													<th align="center">Progress</th>
													<th></th>
												</tr>
												</thead>
												<tbody>
													<?php foreach($wholeDept as $employee):?>
														<tr>
															<td><div class="radio">
																<label>
																	<input class = "radioEmp" type="radio" name="deptEmployees[]" value="<?php echo $employee['USERID'];?>">
																</label>
															</div></td>
															<td><?php echo $employee['FIRSTNAME'] . " " .  $employee['LASTNAME'];?></td>
															<td align="center">N</td>
															<td align="center">N%</td>
															<td class="btn moreInfo"><a class="btn moreBtn" data-toggle="modal" data-target="#modal-moreInfo"><i class="fa fa-info-circle"></i> More Info</a></td>
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
										<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-delegateConfirm"><i class="fa fa-check"></i> Delegate Task</button>
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
										<h4 class="modal-title">Confirmation</h4>
									</div>
									<div class="modal-body">
										<p>Are you sure you want to delegate this task?</p>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
										<button type="button" class="btn btn-success"><i class="fa fa-check"></i> Confirm</button>
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
										<h2 class="modal-title">Task Name here</h2>
										<h4>Start Date - End Date (Days)</h4>
									</div>
									<div class="modal-body">
										<div class="box">
											<div class="box-header">
												<h3 class="box-title">Project Title 1 Tasks</h3>
											</div>
											<!-- /.box-header -->
											<div class="box-body table-responsive no-padding">
												<table class="table table-hover">
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
													<tr>
														<td>Poop today</td>
														<td>80%</td>
													</tr>
												</table>
											</div>
											<!-- /.box-body -->
										</div>
										<!-- /.box -->

										<div class="box">
											<div class="box-header">
												<h3 class="box-title">Project Title 2 Tasks</h3>
											</div>
											<!-- /.box-header -->
											<div class="box-body table-responsive no-padding">
												<table class="table table-hover">
													<tr>
														<td>Poop tomorrow</td>
														<td>0%</td>
													</tr>
												</table>
											</div>
											<!-- /.box-body -->
										</div>
										<!-- /.box -->


									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default pull-right" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
										<!-- <button type="button" class="btn btn-success"><i class="fa fa-check"></i> Confirm</button> -->
									</div>
								</div>
								<!-- /.modal-content -->
							</div>
							<!-- /.modal-dialog -->
						</div>
						<!-- /.modal -->

						<!-- DONE MODAL -->
						<div id = "doneModal" class="modal fade" id="modal-done" tabindex="-1">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h2 class="modal-title" id = "doneTitle">Task Finished</h2>
										<h4 id="doneDates">Start Date - End Date (Days)</h4>
		              </div>
		              <div class="modal-body">
										<h3 id ="delayed" style="color:red">Task is Delayed</h3>
										<h4 id ="early">Are you sure this task is done?</h4>
										<form id = "doneForm" action="myTasks" method="POST">
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
				<?php require("footer.php"); ?>
					</div>
		</div>
		<script>
			$("#myTasks").addClass("active");
			$('.select2').select2();
			$("#responsible").addClass("active");


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
				 $("#doneDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff +" day/s)");
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
				 $("#doneModal").modal("show");
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
				 $(".taskDates").html(moment($start).format('MMMM DD, YYYY') + " - " + moment($end).format('MMMM DD, YYYY') + " ("+ $diff +" day/s)");
			 });

			 $("#depts").change(function(){
				 // $(".responsibleDiv").hide();
			 });

			 $("body").on("click", function(){ // REMOVE ALL SELECTED IN DELEGATE MODAL
				 if($("#modal-delegate").css("display") == 'none')
				 {
					 $("#depts").val("");
					 $(".radioEmp").prop("checked", false);
				 }
			 });

			 $("body").on('click','.radioEmp',function(){
			 });

			 $("#responsible").on("click", function(){
				 $(".raciBtn").removeClass('active');
				 $(this).addClass("active");

			 });

			 $("#accountable").on("click", function(){
				 $(".raciBtn").removeClass('active');
				 $(this).addClass("active");
				 $("#responsibleDiv").hide();
			 });

			 $("#consulted").on("click", function(){
				 $(".raciBtn").removeClass('active');
				 $(this).addClass("active");

			 });

			 $("#informed").on("click", function(){
				 $(".raciBtn").removeClass('active');
				 $(this).addClass("active");

			 });

		 });

		 $(function () {

			 loadTasks();

			 function loadTasks(){

				 $.ajax({
					 type:"POST",
					 url: "<?php echo base_url("index.php/controller/doneTask"); ?>",
					 dataType: 'json',
					 success:function(data)
					 {
						 var table;
						 for(i=0; i<data['tasks'].length; i++)
 						 {
 						 	var taskDuration = parseInt(data['tasks'][i].taskDuration);
							var taskStart = moment(data['tasks'][i].TASKSTARTDATE).format('MMM DD, YYYY');
							var taskEnd = moment(data['tasks'][i].TASKENDDATE).format('MMM DD, YYYY');
 							 table += "<tr>" +
 							 							"<td>" + data['tasks'][i].TASKTITLE+"</td>"+
 														"<td>" + data['tasks'][i].PROJECTTITLE+"</td>"+
 														"<td align='center'>" + taskStart +"</td>"+
 														"<td align='center'>" + taskEnd +"</td>"+
 														"<td align='center'>" + taskDuration+"</td>";

								// DELEGATE BUTTON
 								if(data['tasks'][i].users_USERID == '4' && data['tasks'][i].ROLE == '1') //SHOW BUTTON for assignment
								{
									table+='<td align="center"><button type="button" class="btn btn-primary btn-sm delegateBtn"' +
												'data-toggle="modal" data-target="#modal-delegate" data-id="' +
												data['tasks'][i].TASKID + '" data-title="' + data['tasks'][i].TASKTITLE +
												'" data-start="'+ data['tasks'][i].TASKSTARTDATE +
												'" data-end="'+ data['tasks'][i].TASKENDDATE +'">' +
												'<i class="fa fa-users"></i> Delegate</button></td>';
								}
								else if (data['users'][0].userType != '5')
									table+= '<td></td>';

								// RFC & DONE BUTTON
								if(data['tasks'][i].currentDate >= data['tasks'][i].TASKSTARTDATE) //SHOW BUTTON IF ONOGING TASK
								{
									// RFC
									table+= '<td align="center"><button type="button"' +
									'class="btn btn-warning btn-sm rfcBtn" data-toggle="modal"' +
									'data-target="#modal-request"><i class="fa fa-warning"></i>' +
									' RFC</button></td>';

									var isDelayed = data['tasks'][i].currentDate >= data['tasks'][i].TASKENDDATE;
									// DONE
									table+= '<td align="center"><button type="button"' +
									'class="btn btn-success btn-sm doneBtn" data-toggle="modal"' +
									'data-target="#modal-done" data-id="' + data['tasks'][i].TASKID +
									'" data-title="' + data['tasks'][i].TASKTITLE + '"' +
									'data-delay="' + isDelayed + '" data-start="'+ data['tasks'][i].TASKSTARTDATE +
									'" data-end="'+ data['tasks'][i].TASKENDDATE +'">' +
									'<i class="fa fa-check"></i> Done</button></td>';
								}
								else
									table+= '<td></td>' + '<td></td>';
					 		}
							$('#taskTable').html(table);
						},
						error:function()
						{
							alert("Sorry. I'm trying AJAX")
						}
				 });
			 }


			 $('#taskList').DataTable({
				 'paging'      : false,
				 'lengthChange': false,
				 'searching'   : true,
				 'ordering'    : true,
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
		 });

		</script>
	</body>
</html>
