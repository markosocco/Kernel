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
						<small>What do I need to do</small>
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

					<div class="row">
		        <div class="col-xs-12">
		          <div class="box">
		            <div class="box-header">
		              <h3 class="box-title">Arrange by</h3>

									<div class = "btn-group">
										<button type="button" id = "filterPriority" class="btn btn-info btn-xs" style="margin-left:">Priority</button>
										<button type="button" id = "filterProject" class="btn btn-info btn-xs" style="margin-left:">Project</button>
										<button type="button" id = "filterStatus" class="btn btn-info btn-xs" style="margin-left:">Status</button>
									</div>

									<form id = 'arrangeForm' name = "filter" action = 'myTasks' method="POST">
										<input type = "hidden" class = "filterID">
									</form>
									
									<div class="box-tools">
		                <div class="input-group input-group-sm" style="width: 150px;">
		                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

		                  <div class="input-group-btn">
		                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
		                  </div>
		                </div>
		              </div>
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body table-responsive no-padding">
		              <table class="table table-hover">
		                <tr>
		                  <th>Task</th>
		                  <th>Project</th>
		                  <th align="center">Duration</th>
											<!-- <th>Period<br><span style="font-size:12px">(In Days)</span></th> -->
											<th>Period</th>
											<th>Status</th>
		                </tr>

										<?php foreach($tasks as $row):?>
										<tr>
											<td><?php echo $row['TASKTITLE'];?></td>
											<td><?php echo $row['PROJECTTITLE'];?></td>
											<td><?php echo $row['TASKSTARTDATE'];?> - <?php echo $row['TASKENDDATE'];?></td>

											<!-- PERIOD COMPUTATION -->
											<?php // compute for days remaining and fix date format
											$taskstartdate = date_create($row['TASKSTARTDATE']);
											$taskenddate = date_create($row['TASKENDDATE']);
											$taskedate = date_format($taskenddate, "Y-m-d");
											$tasksdate = date_format($taskstartdate, "Y-m-d");
											$taskenddate2 = date_create($taskedate);
											$taskstartdate2 = date_create($tasksdate);
											$taskperiod = date_diff($taskstartdate2, $taskenddate2);
											?>

											<td align = "center"><?php echo $taskperiod->format('%a');?> Days</td>
											<td><?php echo $row['TASKSTATUS'];?></td>
											<td align="center"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-request"><i class="fa fa-exclamation"></i> RFC</button></td>
											<td align="center"><button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-done"><i class="fa fa-check"></i> Done</button></td>
										</tr>
									<?php endforeach;?>

		              </table>
		            </div>
		            <!-- /.box-body -->
		          </div>
		          <!-- /.box -->
		        </div>

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

						<!-- <div class="modal fade" id="modal-delegate">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h4 class="modal-title">Delegate Task to a Team Member</h4>
		              </div>
		              <div class="modal-body">
		                <form>
											<div class="form-group" style="text-align:center">
				                <!-- <label>Select a Team Member</label>
				                <select class="form-control select2" style="width: 100%;" data-placeholder=" -- Select a Team Member -- ">
													<option disabled selected value> -- Select Request Type -- </option>
													<option>Loop through members under the same supervisor</option>
													<option>With the session user excluding session owner</option>
				                </select>
				              </div>
										</form>
		              </div>
		              <div class="modal-footer">
		                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
		                <button type="button" class="btn btn-success"><i class="fa fa-check"></i> Delegate Task</button>
		              </div>
		            </div>
		            <!-- /.modal-content
		          </div>
		          <!-- /.modal-dialog
		        </div>
		        <!-- /.modal -->

						<div class="modal fade" id="modal-done" tabindex="-1">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h4 class="modal-title">Task Finished</h4>
		              </div>
		              <div class="modal-body">
										<!-- DISPLAY IF CURRDATE>TASKENDDATE -->
										<h3 style="color:red">Task is Delayed</h3>
		                <form>
											<div class="form-group">
			                  <textarea class="form-control" placeholder="State the reason for the delay"></textarea>
			                </div>
										</form>
										<p>Are you sure that this task is done?</p>

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
 	       autoclose: true
 	     })

 	     $('#endDate').datepicker({
 	       autoclose: true
 	     })
		 });

		 $(document).ready(function()
		 {
		 	$("#filterProject").click(function()
		 	{
				$(".filterID").html("<input type='hidden' name='filterID' value='projects.PROJECTTITLE'>");
				$("#arrangeForm").submit();
		 	});

			$("#filterPriority").click(function()
			{
				$(".filterID").html("<input type='hidden' name='filterID' value='tasks.TASKSTARTDATE'>");
				$("#arrangeForm").submit();
			});

			$("#filterStatus").click(function()
			{
				$(".filterID").html("<input type='hidden' name='filterID' value='tasks.TASKSTATUS'>");
				$("#arrangeForm").submit();
			});
		 });
		</script>
	</body>
</html>
