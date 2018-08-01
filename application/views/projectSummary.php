<html>
	<head>
		<title>Kernel - Project Summary</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectSummaryStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse">

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div style="margin-bottom:10px">
					<form action = 'projectGantt' id="back" method="POST" style="display:inline-block">
					</form>
					<a id ="backToProject" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Project"><i class="fa fa-arrow-left"></i></a>
				</div>
				<h1>
					<?php echo $project['PROJECTTITLE']; ?> - Project Summary
					<!-- <small>What can I improve on the next project?</small> -->
					<small>What happened to this project?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="row">
					<div class="col-md-9 col-sm-6 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Details</h3>
							</div>

							<!-- /.box-header -->
							<div class="box-body">
								<div style="display:inline-block">
									<?php
									$actualenddate = date_create($project['PROJECTACTUALENDDATE']);
									?>
									<p>Date of Completion: <b><?php echo date_format($actualenddate, "F d, Y"); ?></b></p>
									<p>Total number of main activities: <b><?php echo count($mainActivity); ?></b></p>
									<p>Total number of sub activities: <b><?php echo count($subActivity); ?></b></p>
									<p>Total number of tasks: <b><?php echo count($tasks); ?></b></p>
									<?php

										$delayCounter = 0;
										$earlyCounter = 0;

										foreach ($groupedTasks as $row)
										{
											if($row['TASKADJUSTEDENDDATE'] == null)
												$endDate = $row['TASKENDDATE'];
											else
												$endDate = $row['TASKADJUSTEDENDDATE'];

											if ($row['TASKACTUALENDDATE'] < $endDate)
												$earlyCounter++;

											if ($row['TASKACTUALENDDATE'] > $endDate)
												$delayCounter++;
										}
									?>
									<p>Total number of delayed tasks:<b> <?php echo $delayCounter;?></b></p>
									<p>Total number of early tasks:<b> <?php echo $earlyCounter;?></b></p>
								</div>

								<div style="display:inline-block; margin-left: 50px;">
									<p>Total number of requests: <b><?php echo count($changeRequests);?></b></p>
									<?php

										$approvedCounter = 0;
										$deniedCounter = 0;
										$pendingCounter = 0;
										$dateCounter = 0;
										$performerCounter = 0;

										foreach ($changeRequests as $request)
										{
											if($request['REQUESTSTATUS'] == 'Approved' )
												$approvedCounter++;
											if($request['REQUESTSTATUS'] == 'Denied' )
												$deniedCounter++;
											if($request['REQUESTSTATUS'] == 'Pending' )
												$pendingCounter++;

											if($request['REQUESTTYPE'] == '1' )
												$performerCounter++;
											else
												$dateCounter++;
										}
									?>
									<p style="text-indent:5%">Change Performer: <b><?php echo $performerCounter;?></b></p>
									<p style="text-indent:5%">Change End Date: <b><?php echo $dateCounter;?></b></p>
									<p>Total number of approved requests: <b><?php echo $approvedCounter;?></b></p>
									<p>Total number of denied requests: <b><?php echo $deniedCounter;?></b></p>
									<p>Total number of missed requests: <b><?php echo $pendingCounter;?></b></p>
								</div>

								<div style="display:inline-block; margin-left: 30px;">
									<p>Total number of documents: <b><?php echo count($documents);?></b></p>

								</div>
							</div>
						</div>

					</div>
	        <!-- /.col -->
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Project Performance</h3>
							</div>
							<!-- /.box-header -->

							<!-- <div class="box-body">
								<div style="text-align:center;">
									<div class="circlechart"
										data-percentage="<?php
											if($projectCompleteness['completeness'] == NULL){
												echo 0;
											} else {
												if($projectCompleteness['completeness'] == 100.00){
													echo 100;
												} elseif ($projectCompleteness['completeness'] == 0.00) {
													echo 0;
												} else {
													echo $projectCompleteness['completeness'];
												}
											}
											?>"> Completeness
									</div>
								</div>
							</div> -->

							<div class="box-body">
								<div style="text-align:center;">
									<div class="circlechart"
									 data-percentage="<?php
										 if($projectTimeliness['timeliness'] == NULL){
											 echo 0;
										 } else {
											 if($projectTimeliness['timeliness'] == 100.00){
												 echo 100;
											 } elseif ($projectTimeliness['timeliness'] == 0.00) {
												 echo 0;
											 } else {
												 echo $projectTimeliness['timeliness'];
											 }
										 }
										 ?>"> Timeliness
									</div>
								</div>
							</div>
						</div>
					</div>
	        <!-- /.col -->
				</div>

				<!-- ALL DEPARTMENTS INVOLVED IN THE PROJECT -->
				<div class="row" id="deptPerformance">

					<?php foreach($departments as $department):?>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title"><?php echo $department['DEPARTMENTNAME'];?></h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div style="text-align:center;">
										<div class="circlechart" id="completeness"
										data-percentage="<?php
 										 if($department['timeliness'] == NULL){
 											 echo 0;
 										 } else {
 											 if($department['timeliness'] == 100.00){
 												 echo 100;
 											 } elseif ($department['timeliness'] == 0.00) {
 												 echo 0;
 											 } else {
 												 echo $department['timeliness'];
 											 }
 										 }
 										 ?>"> Timeliness</div>
			 					 </div>
								</div>
							</div>
		        </div>
					<?php endforeach;?>

				</div>

				<!-- DELAYED TASKS -->
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Delayed Tasks</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<table class="table table-hover no-margin" id="">
									<thead>
										<tr>
											<th width="20%">Task</th>
											<th width="15%">Department</th>
											<th width="15%">Actor</th>
											<th width="10%">Target<br>End Date</th>
											<th width="10%">Actual<br>End Date</th>
											<th width="5%">Days Delayed</th>
											<th width="25">Reason for Delay</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
				</div>

				<!-- Requests -->
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">All Requests</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<table class="table table-hover no-margin" id="">
									<thead>
										<tr>
											<th width="0%">Task</th>
											<th width="0%">Department</th>
											<th width="0%">Actor</th>
											<th width="0%">Type</th>
											<th width="0%">Approver</th>
											<th width="0%">Request Date</th>
											<th width="0%">Resolution Date</th>
											<th width="0%">Status</th>
											<th width="0">Reason for Request</th>
											<th width="0">Approver Remarks</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>Approved</td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
				</div>

				<!-- TASKS WITH REMARKS -->
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Tasks with remarks</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<table class="table table-hover no-margin" id="">
									<thead>
										<tr>
											<th width="30%">Task</th>
											<th width="15%">Department</th>
											<th width="15%">Actor</th>
											<th width="10%">Completion Date</th>
											<th width="30%">Remarks</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
				</div>
			</section>
		</div>
		  <?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
		  $("#myProjects").addClass("active");
			$('.circlechart').circlechart(); // Initialization

			$(document).on("click", "#backToProject", function() {
				var $id = <?php echo $project['PROJECTID']; ?>;
				$("#back").attr("name", "formSubmit");
				$("#back").append("<input type='hidden' name='project_ID' value= " + $id + ">");
				$("#back").submit();
				});

		</script>



	</body>
</html>
