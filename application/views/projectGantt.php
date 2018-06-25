<html>
	<head>
		<title>Kernel - <?php echo  $projectProfile['PROJECTTITLE'];?></title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTasksStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1><?php echo $projectProfile['PROJECTTITLE']; ?></h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<li class="active"><?php echo $projectProfile['PROJECTTITLE']; ?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<h4><i><?php echo $projectProfile['PROJECTDESCRIPTION']; ?></i></h4>
					<div>

						<?php
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
						$current = date_create(date("Y-m-d")); // get current date
						?>

						<h4>Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($enddate, "F d, Y"); ?> (<?php echo $projectProfile['duration'];?> days)</h4>

						<h4 style="color:red">
							<?php if ($current >= $startdate):?>
								<?php echo $projectProfile['remaining'];?> Days Remaining
							<?php else:?>
								<?php echo $projectProfile['launching'];?> Days Remaining before Project Launch
							<?php endif;?>
						</h4>

						<?php echo "Session: " . $_SESSION['projectID']; ?>
						<form name="gantt" action ='projectDocuments' method="POST" id ="prjID">
							<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>

						<!-- IF USING GET METHOD
						<a href="<?php echo base_url("index.php/controller/projectDocuments/?id=") . $projectProfile['PROJECTID']; ?>" name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a> -->

						<a name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a>

						<a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"class="btn btn-default btn-xs"><i class="fa fa-flag"></i> View Logs</a>
					</div>
					<div style="position: relative" class="gantt" id="GanttChartDIV"></div>

						<!-- START OF TASKS -->
						<div class="row">
			        <div class="col-xs-12">
			          <div class="box">
			            <div class="box-header">
			              <h3 class="box-title">Arrange by</h3>
										<button type="button" class="btn btn-info btn-xs" style="margin-left:">Project</button>
										<h3 class="box-title">or</h3>
										<button type="button" class="btn btn-info btn-xs" style="margin-left:">Priority</button>
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
			                  <th align="center">Duration</th>
												<!-- <th>Period<br><span style="font-size:12px">(In Days)</span></th> -->
												<th>Period</th>
												<th>Status</th>
												<th colspan="2">Responsible</th>
			                  <!-- <th align="center"></th>
												<th align="center"></th>
												<th align="center"></th> -->
			                </tr>

											<?php foreach($ganttData as $row):?>
											<tr>
												<td><?php echo $row['TASKTITLE'];?></td>
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
												<td><?php echo $row ['FIRSTNAME'];?> <?php echo $row['LASTNAME'];?></td>
												<!-- HIDE IF STAFF LEVEL AND IF TASK IS NOT ASSIGNED TO USER-->
												<?php if($_SESSION['usertype_USERTYPEID'] != '5' && $row['users_USERID'] == $_SESSION['USERID']):?>
													<td align="center"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-delegate"><i class="fa fa-users"></i> Delegate</button></td>
												<?php else:?>
													<td></td>
												<?php endif;?>
												<!-- HIDE IF TASK IS NOT ASSIGNED TO USER-->
												<?php if($row['users_USERID'] == $_SESSION['USERID']):?>
													<td align="center"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-request"><i class="fa fa-exclamation"></i> RFC</button></td>
													<td align="center"><button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-done"><i class="fa fa-check"></i> Done</button></td>
												<?php endif;?>
											</tr>
										<?php endforeach;?>

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
			                <h4 class="modal-title">Delegate Task to a Team Member</h4>
			              </div>
			              <div class="modal-body">
			                <form>
												<div class="form-group" style="text-align:center">
					                <!-- <label>Select a Team Member</label> -->
					                <select class="form-control select2" style="width: 100%;" data-placeholder=" -- Select a Team Member -- ">
														<option disabled selected value> -- Select a Team Member -- </option>
														<?php foreach($users as $user):?>
															<?php if($user['users_SUPERVISORS'] == $_SESSION['USERID']):?>
																<option><?php echo $user['FIRSTNAME'];?> <?php echo $user['LASTNAME'];?></option>
															<?php endif;?>
														<?php endforeach;?>
					                </select>
					              </div>
											</form>
			              </div>
			              <div class="modal-footer">
			                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
			                <button type="button" class="btn btn-success"><i class="fa fa-check"></i> Delegate Task</button>
			              </div>
			            </div>
			            <!-- /.modal-content -->
			          </div>
			          <!-- /.modal-dialog -->
			        </div>
			        <!-- /.modal -->

							<!-- "DONE" MODAL -->
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
					<!-- </section> -->
				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>
			$("#myProjects").addClass("active");

			// $("#projectDocu").click(function()
			// {
			// 	("#gantt").submit();
      // });

		</script>

		<!-- Javascript for Tasks -->

		<script>

			$('.select2').select2()

			$(function ()
			{
				//Date picker
 	     $('#startDate').datepicker({
 	       autoclose: true
 	     })

 	     $('#endDate').datepicker({
 	       autoclose: true
 	     })
		  })

			$("#projectDocu").click(function() //redirect to individual project profile
      {
				// var $id = $(this).attr('data-id');
				$("#prjID").submit();
        // window.location.replace("<?php echo base_url("index.php/controller/projectGantt/?id="); ?>" + $id);
      });
		</script>

			<!-- Javascript for Gantt Chart -->
	    <script src="<?php echo base_url()."assets/"; ?>jsgantt/jsgantt.js" type="text/javascript"></script>
	    <script type="text/javascript">

		    var g = new JSGantt.GanttChart(document.getElementById('GanttChartDIV'), 'day');

		    <?php

		    foreach($ganttData as $row)
		    {

		      $startDate = $row['TASKSTARTDATE'];

		      $startMonth = substr($startDate, 0, 2);
		      $startDay = substr($startDate, 3, 2);
		      $startYear = substr($startDate, 6, 4);
		      $formattedStartDate = $startYear . "-" . $startMonth . "-" . $startDay;

		      $endDate = $row['TASKENDDATE'];

		      $endMonth = substr($endDate, 0, 2);
		      $endDay = substr($endDate, 3, 2);
		      $endYear = substr($endDate, 6, 4);
		      $formattedEndDate = $endYear . "-" . $endMonth . "-" . $endDay;

		// CHANGING OF DEPARTMENT NAME
		      if($row['DEPARTMENTNAME'] == "Facilities Administration")
		      {
		        $departmentName = 'FAD';
		      } else {
		        $departmentName = $row['DEPARTMENTNAME'];
		      }

		        $parent = 1;
		        $hasChildren = false;
		        $completion = 100;
		        $MAcounter = 0;
						$group = 0;

		// FOR GROUPING
					$currentTask = $row['TASKID'];
		      $group = 0;
					$isParent = 0; // 1 = true, 0 = false
					foreach ($ganttData as $data) {
						if($data['tasks_TASKPARENT'] == $currentTask ){
							$group = 1;
						}
					}

		// FOR PARENT
					if($row['tasks_TASKPARENT'] == NULL){
						$parent = 0;
					} else {
						$parent = $row['tasks_TASKPARENT'];
					}

		// FOR COMPLETION
					if($row['TASKSTATUS'] == 'Pending'){
						$complete = 0;
					} else {
						$complete = 100;
					}

		// TODO: CHECK FOR PREREQ
				$dependency = '';
				foreach ($dependencies as $data) {
					if($currentTask == $data['tasks_POSTTASKID']){
						if($row['TASKSTARTDATE'] == $data["TASKSTARTDATE"])
						{
							echo "console.log('".$row['TASKSTARTDATE']." = ".$data["TASKSTARTDATE"]."');";
							$dependency = $data['PRETASKID'];

							echo "g.AddTaskItem(new JSGantt.TaskItem(" . $row['TASKID'] . ", '" .
				      $row['TASKTITLE'] . "','" . $formattedStartDate . "','" . $formattedEndDate . "'," .
				      "'gtaskRed', '', 0, '" . $departmentName . "', " . $complete . ", " . $group . ", " .
							$parent . ", 1, '". $dependency."SS', '', '', g));";
						}
					}
				}


		      echo "g.AddTaskItem(new JSGantt.TaskItem(" . $row['TASKID'] . ", '" .
		      $row['TASKTITLE'] . "','" . $formattedStartDate . "','" . $formattedEndDate . "'," .
		      "'gtaskRed', '', 0, '" . $departmentName . "', " . $complete . ", " . $group . ", " .
					$parent . ", 1, '". $dependency."', '', '', g));";

		    }

		    echo "g.Draw();";

		    ?>
		</script>
	</body>
</html>
