<html>
	<head>
		<title>Kernel - Tasks To Do</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/notificationsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Tasks To Do
					<small>What do I need to get done?</small>
				</h1>
			</section>

      <!-- Main content -->
			<section class="content container-fluid">
        <!-- START HERE -->
				<a id = "viewAll" class = "pull-right">View All Tasks >></a> <br><br>

				<div id = "filteredTasks">

					<div class="row">
						<!-- TO DO -->

						<?php if ($tasks != NULL): ?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">To Do</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin">
											<thead>
											<tr>
												<th width="1%"></th>
												<th>Task</th>
												<th>Project</th>
												<th class="text-center">End Date</th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody>

												<?php
													$totalToDo=0;
													$delayedToDo=0;
												?>
												<?php foreach($tasks as $task):?>
												<?php
												if($task['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
													$startDate = $task['TASKSTARTDATE'];
												else
													$startDate = $task['TASKADJUSTEDSTARTDATE'];

												if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
													$endDate = $task['TASKENDDATE'];
												else
													$endDate = $task['TASKADJUSTEDENDDATE'];

												$enddate = date_create($endDate);
												?>

													<?php if($task['threshold'] <= $endDate):?>
														<?php $totalToDo++;?>
														<tr class="viewProject" data-id="<?php echo $task['PROJECTID'] ;?>">
															<?php if($task['currentDate'] < $endDate):?>
																<td class="bg-green"></td>
															<?php else:?>
																<td class="bg-red"></td>
																<?php $delayedToDo++;?>
															<?php endif;?>
															<td><?php echo $task['TASKTITLE'];?></td>
															<td><?php echo $task['PROJECTTITLE'];?></td>
															<td align="center"><?php echo date_format($enddate, 'M d, Y');?></td>
															<td align="center">
																<button type="button" class="btn btn-warning btn-sm editBtn"
																data-id="<?php echo $task['TASKID'];?>">
																	<i class="fa fa-fire"></i>
																</button>
																<button type="button" class="btn btn-success btn-sm delegateBtn"
																data-toggle="modal" data-target="#modal-delegate"
																data-id="<?php echo $task['TASKID'];?>"
																data-title="<?php echo $task['TASKTITLE'];?>"
																data-start="<?php echo $startDate;?>"
																data-end="<?php echo $endDate;?>">
																	<i class="fa fa-check"></i>
																</button>
															</td>
														</tr>
													<?php endif;?>
												<?php endforeach;?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					<?php else:?>
						<div class="col-md-10">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">To Do</h3>
								</div>
								<div class="box-body">
									<h4 align="center">You have no tasks due in 3 days</h4>
								</div>
							</div>
						</div>
					<?php endif;?>
					<div class="col-md-2">
						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Total: <br><br><b><?php echo $totalToDo;?></b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed: <br><br><span style='color:red'><b><?php echo $delayedToDo;?></b></span></h4>
								</div>
							</div>
						</div>
					</div>
					</div> <!-- CLOSING ROW -->

				</div>

				<div id = "allTasks">
					<!-- ALL TASKS -->
					<div class = 'row'>
						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Total: <br><br><b><?php echo count($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed: <br><br><b><?php echo count($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>
					</div>


					<?php if ($tasks != NULL): ?>
					<div class = "row">
						<div class="col-md-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">All Tasks</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<table class="table table-hover no-margin">
											<thead>
											<tr>
												<th>Task</th>
												<th>Project</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">Target End Date</th>
												<th class="text-center">Period <small>(day/s)</small></th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody id="taskTable">

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endif;?>
				</div>

				<form id='viewProject' action = 'projectGantt' method="POST">
					<input type ='hidden' name='delegateTask' value='0'>
				</form>

			</section>
		</div>
		  <?php require("footer.php"); ?>
		</div> <!--.wrapper closing div-->
		<script>
      $("#tasks").addClass("active");
      $("#taskTodo").addClass("active");
			$("#allTasks").hide();

			$.ajax({
				type:"POST",
				url: "<?php echo base_url("index.php/controller/loadTasks"); ?>",
				dataType: 'json',
				success:function(data)
				{
					if(data['tasks'].length > 0)
					{
						$('#taskTable').html("");
						for(i=0; i<data['tasks'].length; i++)
						{
							if(data['tasks'][i].TASKADJUSTEDSTARTDATE == null) // check if start date has been previously adjusted
							{
								var taskStart = moment(data['tasks'][i].TASKSTARTDATE).format('MMM DD, YYYY');
								var startDate = data['tasks'][i].TASKSTARTDATE;
							}
							else
							{
								var taskStart = moment(data['tasks'][i].TASKADJUSTEDSTARTDATE).format('MMM DD, YYYY');
								var startDate = data['tasks'][i].TASKADJUSTEDSTARTDATE;
							}

							if(data['tasks'][i].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
							{
								var taskEnd = moment(data['tasks'][i].TASKENDDATE).format('MMM DD, YYYY');
								var endDate = data['tasks'][i].TASKENDDATE;
							}
							else
							{
								var taskEnd = moment(data['tasks'][i].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');
								var endDate = data['tasks'][i].TASKADJUSTEDENDDATE;
							}

							if(data['tasks'][i].TASKADJUSTEDSTARTDATE != null && data['tasks'][i].TASKADJUSTEDENDDATE != null)
								var taskDuration = parseInt(data['tasks'][i].adjustedTaskDuration2);
							if(data['tasks'][i].TASKSTARTDATE != null && data['tasks'][i].TASKADJUSTEDENDDATE != null)
								var taskDuration = parseInt(data['tasks'][i].adjustedTaskDuration1);
							else
								var taskDuration = parseInt(data['tasks'][i].initialTaskDuration);

							$('#taskTable').append(
													 "<tr id='" + data['tasks'][i].TASKID + "'>" +
													 "<td>" + data['tasks'][i].TASKTITLE+"</td>"+
													 "<td>" + data['tasks'][i].PROJECTTITLE+"</td>"+
													 "<td align='center'>" + taskStart +"</td>"+
													 "<td align='center'>" + taskEnd +"</td>"+
													 "<td align='center'>" + taskDuration +"</td>");
						}
					}

				},
				error:function()
				{
					alert("There was a problem in retrieving the tasks");
				}
			});

			$(document).on("click", "#viewAll", function()
			{
				$("#allTasks").toggle();
				$("#filteredTasks").toggle();

				if($("#allTasks").css("display") == "none")
					$("#viewAll").html("View All Tasks >>");
				else
					$("#viewAll").html("Hide All Tasks >>");
			});

			$(document).on("click", ".viewProject", function() {
				var $projectID = $(this).attr('data-id');
				$("#viewProject").attr("name", "formSubmit");
				$("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				$("#viewProject").submit();
			});

			$("body").on("click", ".editBtn", function(e) {
				alert("Forward to Edit Project");
				// var $projectID = $(this).attr('data-id');
				// $("#viewProject").attr("name", "formSubmit");
				// $("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				// $("#viewProject").submit();
			});
		</script>
	</body>
</html>
