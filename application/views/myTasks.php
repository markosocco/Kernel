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
											<th>Status</th>
											<th></th>
											<th></th>
											<th></th>
										</tr>
										</thead>
										<tbody>
											<?php foreach($tasks as $row):?>
											<tr>
												<?php // to fix date format
												$taskstartdate = date_create($row['TASKSTARTDATE']);
												$taskenddate = date_create($row['TASKENDDATE']);
												?>

												<td><?php echo $row['TASKTITLE'];?></td>
												<td><?php echo $row['PROJECTTITLE'];?></td>
												<td><?php echo date_format($taskstartdate, "M d, Y");?></td>
												<td><?php echo date_format($taskenddate, "M d, Y");?></td>
												<td align = "center"><?php echo $row['taskDuration']+1;?></td>
												<td><?php echo $row['TASKSTATUS'];?></td>
												<?php if($_SESSION['usertype_USERTYPEID'] != '5' && $row['users_USERID'] == $_SESSION['USERID']):?>
													<td align="center"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-delegate"><i class="fa fa-users"></i> Delegate</button></td>
												<?php else:?>
													<td></td>
												<?php endif;?>
												<?php if($row['CURDATE()'] >= $row['TASKSTARTDATE'] && $row['PROJECTSTATUS'] != "Complete"):?>
												<td align="center"><button type="button" class="btn btn-warning btn-sm rfcBtn" data-toggle="modal" data-target="#modal-request"><i class="fa fa-exclamation"></i> RFC</button></td>
												<td align="center"><button type="button" class="btn btn-success btn-sm doneBtn" data-toggle="modal" data-target="#modal-done"
													data-id="<?php echo $row['TASKID'];?>" data-title="<?php echo $row['TASKTITLE'];?>" data-delay="<?php echo $row['CURDATE()'] >= $row['TASKENDDATE'];?>"><i class="fa fa-check"></i> Done</button></td>
												<?php else:?>
													<td></td>
													<td></td>
												<?php endif;?>
											</tr>
										<?php endforeach;?>
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
										<h2 class="modal-title">Task Name here</h2>
										<h4>Start Date - End Date (Days)</h4>
									</div>
									<div class="modal-body">
										<div class="box">
											<div class="box-header" style="display:inline-block">
												<h3 class="box-title">
													<div class="btn-group">
														<button type="button" class="btn btn-default btn-sm" id="responsible">Responsible</button>
														<button type="button" class="btn btn-default btn-sm" id="accountable">Accountable</button>
														<button type="button" class="btn btn-default btn-sm" id="consulted">Consulted</button>
														<button type="button" class="btn btn-default btn-sm" id="informed">Informed</button>
													</div>

												</h3>
											</div>
											<!-- /.box-header -->
											<div class="box-body">
												<div class="form-group">

													<select class="form-control" name = "department[]" align="center" required>
													<option disabled selected value> -- Select Department -- </option>

													<?php foreach ($departments as $row): ?>

														<option>
															<?php echo $row['DEPARTMENTNAME']; ?>
														</option>

													<?php endforeach; ?>
													</select>
												</div>

												<table id="teamList" class="table table-bordered table-hover">
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
														<td><div class="radio">
					                    <label>
																<input type="radio" name="" id="" value="">
					                    </label>
					                  </div></td>
														<td>Manuel Cabacaba</td>
														<td align="center">20k</td>
														<td align="center">80%</td>
														<td class="btn" id="moreInfo"><a class="btn moreBtn" data-toggle="modal" data-target="#modal-moreInfo"><i class="fa fa-info-circle"></i> More Info</a></td>
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
		                <h4 class="modal-title" id = "doneTitle">Task Finished</h4>
		              </div>
		              <div class="modal-body">
										<h3 id ="delayed" style="color:red">Task is Delayed</h3>
										<h4 id ="early">Are you sure that this task is done?</h4>
										<form>
											<div class="form-group">
												<textarea id = "remarks" class="form-control" placeholder="Enter remarks"></textarea>
											</div>
										</form>
		              </div>
		              <div class="modal-footer">
		                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
		                <button type="button" class="btn btn-success doneBtn1" data-id=""><i class="fa fa-check"></i> Confirm</button>
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

			$(function ()
			{
				//Date picker
 	     $('#startDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true
 	     })

 	     $('#endDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true
 	     })
		 });

		 $(document).ready(function() {
			 $(".doneBtn").click(function(){
				 var $id = $(this).attr('data-id');
				 var $title = $(this).attr('data-title');
				 $("#doneTitle").html($title);
				 $(".doneBtn1").attr("data-id", $id); //pass data id to confirm button
				 var isDelayed = $(this).attr('data-delay'); // 1 = delayed
				 if(isDelayed != "1")
				 {
					 $("#delayed").hide();
					 $("#early").show();
					 $("#remarks").attr("placeholder", "Enter remarks (optional)");
				 }
				 else
				 {
					 $("#early").hide();
					 $("#delayed").show();
					 $("#remarks").attr("placeholder", "Why were you not able to accomplish the task before the target date?");
				 }

				 $("#doneModal").modal("show");
			 });


		 });

		 $(function () {
			 $('#taskList').DataTable({
				 'paging'      : false,
				 'lengthChange': false,
				 'searching'   : true,
				 'ordering'    : true,
				 'info'        : false,
				 'autoWidth'   : false
			 });
		 });

		 $(function () {
			 $('#employeeList').DataTable({
				 'paging'      : false,
				 'lengthChange': false,
				 'searching'   : true,
				 'ordering'    : true,
				 'info'        : false,
				 'autoWidth'   : false
			 });
			 $('#projectList').DataTable().columns(-1).order('asc').draw();
		 })
		</script>
	</body>
</html>
