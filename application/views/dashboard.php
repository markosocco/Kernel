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

				<?php if($delayedTaskPerUser != NULL || $tasks3DaysBeforeDeadline != NULL): ?>
				<!-- TASK TABLE -->
				<!-- Main row -->
				<div class="row">
					<!-- Left col -->
					<div class="col-md-12">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">WHAT TABLE IS THIS</h3>
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
				</div>
			<?php endif;?>

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
				$.notify({
		      // options
		      icon: 'fa fa-check',
		      message: ' Hello Success World'
		      },{
		      // settings
		      type: 'success',
		      offset: 60,
		      delay: 5000,
		      placement: {
		        from: "top",
		        align: "center"
		      },
		      animate: {
		        enter: 'animated fadeInDownBig',
		        exit: 'animated fadeOutUpBig'
		      },
		      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
		        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
		        '<span data-notify="icon"></span>' +
		        '<span data-notify="title">{1}</span>' +
		        '<span data-notify="message">{2}</span>' +
		      '</div>'
		      });
				});

				$("#danger").click(function(){
					$.notify({
			      // options
			      icon: 'fa fa-ban',
			      message: ' Hello Danger World'
			      },{
			      // settings
			      type: 'danger',
			      offset: 60,
			      delay: 5000,
			      placement: {
			        from: "top",
			        align: "center"
			      },
			      animate: {
			        enter: 'animated fadeInDownBig',
			        exit: 'animated fadeOutUpBig'
			      },
			      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
			        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
			        '<span data-notify="icon"></span>' +
			        '<span data-notify="title">{1}</span>' +
			        '<span data-notify="message">{2}</span>' +
			      '</div>'
			      });
					});

					$("#warning").click(function(){
						$.notify({
				      // options
				      icon: 'fa fa-warning',
				      message: ' Hello Warning World'
				      },{
				      // settings
				      type: 'warning',
				      offset: 60,
				      delay: 5000,
				      placement: {
				        from: "top",
				        align: "center"
				      },
				      animate: {
				        enter: 'animated fadeInDownBig',
				        exit: 'animated fadeOutUpBig'
				      },
				      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
				        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
				        '<span data-notify="icon"></span>' +
				        '<span data-notify="title">{1}</span>' +
				        '<span data-notify="message">{2}</span>' +
				      '</div>'
				      });
						});

						$("#info").click(function(){
							$.notify({
					      // options
					      icon: 'fa fa-info',
					      message: ' Hello Info World'
					      },{
					      // settings
					      type: 'info',
					      offset: 60,
					      delay: 5000,
					      placement: {
					        from: "top",
					        align: "center"
					      },
					      animate: {
					        enter: 'animated fadeInDownBig',
					        exit: 'animated fadeOutUpBig'
					      },
					      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
					        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
					        '<span data-notify="icon"></span>' +
					        '<span data-notify="title">{1}</span>' +
					        '<span data-notify="message">{2}</span>' +
					      '</div>'
					      });
							});
		});
	</script>

	</body>
</html>
