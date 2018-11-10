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
											?>">Completeness
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
										 ?>">Timeliness
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
							<?php $projCount = $p['PROJECTCOUNT']; ?>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php $taskCount = 0;?>
					<?php foreach ($tCount as $t): ?>
					<?php  if ($t['users_USERID'] == $user['USERID']): ?>
						<?php $taskCount =  $t['TASKCOUNT']; ?>
					<?php endif; ?>
				<?php endforeach; ?>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="total"> Ongoing Projects <br><br><b><?php echo $projCount;?></b></h4>
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
							<?php if($projects == NULL): ?>
								<h3 class = "projects" align="center">There are no projects</h3>
							<?php else: ?>
              <?php foreach ($projects as $row): ?>
								<h4><?php echo $row['PROJECTTITLE']; ?></h4>
	              <table class="table table-bordered">
	                <thead>
	                  <tr>
											<th width=".5%"></th>
	                    <th width="27%">Task</th>
	                    <th width="10%" class="text-center">Start Date</th>
	                    <th width="10%" class="text-center">Target<br>End Date</th>
	                    <th class="text-center" width="17.5%">A</th>
	                    <th class="text-center" width="17.5%">C</th>
	                    <th class="text-center" width="17.5%">I</th>
	                  </tr>
	                </thead>
	                <tbody>
	                  <?php foreach ($tasks as $t): ?>
											<?php if ($row['PROJECTID'] == $t['PROJECTID']): ?>
												<?php
												if($t['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
													$startDate = date_create($t['TASKSTARTDATE']);
												else
													$startDate = date_create($t['TASKADJUSTEDSTARTDATE']);

												if($t['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
													$endDate = $t['TASKENDDATE'];
												else
													$endDate = $t['TASKADJUSTEDENDDATE'];

												if($endDate < $t['currDate'] && $t['TASKSTATUS'] == 'Ongoing')
													$delay = "true";
												else {
													$delay = "false";
												}
												?>
												<tr data-toggle='modal' data-target='#taskDetails' class='clickable task' data-id="<?php echo $t['TASKID'];?>" data-delay="<?php echo $delay;?>">
													<?php if ($t['TASKSTATUS'] == 'Ongoing'): ?>
														<?php if($endDate >= $t['currDate']):?>
															<td class="bg-green"></td>
														<?php else:?>
															<td class="bg-red"></td>
														<?php endif;?>
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
						<?php endif; ?>
              <!-- END LOOP HERE -->
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
                  <div class="btn-group">
										<button type="button" id = "tabDependency" class="btn btn-default tabDetails">Dependencies</button>
										<button type="button" id = "tabRACI" class="btn btn-default tabDetails">RACI</button>
										<button type="button" id = "tabRFC" class="btn btn-default tabDetails">RFC</button>
										<button type="button" id = "tabDelay" class="btn btn-default tabDetails">Delay</button>
									</div>
									<br><br>

                  <div id="divRACI" class="divDetails">
										<table class="table table-bordered">
											<thead id="raciHeader">
												<th colspan = '4'>Current</th>
												<tr>
													<th width="25%" class='text-center'>R</th>
													<th width="25%" class='text-center'>A</th>
													<th width="25%" class='text-center'>C</th>
													<th width="25%" class='text-center'>I</th>
												</tr>
											</thead>
											<tbody id="raciCurrentTable">
											</tbody>
										</table>

										<table class="table table-bordered">
											<thead>
												<th colspan = '4'>History</th>
												<tr class='text-center'><td id="raciHistoryTitle" colspan='4'></td></tr>
												<tr id="raciHeader2">
													<th width="25%" class='text-center'>R</th>
													<th width="25%" class='text-center'>A</th>
													<th width="25%" class='text-center'>C</th>
													<th width="25%" class='text-center'>I</th>
												</tr>
											</thead>
											<tbody id="raciHistoryTable">
											</tbody>
										</table>
									</div>

									<div id="divRFC" class="divDetails">
										<table class="table table-bordered">
											<thead id="rfcHeader">
	                      <tr>
	                        <th width="1%" class='text-center'>Type</th>
	                        <th width="20%">Requested By</th>
													<th width="20%" class='text-center'>Date Requested</th>
													<th width="18%" class='text-center'>Status</th>
	                        <th width="20%">Reviewed By</th>
	                        <th width="20%" class='text-center'>Date Reviewed</th>
	                      </tr>
	                    </thead>
	                    <tbody id="rfcHistory">
	                    </tbody>
                  	</table>
									</div>

									<div id="divDelay" class="divDetails">
										<table class="table table-bordered" id='projectDelayTable'>
											<thead>
												<th colspan = '2'>Project</th>
												<tr id="affectedDelayHeader">
													<td width="25%">Target End Date</td>
													<td id='projectEndDates'>MMM DD, YYYY</td>
												</tr>
											</thead>
											<tbody id="projectDelayData">
											</tbody>
										</table>

										<table class="table table-bordered">
											<thead id="affectedDelay">
												<th colspan = '5'>Affected Tasks Projection</th>
												<tr class='text-center'><td id="affectedTitle" colspan='5'></td></tr>
												<tr id="affectedDelayHeader">
													<th width="1%"></th>
													<th width="35%">Task</th>
													<th width="20%" class="text-center">Start Date</th>
													<th width="20%" class="text-center">End Date</th>
													<th width="24%">Responsible</th>
												</tr>
	                    </thead>
	                    <tbody id="affectedDelayData">
	                    </tbody>
                  	</table>
									</div>

									<div id="divDependency" class="divDetails">
										<table class="table table-bordered">
											<thead>
												<th colspan = '5'>Pre-Requisites</th>
												<tr class='text-center'><td id="preReqTitle" colspan='5'></td></tr>
	                      <tr id="dependencyPreHeader">
													<th width="1%"></th>
													<th width="35%">Task</th>
													<th width="20%" class="text-center">Start Date</th>
													<th width="20%" class="text-center">End Date</th>
													<th width="24%">Responsible</th>
	                      </tr>
	                    </thead>
	                    <tbody id="dependencyPreBody">
	                    </tbody>
                  	</table>

										<table class="table table-bordered">
											<thead>
												<th colspan = '5'>Post-Requisites</th>
												<tr class='text-center'><td id="postReqTitle" colspan='5'></td></tr>
												<tr id="dependencyPostHeader">
													<th width="1%"></th>
													<th width="35%">Task</th>
													<th width="20%" class="text-center">Start Date</th>
													<th width="20%" class="text-center">End Date</th>
													<th width="24%">Responsible</th>
												</tr>
	                    </thead>
	                    <tbody id="dependencyPostBody">
	                    </tbody>
                  	</table>
									</div>

                </div>
								<!-- /.modal-body -->
								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-right" data-dismiss="modal" data-toggle="tooltip" data-placement="left" title="Close"><i class="fa fa-close"></i></button>
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
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$("#tabDependency").addClass("active");
				$("#divDependency").show();

				var $taskID = $(this).attr('data-id');
				var $isDelayed = $(this).attr('data-delay');

				if($isDelayed == 'true'){
					$("#tabDelay").show();
					$("#projectDelayTable").hide();

					// DELAY
					$.ajax({
		 			 type:"POST",
		 			 url: "<?php echo base_url("index.php/controller/getDelayEffect"); ?>",
		 			 data: {task_ID: $taskID},
		 			 dataType: 'json',
		 			 success:function(affectedTasks)
		 			 {
						 $('#affectedTitle').hide();
						 $('#affectedDelayData').html("");

						 if(affectedTasks.length > 0)
						 {
	 						 var d = new Date();
	 						 var month = d.getMonth()+1;
	 					   var day = d.getDate();
	 						 var currDate = d.getFullYear() + '-' +
	 							    ((''+month).length<2 ? '0' : '') + month + '-' +
	 							    ((''+day).length<2 ? '0' : '') + day;

							 for(i=0; i < affectedTasks.length; i++)
							 {
								 if(affectedTasks[i].id != null)
								 {
									 if(affectedTasks[i].taskStatus == "Complete")
									 {
										 var status = "<td class='bg-teal'></td>";
									 }
									 if(affectedTasks[i].taskStatus == "Planning")
									 {
										 var status = "<td class='bg-yellow'></td>";
									 }
									 if(affectedTasks[i].taskStatus == "Ongoing")
									 {
										 if(currDate > affectedTasks[i].endDate)
											 var status = "<td class='bg-red'></td>";
										 else
											 var status = "<td class='bg-green'></td>";
									 }

									 $('#affectedDelayData').append(
													"<tr>" + status +
													"<td>" + affectedTasks[i].taskTitle+"</td>"+
													"<td align='center'><span style='color:gray'><strike>" + moment(affectedTasks[i].startDate).format('MMM DD, YYYY') + "</strike></span><br>" + moment(affectedTasks[i].newStartDate).format('MMM DD, YYYY') + "</td>"+
													"<td align='center'><span style='color:gray'><strike>" + moment(affectedTasks[i].endDate).format('MMM DD, YYYY') + "</strike></span><br>" + moment(affectedTasks[i].newEndDate).format('MMM DD, YYYY') + "</td>"+
													"<td>" + affectedTasks[i].responsible + "</td></tr>");

									if(affectedTasks[i].projEndDate < affectedTasks[i].newEndDate)
								 	{
										$("#projectDelayTable").show();
									  $('#projectEndDates').html("<span style='color:gray'><strike>" + moment(affectedTasks[i].projEndDate).format('MMM DD, YYYY') + "</strike></span>");
									  $('#projectEndDates').append("<b> " + moment(affectedTasks[i].newEndDate).format('MMM DD, YYYY')+ " </b>");
								  }
								 }
							 }
						 }
						else
						{
							$("#affectedDelayData").html("<tr><td colspan='5' align='center'>There are no post-requisite tasks that will be affected</td></tr>")
 							$("#affectedDelay").hide();
						}
					 },
					 error:function()
		 			 {
		 				 alert("There was a problem in retrieving the task details");
		 			 }
		 			});

				} else {
					$("#tabDelay").hide();
				}

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
						$("#raciCurrentTable").html("");
						$("#raciHistoryTable").html("");
						$('#raciHistoryTitle').hide();

						var withHistory = false;

						for(rh=0; rh < data['raciHistory'].length; rh++)
						{
							if(data['raciHistory'][rh].ROLE == 1 && data['raciHistory'][rh].STATUS == 'Current')
							{
								$("#raciCurrentTable").append(
									"<tr>" +
										"<td id = 'currentR'></td>" +
										"<td id = 'currentA'></td>" +
										"<td id = 'currentC'></td>" +
										"<td id = 'currentI'></td>" +
									"</tr>");

								for(rc=0; rc < data['raciHistory'].length; rc++)
								{
									if(data['raciHistory'][rc].ROLE == 1 && data['raciHistory'][rc].STATUS == 'Current')
									{
										$("#currentR").append(data['raciHistory'][rc].FIRSTNAME + " " + data['raciHistory'][rc].LASTNAME + "<br>");
									}
									if(data['raciHistory'][rc].ROLE == 2 && data['raciHistory'][rc].STATUS == 'Current')
									{
										$("#currentA").append(data['raciHistory'][rc].FIRSTNAME + " " + data['raciHistory'][rc].LASTNAME + "<br>");
									}
									if(data['raciHistory'][rc].ROLE == 3 && data['raciHistory'][rc].STATUS == 'Current')
									{
										$("#currentC").append(data['raciHistory'][rc].FIRSTNAME + " " + data['raciHistory'][rc].LASTNAME + "<br>");
									}
									if(data['raciHistory'][rc].ROLE == 4 && data['raciHistory'][rc].STATUS == 'Current')
									{
										$("#currentI").append(data['raciHistory'][rc].FIRSTNAME + " " + data['raciHistory'][rc].LASTNAME + "<br>");
									}
								}
							}

							if(data['raciHistory'][rh].ROLE == 1 && data['raciHistory'][rh].STATUS == 'Changed')
							{
								var withHistory = true;
								$("#raciHistoryTable").append(
									"<tr>" +
										"<td id = 'changedR'></td>" +
										"<td id = 'changedA'></td>" +
										"<td id = 'changedC'></td>" +
										"<td id = 'changedI'></td>" +
									"</tr>");

								for(ro=0; ro < data['raciHistory'].length; ro++)
								{
									if(data['raciHistory'][ro].ROLE == 1 && data['raciHistory'][ro].STATUS == 'Changed')
									{
										$("#changedR").append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
									}
									if(data['raciHistory'][ro].ROLE == 2 && data['raciHistory'][ro].STATUS == 'Changed')
									{
										$("#changedA").append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
									}
									if(data['raciHistory'][ro].ROLE == 3 && data['raciHistory'][ro].STATUS == 'Changed')
									{
										$("#changedC").append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
									}
									if(data['raciHistory'][ro].ROLE == 4 && data['raciHistory'][ro].STATUS == 'Changed')
									{
										$("#changedI").append(data['raciHistory'][ro].FIRSTNAME + " " + data['raciHistory'][ro].LASTNAME);
									}
								}
							}

						} // end for loop

						if(!withHistory)
						{
							$('#raciHistoryTitle').html("There is no RACI assignment history");
							$('#raciHeader2').hide();
							$('#raciHistoryTitle').show();
						}

						// RFC HISTORY
						if(data['changeRequests'].length <= 0)
						{
							$("#rfcHistory").html("<tr><td colspan='5' align='center'>There is no change request history</td></tr>")
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

								for(u=0; u < data['users'].length; u++)
								{
									if(data['changeRequests'][r].users_REQUESTEDBY == data['users'][u].USERID)
										var requester = data['users'][u].FIRSTNAME + " " + data['users'][u].LASTNAME;

									if(data['changeRequests'][r].users_APPROVEDBY == data['users'][u].USERID)
										var approver = "<td>" + data['users'][u].FIRSTNAME + " " + data['users'][u].LASTNAME + "</td>";
								}

								if(data['changeRequests'][r].REQUESTSTATUS == 'Pending')
								{
									var approver = "<td align='center'>-</td>";
									var reviewDate = "-";
								}
								else
								{
									var reviewDate = moment(data['changeRequests'][r].APPROVEDDATE).format('MMM DD, YYYY');
								}

								$("#rfcHistory").append(
									"<tr>" +
									"<td align='center'>" + type + "</td>" +
									"<td>" + requester + "</td>" +
									"<td align='center'>" + moment(data['changeRequests'][r].REQUESTEDDATE).format('MMM DD, YYYY') + "</td>" +
									"<td align='center'>" + data['changeRequests'][r].REQUESTSTATUS + "</td>" +
									approver  +
									"<td align='center'>" + reviewDate + "</td>" +
									"</tr>");
							}
						}
					},
					error:function(data)
					{
						alert("There was a problem with loading the change requests");
					}
				});

				// PRE-REQUISITES
				$.ajax({
					type:"POST",
					url: "<?php echo base_url("index.php/controller/getDependenciesByTaskID"); ?>",
					data: {task_ID: $taskID},
					dataType: 'json',
					success:function(preReqData)
					{
						if(preReqData['dependencies'].length > 0)
						{
							$('#dependencyPreBody').html("");
							$('#preReqTitle').html("");
							$("#preReqTitle").hide();
							for(i=0; i<preReqData['dependencies'].length; i++)
							{
								var taskStart = moment(preReqData['dependencies'][i].TASKSTARTDATE).format('MMM DD, YYYY');
								var startDate = preReqData['dependencies'][i].TASKSTARTDATE;

								if(preReqData['dependencies'][i].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
								{
									var taskEnd = moment(preReqData['dependencies'][i].TASKENDDATE).format('MMM DD, YYYY');
									var endDate = preReqData['dependencies'][i].TASKENDDATE;
								}
								else
								{
									var taskEnd = moment(preReqData['dependencies'][i].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');
									var endDate = preReqData['dependencies'][i].TASKADJUSTEDENDDATE;
								}

								if(preReqData['dependencies'][i].TASKADJUSTEDSTARTDATE != null && preReqData['dependencies'][i].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(preReqData['dependencies'][i].adjustedTaskDuration2);
								if(preReqData['dependencies'][i].TASKSTARTDATE != null && preReqData['dependencies'][i].TASKADJUSTEDENDDATE != null)
									var taskDuration = parseInt(preReqData['dependencies'][i].adjustedTaskDuration1);
								else
									var taskDuration = parseInt(preReqData['dependencies'][i].initialTaskDuration);

								if(preReqData['dependencies'][i].TASKSTATUS == "Complete")
								{
									var status = "<td class='bg-teal'></td>";
								}
								if(preReqData['dependencies'][i].TASKSTATUS == "Planning")
								{
									var status = "<td class='bg-yellow'></td>";
								}
								if(preReqData['dependencies'][i].TASKSTATUS == "Ongoing")
								{
									if(preReqData['dependencies'][i].currDate > endDate)
										var status = "<td class='bg-red'></td>";
									else
										var status = "<td class='bg-green'></td>";
								}

								$('#dependencyPreBody').append(
														 "<tr>" + status +
														 "<td>" + preReqData['dependencies'][i].TASKTITLE+"</td>"+
														 "<td align='center'>" + taskStart+"</td>"+
														 "<td align='center'>" + taskEnd +"</td>" +
														 "<td>" + preReqData['dependencies'][i].FIRSTNAME + " " + preReqData['dependencies'][i].LASTNAME + "</td></tr>");
						 }
						 $("#dependencyPreHeader").show();
					 }
					 else
					 {
						 $('#preReqTitle').html("There are no pre-requisite tasks");
						 $('#dependencyPreBody').html("");
						 $("#dependencyPreHeader").hide();
						 $("#preReqTitle").show();
					 }
					},
					error:function()
					{
						alert("There was a problem in retrieving the task details");
					}
					});

				// POST REQUISITES
				$.ajax({
	 			 type:"POST",
	 			 url: "<?php echo base_url("index.php/controller/getPostDependenciesByTaskID"); ?>",
	 			 data: {task_ID: $taskID},
	 			 dataType: 'json',
	 			 success:function(postReqData)
	 			 {
	 				 if(postReqData['dependencies'].length > 0)
	 				 {
	 					 $('#dependencyPostBody').html("");
						 $("#postReqTitle").hide();
	 					 for(i=0; i<postReqData['dependencies'].length; i++)
	 					 {
 							 var taskStart = moment(postReqData['dependencies'][i].TASKSTARTDATE).format('MMM DD, YYYY');
 							 var startDate = postReqData['dependencies'][i].TASKSTARTDATE;

	 						 if(postReqData['dependencies'][i].TASKADJUSTEDENDDATE == null) // check if start date has been previously adjusted
	 						 {
	 							 var taskEnd = moment(postReqData['dependencies'][i].TASKENDDATE).format('MMM DD, YYYY');
	 							 var endDate = postReqData['dependencies'][i].TASKENDDATE;
	 						 }
	 						 else
	 						 {
	 							 var taskEnd = moment(postReqData['dependencies'][i].TASKADJUSTEDENDDATE).format('MMM DD, YYYY');
	 							 var endDate = postReqData['dependencies'][i].TASKADJUSTEDENDDATE;
	 						 }

	 						 if(postReqData['dependencies'][i].TASKSTATUS == "Complete")
	 						 {
	 							 var status = "<td class='bg-teal'></td>";
	 						 }
	 						 if(postReqData['dependencies'][i].TASKSTATUS == "Planning")
	 						 {
	 							 var status = "<td class='bg-yellow'></td>";
	 						 }
	 						 if(postReqData['dependencies'][i].TASKSTATUS == "Ongoing")
	 						 {
	 							 if(postReqData['dependencies'][i].currDate > endDate)
	 								 var status = "<td class='bg-red'></td>";
	 							 else
	 								 var status = "<td class='bg-green'></td>";
	 						 }

	 						 $('#dependencyPostBody').append(
	 													"<tr>" + status +
	 													"<td>" + postReqData['dependencies'][i].TASKTITLE+"</td>"+
	 													"<td align='center'>" + taskStart+"</td>"+
	 													"<td align='center'>" + taskEnd +"</td>" +
	 													"<td>" + postReqData['dependencies'][i].FIRSTNAME + " " + postReqData['dependencies'][i].LASTNAME + "</td></tr>");

						}
	 					$("#dependencyPostHeader").show();
	 				}
	 				else
	 				{
	 					$('#postReqTitle').html("There are no post-requisite tasks");
	 					$("#dependencyPostHeader").hide();
						$('#dependencyPostBody').html("");
						$("#postReqTitle").show();
	 				}
	 			 },
	 			 error:function()
	 			 {
	 				 alert("There was a problem in retrieving the task details");
	 			 }
	 			 });
			});

			// TASK DETAILS TABS
			$(document).on("click", "#tabDependency", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divDependency").show();
			});

			$(document).on("click", "#tabRACI", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divRACI").show();
			});

			$(document).on("click", "#tabRFC", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divRFC").show();
			});

			$(document).on("click", "#tabDelay", function(){
				$(".divDetails").hide();
				$(".tabDetails").removeClass('active');
				$(this).addClass('active')
				$("#divDelay").show();
			});		</script>
	</body>
</html>
