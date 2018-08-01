<html>
	<head>
		<title>Kernel - Monitor Team</title>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTeamStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Monitor Team
						<small>What's happening to my team?</small>
					</h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/monitorTeam"); ?>"><i class="fa fa-dashboard"></i> My Team</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>

				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
						<div class="row">
							<?php foreach ($staff as $row): ?>
								<?php if ($row['USERID'] != $_SESSION['USERID']): ?>
								<div class="col-md-4">
									<!-- Widget: user widget style 1 -->
									<div class="box box-widget widget-user">
										<!-- Add the bg color to the header using any of the bg-* classes -->
										<div class="widget-user-header bg-aqua-active">
											<h3 class="widget-user-username"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></h3>
											<h5 class="widget-user-desc"><?php echo $row['POSITION']; ?></h5>
										</div>
										<div class="widget-user-image">
											<img src="<?php echo base_url()."assets/"; ?>media/idpic.png" class="img-circle" alt="User Image">
										</div>
										<div class="box-footer">
											<div class="row">
												<div class="col-sm-4 border-right">
													<div class="description-block">
														<h5 class="description-header">
															<?php foreach ($projectCount as $pCount): ?>
																<?php if ($row['USERID'] == $pCount['USERID']): ?>
																	<?php if ($pCount != 0): ?>
																		<?php echo $pCount['projectCount']; ?>
																	<?php else: ?>
																		0
																	<?php endif; ?>
																<?php endif; ?>
															<?php endforeach; ?>
														</h5>
														<span class="description-text">PROJECTS</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
												<div class="col-sm-4 border-right">
													<div class="description-block">
														<h5 class="description-header">
															<?php foreach ($taskCount as $tCount): ?>
																<?php if ($row['USERID'] == $tCount['USERID']): ?>
																	<?php if ($tCount != 0): ?>
																		<?php echo $tCount['taskCount']; ?>
																		0
																	<?php endif; ?>
																<?php endif; ?>
															<?php endforeach; ?>
														</h5>
														<span class="description-text">TASKS</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
												<div class="col-sm-4">
													<div class="description-block">
														<h5 class="description-header">35</h5>
														<span class="description-text">PERFORMANCE</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
											</div>
											<!-- /.row -->
										</div>
									</div>
									<!-- /.widget-user -->
								</div>
								<!-- /.col -->
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorTeam").addClass("active");

			$("body").on("click", ".moreInfo", function(){

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

				$("#raciDelegate").hide();
				var $id = $(this).attr('data-id');
				var $projectCount = $(this).attr('data-projectCount');
				var $taskCount = $(this).attr('data-taskCount');
				$("#workloadEmployee").html($(this).attr('data-name'));
				$("#workloadProjects").html("Total Number of Projects: " + $projectCount);
				$("#workloadTasks").html("Total Number of Tasks: " + $taskCount);
				$('#workloadDiv').html("");
				$("#workloadAssessment").show();

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

			});
		</script>
	</body>
</html>
