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
					<a id ="backToProject" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="Return to Project"><i class="fa fa-arrow-left"></i></a>
				</div>
				<h1>
					<?php echo $project['PROJECTTITLE']; ?> - Project Summary
					<small>What can I improve on the next project?</small>
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
									<?php $date = date_create($project['PROJECTACTUALENDDATE']); ?>
									<p>Completed on: <b><?php echo date_format($date, "M d, Y"); ?></b></p>
									<p>Total number of main activities: <b><?php echo count($mainActivity); ?></b></p>
									<p>Total number of sub activities: <b><?php echo count($subActivity); ?></b></p>
									<p>Total number of tasks: <b><?php echo count($tasks); ?></b></p>
								</div>
								<div style="display:inline-block; margin-left: 50px;">
									<p>Total number of delayed tasks:
										<b>
											<!-- TODO: Fix this -->
											<?php

												$delayCounter = 0;

												foreach ($groupedTasks as $row)
												{
													if ($row['TASKACTUALENDDATE'] < $row['TASKENDDATE'])
													{
														$delayCounter++;
													}
												}

												echo $delayCounter;
											?>
										</b>
									</p>
									<p>Total number of requests: <b>75</b></p>
									<p>Total number of approved requests: <b>175</b></p>
									<p>Total number of denied requests: <b>200</b></p>
								</div>
								<div style="display:inline-block; margin-left: 30px;">
									<p>Total number of documents: <b>10</b></p>
									<p>WHAT ELSE</p>
									<p>WHAT ELSE</p>
									<p>WHAT ELSE</p>
								</div>
							</div>
						</div>
					</div>
	        <!-- /.col -->
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Overall Performance</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div style="text-align:center;">
									<div class="circlechart" id="completeness" data-percentage="29.76"> Timeliness</div>
							 </div>
							</div>
						</div>
					</div>
	        <!-- /.col -->
				</div>

				<!-- ALL PROJECTS INVOLVED IN THE PROJECT -->
				<div class="row">
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">HR Performance</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div style="text-align:center;">
									<div class="circlechart" id="completeness" data-percentage="29.76"> Timeliness</div>
							 </div>
							</div>
						</div>
					</div>
	        <!-- /.col -->
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Marketing Department</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div style="text-align:center;">
									<div class="circlechart" id="completeness" data-percentage=""> Timeliness</div>
		 					 </div>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">MIS Department</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div style="text-align:center;">
									<div class="circlechart" id="completeness" data-percentage=""> Timeliness</div>
		 					 </div>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
					<div class="col-md-3 col-sm-3 col-xs-12">
						<div class="box box-danger">
							<div class="box-header with-border">
								<h3 class="box-title">Finance Department</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div style="text-align:center;">
									<div class="circlechart" id="completeness" data-percentage=""> Timeliness</div>
		 					 </div>
							</div>
						</div>
	        </div>
	        <!-- /.col -->
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
											<th width="0%">Approver</th>
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
