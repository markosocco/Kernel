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
											<th>Project</th>
											<th class="text-center">Target End Date</th>
											<th class="text-center">Progress</th>
											<th class="text-center">Until End</th>
										</tr>
										</thead>
										<tbody>
										<tr data-id="" data-toggle="modal" data-target="projectGantt of this project">
											<td>Store Opening - SM Southmall</td>
											<td align="center">Dec 73, 2080</td>
											<td align="center">80.79%</td>
											<td align="center">45 days</td>
										</tr>
										<tr data-id="" data-toggle="modal" data-target="projectGantt of this project">
											<td>Store Opening - SM Southmall</td>
											<td align="center">Dec 73, 2080</td>
											<td align="center">80.79%</td>
											<td align="center">69 days</td>
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
												echo "<tr style='color:red'>";
													echo "<td class='projectLink'>" . $row['PROJECTTITLE'] . "</td>";
													echo "<td>" . $row['TASKTITLE'] . "</td>";
													echo "<td>" . $row['TASKENDDATE'] . "</td>";
													echo "<td> DELAYED </td>";
												echo "</tr>";
											}
											foreach ($tasks3DaysBeforeDeadline as $data)
											{
												echo "<tr>";
													echo "<td class='projectLink'>" . $data['PROJECTTITLE'] . "</td>";
													echo "<td>" . $data['TASKTITLE'] . "</td>";
													echo "<td>" . $data['TASKENDDATE'] . "</td>";
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

			<!-- APPROVAL TABLE -->
			<!-- Main row -->
			<div class="row">
				<!-- Left col -->
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Request Approval</h3>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table table-hover no-margin" id="requestApproval">
									<thead>
									<tr>
										<th>User</th>
										<th>Request Type</th>
										<th>Project</th>
										<th>Request Date</th>
									</tr>
									</thead>
									<tbody>
									<tr data-id="" data-toggle="modal" data-target="#modal-requestDetails">
										<td>firstName lastName</td>
										<td>Change Performer</td>
										<td>SM Southmall - Store Opening
										<td>June 45, 2018</td>
									</tr>
									<tr data-id="" data-toggle="modal" data-target="#modal-requestDetails">
										<td>firstName lastName</td>
										<td>Change Date</td>
										<td>SM Southmall - Store Opening
										<td>June 45, 2018</td>
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

			<div class="row">
			</div>

			<!-- END APPROVAL TABLE -->

			<!-- MODALS -->
			<!-- REQUEST APPROVAL MODAL -->
			<div class="modal fade" id="modal-requestDetails" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span></button>
							<h2 class="modal-title" id = "doneTitle">Task Finished</h2>
							<h4>Start Date - End Date (Days)</h4>
						</div>
						<div class="modal-body">
							<form id = "approvalForm" action="" method="POST" style="margin-bottom:0;">
								<!-- IF TYPE = PERFORMER -->
								<label>Reason</label>
								<p id="performerReason">Wrong tagged employee. Get it right bitch</p>
								<div class="form-group">
									<textarea id = "remarks" name = "remarks" class="form-control" placeholder="Enter remarks (Optional)"></textarea>
								</div>
								<!-- IF TYPE = DATE -->
								<label>Reason</label>
								<p id="dateReason"> Need more time. Get it right bitch</p>
								<label>Dates</label>
								<p>Original Start Date to Requested Start Date</p>
								<p>Original End Date to Requested End Date</p>
								<div class="form-group">
									<textarea id = "remarks" name = "remarks" class="form-control" placeholder="Enter remarks (Optional)"></textarea>
								</div>
								<div class="modal-footer">
									<button id = "denyRequest" type="submit" class="btn btn-danger pull-left" data-id=""><i class="fa fa-thumbs-down"></i> Deny Request</button>
									<button id = "approveRequest" type="submit" class="btn btn-success" data-id=""><i class="fa fa-thumbs-up"></i> Approve Request</button>
								</div>
							</form>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->

			<!-- MODALS END -->


		</section>
			</div>

		<?php require("footer.php"); ?>

	</div> <!--.wrapper closing div-->

	<script>
		$("#dashboard").addClass("active");

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
