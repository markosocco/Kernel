<html>
	<head>
		<title>Kernel - Monitor Department Details</title>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorMembersStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">

					<button id="backBtn" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Departments"><i class="fa fa-arrow-left"></i></button>
					<form id="backForm" action = 'monitorDepartment' method="POST" data-id="<?php echo $projectProfile['PROJECTID']; ?>">
					</form>
					<h1>
						<?php echo $projectProfile['PROJECTTITLE'];?>
						<?php
						$projectStart = date_create($projectProfile['PROJECTSTARTDATE']);
						$projectEnd = date_create($projectProfile['PROJECTENDDATE']);
						?>
						<small>(<?php echo date_format($projectStart, "F d, Y");?> to <?php echo date_format($projectEnd, "F d, Y");?>)</small>
					</h1>

					<ol class="breadcrumb">
	          <?php $dateToday = date('F d, Y | l');?>
	          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
	        </ol>
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->

					<div class = 'row'>
						<?php
						$completed = 0;
						$planned = 0;
						$ongoing = 0;
						$delayed = 0;
						?>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="total"> Total <br><br><b><?php echo count ($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<?php foreach($tasks as $task)
							if($task['TASKSTATUS'] == 'Complete')
								$completed++;
							elseif($task['TASKSTATUS'] == 'Planning')
								$planned++;
							elseif($task['TASKSTATUS'] == 'Ongoing')
							{
								$ongoing++;

								if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
									$endDate = $task['TASKENDDATE'];
								else
									$endDate = $task['TASKADJUSTEDENDDATE'];

								if($endDate < $task['currDate'])
									$delayed++;
							}
						?>


						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed <br><br><b><span style='color:red'><?php echo $delayed ;?></span></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing <br><br><b><?php echo $ongoing ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Completed <br><br><b><?php echo $completed ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Planned <br><br><b><?php echo $planned ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

					</div>

          <div class="box box-danger">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered responsive">
                <thead>
                  <tr>
										<th width='.5%'></th>
                    <th width="23.5%">Task</th>
										<th width="8%" class='text-center'>Start Date</th>
										<th width="8%" class='text-center'>Target<br>End Date</th>
                    <th width="15%" class='text-center'>R</th>
										<th width="15%" class='text-center'>A</th>
										<th width="15%" class='text-center'>C</th>
										<th width="15%" class='text-center'>I</th>
                  </tr>
                </thead>
                <tbody>
									<?php foreach($tasks as $task):?>
										<?php
										if($task['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
											$startDate = date_create($task['TASKSTARTDATE']);
										else
											$startDate = date_create($task['TASKADJUSTEDSTARTDATE']);

										if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
											$endDate = $task['TASKENDDATE'];
										else
											$endDate = $task['TASKADJUSTEDENDDATE'];
										?>
                  <tr class="clickable task" data-id="<?php echo $task['TASKID'];?>" data-toggle='modal' data-target='#taskDetails'>
										<?php if($endDate < $task['currDate'] && $task['TASKSTATUS'] == 'Ongoing'):?>
											<td class='bg-red'></td>
										<?php elseif($endDate >= $task['currDate'] && $task['TASKSTATUS'] == 'Ongoing'):?>
											<td class='bg-green'></td>
										<?php elseif($task['TASKSTATUS'] == 'Complete'):?>
											<td class='bg-teal'></td>
										<?php elseif($task['TASKSTATUS'] == 'Planning'):?>
											<td class='bg-yellow'></td>
										<?php endif;?>
										<td><?php echo $task['TASKTITLE'];?></td>
										<td align='center'><?php echo date_format($startDate, "M d, Y");?></td>
										<td align='center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
                    <td><?php echo $task['FIRSTNAME'];?> <?php echo $task['LASTNAME'];?></td>
										<td>
											<?php foreach ($raci as $raciRow): ?>
												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
													<?php if ($raciRow['ROLE'] == '2'): ?>
														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
													<?php endif; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td>
											<?php foreach ($raci as $raciRow): ?>
												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
													<?php if ($raciRow['ROLE'] == '3'): ?>
														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
													<?php endif; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
										<td>
											<?php foreach ($raci as $raciRow): ?>
												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
													<?php if ($raciRow['ROLE'] == '4'): ?>
														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
													<?php endif; ?>
												<?php endif; ?>
											<?php endforeach; ?>
										</td>
                  </tr>
								<?php endforeach;?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>

          <!-- Task Details Modal -->
          <div class="modal fade" id="taskDetails" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h2 class="modal-title" id='taskName'>Task Name here</h2>
									<h4 id="taskDates">Start Date - End Date (Days)</h4>
                </div>
                <div class="modal-body">
                  <!-- <h4>Task Delegation</h4>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="20%">Delagated By</th>
												<th width="20%">Responsible</th>
                        <th width="20%">Accountable</th>
                        <th width="20%">Consulted</th>
                        <th width="20%">Informed</th>
                      </tr>
                    </thead>
                    <tbody id="delegationHistory">
                    </tbody>
                  </table> -->
                  <h4>Change Requests</h4>
                  <table class="table table-bordered">
                    <thead id="rfcHeader">
                      <tr>
                        <th width="20%" class='text-center'>Type</th>
                        <th width="20%">Requested By</th>
												<th width="20%">Date Requested</th>
												<th width="20%" class='text-center'>Status</th>
                        <th width="20%">Reviewed By</th>
                        <th width="20%">Date Reviewed</th>
                      </tr>
                    </thead>
                    <tbody id="rfcHistory">
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorProject").addClass("active");
      $('.circlechart').circlechart(); // Initialization

			$(document).on("click", "#backBtn", function() {
				var $project = $("#backForm").attr('data-id');
				$("#backForm").attr("name", "formSubmit");
				$("#backForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
				$("#backForm").submit();
				});

			$(document).on("click", ".task", function(){
				var $taskID = $(this).attr('data-id');

				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/loadTaskHistory"); ?>",
					data: {task_ID: $taskID},
					dataType: 'json',
					success:function(data)
					{
						$("#taskName").html(data['task'].TASKTITLE);

						if(data['task'].TASKADJUSTEDSTARTDATE == null)
							var startDate = data['task'].TASKSTARTDATE;
						else
							var startDate = data['task'].TASKADJUSTEDSTARTDATE;

						if(data['task'].TASKADJUSTEDENDDATE == null)
							var endDate = data['task'].TASKENDDATE;
						else
							var endDate = data['task'].TASKADJUSTEDENDDATE;

						var diff = ((new Date(endDate) - new Date(startDate))/ 1000 / 60 / 60 / 24)+1;

						$("#taskDates").html(moment(startDate).format('MMMM DD, YYYY') + " - " + moment(endDate).format('MMMM DD, YYYY') + " (" + diff);
	 				 	if(diff > 1)
	 						$("#taskDates").append(" days)");
	 				 	else
	 						$("#taskDates").append(" day)");

						// TASK DELEGATION
						// $("#delegationHistory").html("");
						// var isDelegated = 'false';
						// for(rh=0; rh < data['raciHistory'].length; rh++)
						// {
						// 	if(isDelegated == 'false' && data['raciHistory'][rh].ROLE == '0')
						// 	{
						// 		isDelegated = 'true';
						// 		$("#delegationHistory").append(
						// 			"<tr>" + "<td>" + data['raciHistory'][rh].FIRSTNAME + "" + data['raciHistory'][rh].LASTNAME + "</td>");
						// 	}
						//
						// 	if(isDelegated == 'true' && data['raciHistory'][rh].ROLE == '1')
						// 	{
						// 		$("#delegationHistory").append(
						// 			"<td>" + data['raciHistory'][rh].FIRSTNAME + "" + data['raciHistory'][rh].LASTNAME + "</td>");
						// 	}
						//
						// 	if(isDelegated == 'true' && data['raciHistory'][rh].ROLE == '2')
						// 	{
						// 		$("#delegationHistory").append(
						// 			"<td>" + data['raciHistory'][rh].FIRSTNAME + "" + data['raciHistory'][rh].LASTNAME + "</td>");
						// 	}
						//
						// 	if(isDelegated == 'true' && data['raciHistory'][rh].ROLE == '3')
						// 	{
						// 		$("#delegationHistory").append(
						// 			"<td>" + data['raciHistory'][rh].FIRSTNAME + "" + data['raciHistory'][rh].LASTNAME + "</td>");
						// 	}
						//
						// 	if(isDelegated == 'true' && data['raciHistory'][rh].ROLE == '4')
						// 	{
						// 		$("#delegationHistory").append(
						// 			"<td>" + data['raciHistory'][rh].FIRSTNAME + "" + data['raciHistory'][rh].LASTNAME + "</td>");
						// 	}
						//
						// 	if(isDelegated == 'true' && data['raciHistory'][rh].ROLE == '4' && data['raciHistory'][rh+1].ROLE == '5')
						// 	{
						// 		isDelegated = 'false';
						// 		$("#delegationHistory").append("<tr>");
						// 	}
						// }

						// RFC HISTORY
						if(data['changeRequests'].length <= 0)
						{
							$("#rfcHistory").html("<h4 colspan='5' align='center'>No history</h4>")
							$("#rfcHeader").hide();
						}
						else
						{
							$("#rfcHistory").html("");
							$("#rfcHeader").show();

							for(r=0; r < data['changeRequests'].length; r++)
							{
								if(data['changeRequests'][r].REQUESTTYPE == '1')
									var type = "<i class='fa fa-user-times'></i>";
								else
									var type = "<i class='fa fa-calendar'></i>";

								var approver="-";
								for(u=0; u < data['users'].length; u++)
								{
									if(data['changeRequests'][r].users_REQUESTEDBY == data['users'][u].USERID)
										var requester = data['users'][u].FIRSTNAME + " " + data['users'][u].LASTNAME;

									if(data['changeRequests'][r].users_APPROVEDBY == data['users'][u].USERID)
										var approver = data['users'][u].FIRSTNAME + " " + data['users'][u].LASTNAME;
								}

								$("#rfcHistory").append(
									"<tr>" +
									"<td align='center'>" + type + "</td>" +
									"<td>" + requester + "</td>" +
									"<td>" + moment(data['changeRequests'][r].REQUESTEDDATE).format('MMM DD, YYYY') + "</td>" +
									"<td align='center'>" + data['changeRequests'][r].REQUESTSTATUS + "</td>" +
									"<td>" + approver + "</td>" +
									"<td>" + moment(data['changeRequests'][r].APPROVEDDATE).format('MMM DD, YYYY') + "</td>" +
									"</tr>");
							}
						}
					},
					error:function(data)
					{
						alert("There was a problem with loading the change requests");
					}
				});
			});
		</script>
	</body>
</html>
