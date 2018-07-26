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

				<button id = "viewAll" class="btn btn-default pull-right" title="View All Tasks"><i class="fa fa-eye"></i></button>
				<br><br>

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
										<table class="table table-hover no-margin" id="toDoTable">
											<thead>
											<tr>
												<th width="1%"></th>
												<th>Task</th>
												<th>Project</th>
												<th class="text-center">End Date</th>
												<th class="text-center">Days Delayed</th>
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
									<h4 align="center" id="totalToDo"> Total <br><br><b>N</b></h4>
								</div>
							</div>
						</div>

						<div class="box box-danger">
							<!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<h4 align="center"> Delayed <br><br><b><span style='color:red' id= "totalDelayedToDo">N</b></span></h4>
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
										<h4 align="center" id="total"> Total <br><br><b>N</b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed <br><br><b><span style='color:red' id= "totalDelayed">N</span></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="totalPlanned"> Planned <br><br><b>N</b></h4>
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
										<table class="table table-hover no-margin" id = "allTaskTable">
											<thead>
											<tr>
												<th width="1%"></th>
												<th>Task</th>
												<th>Project</th>
												<th class="text-center">Start Date</th>
												<th class="text-center">End Date</th>
												<th class="text-center">Days Delayed</th>
												<th class="text-center">Action</th>
											</tr>
											</thead>
											<tbody id="taskAll">



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
					var totalToDo=0;
					var totalDelayedToDo=0;
					var total=0;
					var totalDelayed=0;
					var totalPlanned=0;

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

							var delayDays = data['tasks'][i].delay - taskDuration;

							if(delayDays <= 0)
								delayDays = 0;

							if(data['tasks'][i].currentDate <= endDate)
								var status = "<td class='bg-green'></td>";
							if(data['tasks'][i].TASKSTATUS == 'Planning')
							{
								var status = "<td class='bg-yellow'></td>";
								var totalPlanned = totalPlanned+1;
							}
							if(data['tasks'][i].currentDate > endDate)
							{
								var status = "<td class='bg-red'></td>";
								var totalDelayedToDo = totalDelayedToDo+1;
								var totalDelayed = totalDelayed+1;
							}

							var taskID = data['tasks'][i].TASKID;

							if(data['tasks'][i].threshold >= endDate)
							{
								var totalToDo = totalToDo+1;

								$('#taskTable').append(
														 "<tr id='" + taskID + "'>" +
														 status + "<td>" + data['tasks'][i].TASKTITLE+"</td>"+
														 "<td>" + data['tasks'][i].PROJECTTITLE+"</td>"+
														 "<td align='center'>" + taskEnd +"</td>" +
														 "<td align='center'>" + delayDays + "</td>" +
														 "<td class = 'action-" + taskID +"'></td>");
							}

							var total = total+1;

							$('#taskAll').append(
													 "<tr id='" + taskID + "'>" +
													 status + "<td>" + data['tasks'][i].TASKTITLE+"</td>"+
													 "<td>" + data['tasks'][i].PROJECTTITLE+"</td>"+
													 "<td align='center'>" + taskStart +"</td>" +
													 "<td align='center'>" + taskEnd +"</td>" +
													 "<td align='center'>" + delayDays + "</td>" +
													 "<td class = 'action-" + taskID +"'></td>");

							if(data['tasks'][i].TASKSTATUS != 'Planning')
								$(".action-" + taskID).append("<button type='button' class='btn btn-warning btn-sm rfcBtn'" +
							 	"data-id='" + taskID + "'><i class='fa fa-flag'></i></button>");

								// AJAX TO CHECK IF DEPENDENCIES ARE COMPLETE
								$.ajax({
								 type:"POST",
								 url: "<?php echo base_url("index.php/controller/getDependenciesByTaskID"); ?>",
								 data: {task_ID: taskID},
								 dataType: 'json',
								 success:function(dependencyData)
								 {
									 var taskID = dependencyData['taskID'].TASKID;
									 var taskTitle = dependencyData['taskID'].TASKTITLE;
									 var startDate = moment(dependencyData['taskID'].TASKSTARTDATE).format('MMM DD, YYYY');
									 var endDate = moment(dependencyData['taskID'].TASKENDDATE).format('MMM DD, YYYY');
									 var isDelayed = dependencyData['taskID'].currentDate > dependencyData['taskID'].TASKENDDATE;

									 if(dependencyData['dependencies'].length > 0) //if task has pre-requisite tasks
									 {
										 var isComplete = 'true';
										 for (var d = 0; d < dependencyData['dependencies'].length; d++)
										 {
											 if(dependencyData['dependencies'][d].TASKSTATUS != 'Complete') // if there is a pre-requisite task that is ongoing
											 {
												 isComplete = 'false';
											 }
										 }

										 if(isComplete == 'true' ) // if all pre-requisite tasks are complete, task can be marked done
										 {
											 $(".action-" + data['tasks'][i].TASKID).append(
													'<button type="button"' +
													'class="btn btn-success btn-sm doneBtn" data-toggle="modal"' +
													'data-target="#modal-done" data-id="' + taskID +
													'" data-title="' + taskTitle + '"' +
													'data-delay="' + isDelayed + '" data-start="'+ startDate +
													'" data-end="'+ endDate +'">' +
													'<i class="fa fa-check"></i></button>');
										 }
										 else
										 {
											 $(".action-" + data['tasks'][i].TASKID).append(
												'<button disabled type="button"' +
												'class="btn btn-success btn-sm doneBtn" data-toggle="modal"' +
												'data-target="#modal-done" data-id="' + taskID +
												'" data-title="' + taskTitle + '"' +
												'data-delay="' + isDelayed + '" data-start="'+ startDate +
												'" data-end="'+ endDate +'">' +
												'<i class="fa fa-check"></i></button>');
										 }

									 }
									 else // if task has no prerequisites
									 {
										 $('.action-' + dependencyData['taskID'].TASKID).append(
												'<button type="button"' +
												'class="btn btn-success btn-sm doneBtn" data-toggle="modal"' +
												'data-target="#modal-done" data-id="' + taskID +
												'" data-title="' + taskTitle + '"' +
												'data-delay="' + isDelayed + '" data-start="'+ startDate +
												'" data-end="'+ endDate +'">' +
												'<i class="fa fa-check"></i></button>');
									 }
								 },
								 error:function()
								 {
									 alert("There was a problem in checking the task dependencies");
								 }
							 }); // end of dependencies ajax

						}
						$('#totalToDo').html("Total<br><br><b>" + totalToDo + "</b>");
						$('#totalDelayedToDo').html(totalDelayedToDo);
						$('#total').html("Total<br><br><b>" + total + "</b>");
						$('#totalDelayed').html(totalDelayed);
						$('#totalPlanned').html("Planned<br><br><b>" + totalPlanned + "</b>");
					}

				},
				error:function()
				{
					alert("There was a problem in retrieving the tasks");
				},
				complete:function()
				{
					$('#allTaskTable').DataTable({
						'paging'      : false,
						'lengthChange': false,
						'searching'   : true,
						'ordering'    : true,
						'info'        : false,
						'autoWidth'   : false
					});
					$('#toDoTable').DataTable({
						'paging'      : false,
						'lengthChange': false,
						'searching'   : true,
						'ordering'    : true,
						'info'        : false,
						'autoWidth'   : false
					});
				}
			});

			$(document).on("click", "#viewAll", function()
			{
				$("#allTasks").toggle();
				$("#filteredTasks").toggle();

				if($("#allTasks").css("display") == "none")
				{
					$("#viewAll").html("<i class='fa fa-eye'></i>");
					$("#viewAll").attr("title", "View All Tasks");
				}
				else
				{
					$("#viewAll").html("<i class='fa fa-eye-slash'></i>");
					$("#viewAll").attr("title", "Hide All Tasks");
				}

			});

			$(document).on("click", ".viewProject", function() {
				var $projectID = $(this).attr('data-id');
				$("#viewProject").attr("name", "formSubmit");
				$("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				$("#viewProject").submit();
			});

			$("body").on("click", ".rfcBtn", function(e) {
				alert("Forward to RFC");
				// var $projectID = $(this).attr('data-id');
				// $("#viewProject").attr("name", "formSubmit");
				// $("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				// $("#viewProject").submit();
			});

			$("body").on("click", ".doneBtn", function(e) {
				alert("Forward to Done Task");
				// var $projectID = $(this).attr('data-id');
				// $("#viewProject").attr("name", "formSubmit");
				// $("#viewProject").append("<input type='hidden' name='project_ID' value= " + $projectID + ">");
				// $("#viewProject").submit();
			});
		</script>
	</body>
</html>
