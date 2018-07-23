<html>
	<head>
		<title>Kernel - Dashboard</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/dashboardStyle.css")?>">
	</head>
	<body>
		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Welcome, <b><?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?>!</b>
				</h1>
			</section>

			<section class="content container-fluid">
				<div>
					<button id="success" type="button" class="btn btn-success">Test Success</button>
					<button id="warning" type="button" class="btn btn-warning">Test Warning</button>
					<button id="danger" type="button" class="btn btn-danger">Test Danger</button>
					<button id="info" type="button" class="btn btn-info">Test Info</button>
				</div>
				<br>

				<div class="row">
	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-blue" style="padding-top:20px;"><i class="fa fa-check"></i></span>

	            <div class="info-box-content">
	              <span class="info-box-text">My Completeness</span>
	              <span class="info-box-number">10.99%</span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	        <!-- /.col -->
	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-blue" style="padding-top:20px;"><i class="fa fa-clock-o"></i></span>

	            <div class="info-box-content">
	              <span class="info-box-text">My Timeliness</span>
	              <span class="info-box-number">99%</span>
	            </div>

	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	        <!-- /.col -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box">
							<span class="info-box-icon bg-light-blue" style="padding-top:20px;"><i class="fa fa-check"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">DeptName<br>Completeness</span>
								<span class="info-box-number">10.99%</span>
							</div>
							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
					<div class="col-md-3 col-sm-6 col-xs-12">
						<div class="info-box">
							<span class="info-box-icon bg-light-blue" style="padding-top:20px;"><i class="fa fa-clock-o"></i></span>

							<div class="info-box-content">
								<span class="info-box-text">DeptName<br>Timeliness</span>
								<span class="info-box-number">99%</span>
							</div>

							<!-- /.info-box-content -->
						</div>
						<!-- /.info-box -->
					</div>
					<!-- /.col -->
				</div>

				<!-- MANAGE TABLE -->
				<!-- Main row -->
				<div class="row">
					<!-- Left col -->
					<div class="col-md-6">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Projects I'm Working On</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<table class="table table-hover no-margin" id="projWeeklyProgress">
										<thead>
										<tr>
											<th>Name</th>
											<th class="text-center">Target End Date</th>
											<th class="text-center">Progress</th>
											<th class="text-center">Days Left</th>
										</tr>
										</thead>
										<tbody>
											<?php foreach($ongoingProjects as $ongoingProject): ?>
												<tr class = "projects" data-id="<?php echo $ongoingProject['PROJECTID'];?>">

													<form class='projectForm' action = 'projectGantt' method="POST">
														<input type ='hidden' name='dashboard' value='0'>
													</form>

													<td><?php echo $ongoingProject['PROJECTTITLE'];?></td>
													<?php
													if($ongoingProject['PROJECTADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
														$endDate = date_create($ongoingProject['PROJECTENDDATE']);
													else
														$endDate = date_create($ongoingProject['PROJECTADJUSTEDENDDATE']);
													?>
													<td align="center"><?php echo date_format($endDate, "M d, Y");?></td>
													<td align="center">80.79%</td>  <!-- PUT PROGRESS PLS, @NAMI -->
													<td align="center"><?php echo $ongoingProject['datediff'];?></td>
												</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
							</div>
							<!-- /.box-body -->
							<!-- /.box-footer -->
						</div>
						<!-- /.box -->
					</div>

					<!-- Right col -->
					<div class="col-md-6">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Projects I Need To Edit</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<table class="table table-hover no-margin" id="projWeeklyProgress">
										<thead>
										<tr>
											<th>Project</th>
											<th class="text-center">Start Date</th>
											<th class="text-center">Until Launch</th>
										</tr>
										</thead>
										<tbody>
										<tr data-id="" data-toggle="modal" data-target="projectGantt of this project">
											<td>Store Opening - SM Southmall</td>
											<td align="center">Dec 73, 2080</td>
											<td align="center">3 days</td>
										</tr>
										<tr data-id="" data-toggle="modal" data-target="projectGantt of this project">
											<td>Store Opening - SM Southmall</td>
											<td align="center">Dec 73, 2080</td>
											<td align="center">75 days</td>
										</tr>
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
							</div>
							<!-- /.box-body -->
							<!-- /.box-footer -->
						</div>
						<!-- /.box -->
					</div>

				</div>

				<!-- END MANAGE TABLE -->

				<!-- TASK TABLE -->
				<!-- Main row -->
				<div class="row">
					<!-- Left col -->
					<?php if($delayedTaskPerUser != NULL || $tasks3DaysBeforeDeadline != NULL): ?>
					<div class="col-md-6">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Deadlines</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<table class="table table-hover no-margin" id="logsList">
										<thead>
											<th>Project</th>
											<th>Task</th>
											<th>Task End Date</th>
											<th>Status</th>
										</thead>
										<tbody>
										<?php
											foreach ($delayedTaskPerUser as $row)
											{
												if($row['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
													$endDate = date_create($row['TASKENDDATE']);
												else
													$endDate = date_create($row['TASKADJUSTEDENDDATE']);

												echo "<tr style='color:red'>";
													echo "<td class='projectLink'>" . $row['PROJECTTITLE'] . "</td>";
													echo "<td>" . $row['TASKTITLE'] . "</td>";
													echo "<td>" . date_format($endDate, "M d, Y") . "</td>";
													echo "<td> DELAYED </td>";
												echo "</tr>";
											}
											foreach ($tasks3DaysBeforeDeadline as $data)
											{
												if($data['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
													$endDate = date_create($data['TASKENDDATE']);
												else
													$endDate = date_create($data['TASKADJUSTEDENDDATE']);

												echo "<tr>";
													echo "<td class='projectLink'>" . $data['PROJECTTITLE'] . "</td>";
													echo "<td>" . $data['TASKTITLE'] . "</td>";
													echo "<td>" . date_format($endDate, "M d, Y") . "</td>";
													echo "<td>" . $data['TASKDATEDIFF'] . " day/s before deadline</td>";
												echo "</tr>";
											}
										?>
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
							</div>
							<!-- /.box-body -->
							<!-- /.box-footer -->
						</div>
						<!-- /.box -->
					</div>
					<?php endif;?>

					<div class="col-md-6">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Project Weekly Progress</h3>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<table class="table table-hover no-margin" id="projWeeklyProgress">
										<thead>
										<tr>
											<th>Project</th>
											<th class="text-center">Launch Date</th>
											<th class="text-center">Last Week's Progress</th>
											<th class="text-center">This Week's Progress</th>
										</tr>
										</thead>
										<tbody>
										<tr data-id="" data-toggle="modal" data-target="projectGantt of this project">
											<td>Store Opening - SM Southmall</td>
											<td align="center">Dec 73, 2080</td>
											<td align="center">80.79%</td>
											<td align="center">90.80%</td>
										</tr>
										</tbody>
									</table>
								</div>
								<!-- /.table-responsive -->
							</div>
							<!-- /.box-body -->
							<!-- /.box-footer -->
						</div>
						<!-- /.box -->
					</div>

				</div>

				<?php if($changeRequests != null):?>

					<!-- APPROVAL TABLE -->
					<!-- Main row -->
					<div class="row">
						<!-- Left col -->
						<div class="col-md-12">
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Change Request Approval</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin" id="requestApproval">
											<thead>
											<tr>
												<th>Date Requested</th>
												<th>Request Type</th>
												<th>Requester</th>
												<th>Project</th>
												<th>Task</th>
											</tr>
											</thead>
											<tbody>
												<?php foreach($changeRequests as $changeRequest):
													$dateRequested = date_create($changeRequest['REQUESTEDDATE']);
													// if($changeRequest['REQUESTTYPE'] == 1)
													// 	$type = "Change Performer";
													// else
													// 	$type = "Change Date/s";
												?>
													<tr class="request" data-project = "<?php echo $changeRequest['PROJECTID']; ?>" data-request = "<?php echo $changeRequest['REQUESTID']; ?>">

														<form class='changeRequestApproval' action = 'projectGantt' method="POST">
															<input type ='hidden' name='dashboard' value='0'>
															<input type ='hidden' name='rfc' value='0'>
														</form>

														<td><?php echo date_format($dateRequested, "M d, Y"); ?></td>
														<!-- <td><?php echo $type;?></td> -->
														<td>
															<?php if($changeRequest['REQUESTTYPE'] == 1):?>
																<i class="fa fa-user-times"></i>
															<?php else:?>
																<i class="fa fa-calendar"></i>
															<?php endif;?>
														</td>
														<td><?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'] ;?></td>
														<td><?php echo $changeRequest['PROJECTTITLE'];?></td>
														<td><?php echo $changeRequest['TASKTITLE'];?></td>
													</tr>
												<?php endforeach;?>
											</tbody>
										</table>
									</div>
									<!-- /.table-responsive -->
								</div>
								<!-- /.box-body -->
								<!-- /.box-footer -->
							</div>
							<!-- /.box -->
						</div>
					</div>

					<div class="row">
					</div>

					<!-- END APPROVAL TABLE -->
				<?php endif;?>


				<?php if($toAcknowledgeDocuments != NULL):?>
					<!-- MARKO - Docu-->
					<!-- Main row -->
					<div class="row">
						<!-- Left col -->
						<div class="col-md-12">
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Document Acknowledgement</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin" id="requestApproval">
											<thead>
											<tr>
												<th>Document Name</th>
												<th>Uploaded By</th>
												<th>Department</th>
												<th align="center"></th>
											</tr>
											</thead>
											<tbody>
												<?php
													foreach($toAcknowledgeDocuments as $row){
														if($row['users_UPLOADEDBY'] != $_SESSION['USERID']){
															echo "<tr>";
															echo"
															<form action='acknowledgeDocument' method='POST' class ='acknowledgeDocument'>
																<input type='hidden' name='project_ID' value='" . $row['projects_PROJECTID'] . "'>
																<input type='hidden' name='documentID' value='" . $row['DOCUMENTID'] . "'>
															</form>";
																echo "<td>" . $row['DOCUMENTNAME'] . "</td>";
																echo "<td>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</td>";
																echo "<td>" . $row['DEPARTMENTNAME'] . "</td>";


																if($row['ACKNOWLEDGEDDATE'] != ''){
																	echo "<td align='center'>Acknowledged</td>";
																} else {
																	echo "<td align='center'>
																	<button type='button' class='btn btn-success document' name='documentButton' id='acknowledgeButton' data-id ='" . $row['DOCUMENTID'] . "'>
																	<i class='fa fa-eye'></i> Acknowledge</button></td>";
																}

															echo "</tr>";

														}
													}
												?>
											</tbody>
										</table>
									</div>
									<!-- /.table-responsive -->
								</div>
								<!-- /.box-body -->
								<!-- /.box-footer -->
							</div>
							<!-- /.box -->
						</div>
					</div>

					<div class="row">
					</div>

					<!-- END DOCUMENTS TABLE -->
				<?php endif;?>


		</section>
			</div>

		<?php require("footer.php"); ?>

	</div> <!--.wrapper closing div-->

	<script>
		$("#dashboard").addClass("active");

		$(document).on("click", ".request", function() {
			var $project = $(this).attr('data-project');
			var $request = $(this).attr('data-request');
			$(".changeRequestApproval").attr("name", "formSubmit");
			$(".changeRequestApproval").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$(".changeRequestApproval").append("<input type='hidden' name='request_ID' value= " + $request + ">");
			$(".changeRequestApproval").submit();
			});

		$(document).on("click", ".projects", function() {
			var $project = $(this).attr('data-id');
			$(".projectForm").attr("name", "formSubmit");
			$(".projectForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$(".projectForm").submit();
			});

		$(document).on("click", ".document", function() {
			var $id = $(this).attr('data-id');
			$(".acknowledgeDocument").attr("name", "formSubmit");
			$(".acknowledgeDocument").append("<input type='hidden' name='documentID' value= " + $id + ">");
			$(".acknowledgeDocument").submit();
		});

		$(document).ready(function()
		{
			$("#success").click(function(){
				successAlert();
			});

			$("#danger").click(function(){
				dangerAlert();
			});

			$("#warning").click(function(){
				warningAlert();
			});

			$("#info").click(function(){
				infoAlert();
			});
		});
	</script>

	</body>
</html>
