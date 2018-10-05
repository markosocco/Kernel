<html>
	<head>
		<title>Kernel - Monitor Members</title>

		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorMembersStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<a href="<?php echo base_url("index.php/controller/monitorTeam"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to My Team"><i class="fa fa-arrow-left"></i></a>
					<br><br>
					<h1>
						Monitor Members
						<small>What's happening to the members of my team?</small>
					</h1>

					<ol class="breadcrumb">
						<?php $dateToday = date('F d, Y | l');?>
						<p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
					</ol>
					<div class="col-md-4 col-sm-6 col-xs-12 pull-right">
              <div class="box-header with-border" style="text-align:center;">
                <h3 class="box-title">Performance</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div style="display:inline-block; text-align:center; width:49%;">
                  <div class="circlechart"
                    data-percentage="<?php
											if($completeness['completeness'] == NULL){
												echo 0;
											} else {
												if($completeness['completeness'] == 100.00){
													echo 100;
												} elseif ($completeness['completeness'] == 0.00) {
													echo 0;
												} else {
													echo $completeness['completeness'];
												}
											}
											?>"> Completeness
                  </div>
                </div>
                <div style="display:inline-block; text-align:center; width:49%;">
                  <div class="circlechart"
                   data-percentage="<?php
										 if($timeliness['timeliness'] == NULL){
											 echo 0;
										 } else {
											 if($timeliness['timeliness'] == 100.00){
												 echo 100;
											 } elseif ($timeliness['timeliness'] == 0.00) {
												 echo 0;
											 } else {
												 echo $timeliness['timeliness'];
											 }
										 }
										 ?>"> Timeliness
                 </div>
               </div>
              </div>
          </div>
          <!-- /.col -->
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
          <h3><?php echo $user['FIRSTNAME'] . " " . $user['LASTNAME']; ?></h3>
          <h4><?php echo $user['POSITION']; ?></h4>

					<div class = 'row'>

						<?php $projCount = 0;?>
						<?php foreach ($pCount as $p): ?>
						<?php  if ($p['USERID'] == $user['USERID']): ?>
							<?php $projCount = $p['projectCount']; ?>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php $taskCount = 0;?>
					<?php foreach ($tCount as $t): ?>
					<?php  if ($t['USERID'] == $user['USERID']): ?>
						<?php $taskCount =  $t['taskCount']; ?>
					<?php endif; ?>
				<?php endforeach; ?>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="total"> Ongoing Projects <br><b><?php echo $projCount;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing Tasks <br><br><b><?php echo $taskCount;?></b></h4>
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
              <!-- START LOOP HERE -->
              <?php foreach ($projects as $row): ?>
								<h4><?php echo $row['PROJECTTITLE']; ?></h4>
	              <table class="table table-bordered">
	                <thead>
	                  <tr>
											<th width=".5%"></th>
	                    <th width="27%">Task</th>
	                    <th width="10%">Start Date</th>
	                    <th width="10%">Target<br>End Date</th>
	                    <th class="text-center" width="17.5%">A</th>
	                    <th class="text-center" width="17.5%">C</th>
	                    <th class="text-center" width="17.5%">I</th>
	                  </tr>
	                </thead>
	                <tbody>
	                  <?php foreach ($tasks as $t): ?>
											<?php if ($row['PROJECTID'] == $t['PROJECTID']): ?>
												<tr data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>">
													<?php if ($t['TASKSTATUS'] == 'Ongoing'): ?>
														<td class="bg-green"></td>
													<?php elseif ($t['TASKSTATUS'] == 'Delayed'): ?>
														<td class="bg-red"></td>
													<?php elseif ($t['TASKSTATUS'] == 'Planning'): ?>
														<td class="bg-yellow"></td>
													<?php elseif ($t['TASKSTATUS'] == 'Complete'): ?>
														<td class="bg-teal"></td>
													<?php else: ?>
														<td></td>
													<?php endif; ?>
													<!-- <td></td> -->
													<td><?php echo $t['TASKTITLE']; ?></td>

													<?php
														if($t['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
															$endDate = $t['TASKENDDATE'];
														else
															$endDate = $t['TASKADJUSTEDENDDATE'];

														if($t['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
															$startDate = $t['TASKSTARTDATE'];
														else
															$startDate = $t['TASKADJUSTEDSTARTDATE'];
													?>

			                    <td><?php echo date_format(date_create($startDate), "M d, Y"); ?></td>
			                    <td><?php echo date_format(date_create($endDate), "M d, Y"); ?></td>
			                    <td>
														<?php foreach ($raci as $raciRow): ?>
															<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																<?php if ($raciRow['ROLE'] == '2'): ?>
																	<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																<?php endif; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													</td>
			                    <td>
														<?php foreach ($raci as $raciRow): ?>
															<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																<?php if ($raciRow['ROLE'] == '3'): ?>
																	<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																<?php endif; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													</td>
			                    <td>
														<?php foreach ($raci as $raciRow): ?>
															<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																<?php if ($raciRow['ROLE'] == '4'): ?>
																	<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																<?php endif; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													</td>
			                  </tr>
											<?php endif; ?>
										<?php endforeach; ?>
	                </tbody>
	              </table>
							<?php endforeach; ?>
              <!-- END LOOP HERE -->
            </div>
            <!-- /.box-body -->
          </div>

					<!-- Task Details Modal -->
					<div class="modal fade" id="taskDetails" tabindex="-1">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h2 class="modal-title" id='taskTitle'>Task Name here</h2>
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
			$("#monitorTeam").addClass("active");
      $('.circlechart').circlechart(); // Initialization

			$(document).on("click", ".task", function(){
				var $taskID = $(this).attr('data-id');

				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/loadTaskHistory"); ?>",
					data: {task_ID: $taskID},
					dataType: 'json',
					success:function(data)
					{
						$("#taskTitle").html(data['task'].TASKTITLE);

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
