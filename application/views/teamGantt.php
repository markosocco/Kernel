<html>
	<head>
		<title>Kernel - <?php echo  $projectProfile['PROJECTTITLE'];?></title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTasksStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<div style="margin-bottom:10px">
						<a href="<?php echo base_url("index.php/controller/myProjects"); ?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-left"></i> Return to My Projects</a>
					</div>
					<h1><?php echo $projectProfile['PROJECTTITLE']; ?></h1>
				</h2><?php echo $_SESSION['DEPARTMENTNAME']; ?></h2>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<h4>Project Owner: <?php echo $projectProfile['FIRSTNAME']; ?> <?php echo $projectProfile['LASTNAME']; ?></h4>
					<h4>Description: <?php echo $projectProfile['PROJECTDESCRIPTION']; ?></h4>
					<div>

						<?php
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
						$current = date_create(date("Y-m-d")); // get current date
						?>

						<h4>Initial Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($enddate, "F d, Y"); ?>
							(<?php echo $projectProfile['duration'];?>
							<?php if($projectProfile['duration'] > 1):?>
								days)
							<?php else:?>
								day)
							<?php endif;?>
						</h4>

						<?php if ($projectProfile['PROJECTSTATUS'] == 'Archived' || $projectProfile['PROJECTSTATUS'] == 'Complete'):?>
							<?php $actualEnd = date_create($projectProfile['PROJECTACTUALENDDATE']);?>

							<h4 style="color:red">Actual Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($actualEnd, "F d, Y"); ?>
								(<?php echo $projectProfile['actualDuration'];?>
								<?php if($projectProfile['actualDuration'] > 1):?>
									days)
								<?php else:?>
									day)
								<?php endif;?>
							</h4>

						<?php else:?>

							<h4 style="color:red">
								<?php if ($current >= $startdate && $current <= $enddate && $projectProfile['PROJECTSTATUS'] == 'Ongoing'):?>
									<?php echo $projectProfile['remaining'];?>
									<?php if($projectProfile['remaining'] > 1):?>
										days remaining
									<?php else:?>
										day remaining
									<?php endif;?>
								<?php elseif ($current < $startdate && $projectProfile['PROJECTSTATUS'] == 'Planning'):?>
									Launch in <?php echo $projectProfile['launching'];?>
									<?php if($projectProfile['launching'] > 1):?>
										days
									<?php else:?>
										day
									<?php endif;?>
								<?php elseif ($current >= $startdate && $current >= $enddate && $projectProfile['PROJECTSTATUS'] == 'Ongoing'):?>
									<?php echo $projectProfile['delayed'];?>
									<?php if($projectProfile['delayed'] > 1):?>
										days delayed
									<?php else:?>
										day delayed
									<?php endif;?>
								<?php endif;?>
							</h4>

						<?php endif;?>

						<?php if($projectProfile['PROJECTSTATUS'] != 'Planning'): ?>

							<div class="col-md-3 col-sm-6 col-xs-12 pull-right">
									<div class="box-header with-border" style="text-align:center;">
										<h3 class="box-title"><?php echo $_SESSION['DEPARTMENTNAME'];?> Performance</h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
										<div style="display:inline-block; text-align:center; width:49%;">
											<div class="circlechart" id="completeness"
												data-percentage="<?php
													if($departmentCompleteness['completeness'] == NULL){
														echo 0;
													} else {
														if($departmentCompleteness['completeness'] == 100.00){
															echo 100;
														} elseif ($departmentCompleteness['completeness'] == 0.00) {
															echo 0;
		 												} else {
		 													echo $departmentCompleteness['completeness'];
		 												}
		 											}
	 										 ?> "> Completeness
											</div>
										</div>
										<div style="display:inline-block; text-align:center; width:49%;">
											<div class="circlechart" id="completeness"
		 									 data-percentage="<?php
												 if($departmentTimeliness['timeliness'] == NULL){
													 echo 0;
												 } else {
													 if($departmentTimeliness['timeliness'] == 100.00){
														 echo 100;
													 } elseif ($departmentTimeliness['timeliness'] == 0.00) {
														 echo 0;
													 } else {
														 echo $departmentTimeliness['timeliness'];
													 }
												 }
											 ?> "> Timeliness
		 								 </div>
									 </div>
									</div>
							</div>
							<!-- /.col -->

							<?php
								$isResponsible = FALSE;
								foreach ($responsible as $r) {
									if($r['USERID'] == $_SESSION['USERID']){
										$isResponsible = TRUE;
										break;
									}
								}
							?>

							<?php if($isResponsible == TRUE): ?>
								<div class="col-md-3 col-sm-6 col-xs-12 pull-right" style="border-right: solid 1px #b3b3b3;">
										<div class="box-header with-border" style="text-align:center;">
											<h3 class="box-title">My Performance</h3>
										</div>
										<!-- /.box-header -->
										<div class="box-body">
											<div style="display:inline-block; text-align:center; width:49%;">
												<div class="circlechart" id="completeness"
													data-percentage="<?php
														if($employeeCompleteness['completeness'] == NULL){
															echo 0;
														} else {
															if($employeeCompleteness['completeness'] == 100.00){
																echo 100;
															} elseif ($employeeCompleteness['completeness'] == 0.00) {
																echo 0;
															} else {
																echo $employeeCompleteness['completeness'];
															}
														}
														?> "> Completeness
												</div>
											</div>
											<div style="display:inline-block; text-align:center; width:49%;">
												<div class="circlechart" id="completeness"
			 									 data-percentage="<?php
												 if($employeeTimeliness['timeliness'] == NULL){
													 echo 0;
												 } else {
													 if($employeeTimeliness['timeliness'] == 100.00){
														 echo 100;
													 } elseif ($employeeTimeliness['timeliness'] == 0.00) {
														 echo 0;
													 } else {
														 echo $employeeTimeliness['timeliness'];
													 }
												 }
												 ?> ">Timeliness
			 								 </div>
										 </div>
										</div>
								</div>
							<?php endif;?>
						<?php endif; ?>

						<form name="gantt" action ='projectDocuments' method="POST" id ="prjID">
							<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
							<input type="hidden" name="projectID_logs" value="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>

						<!-- IF USING GET METHOD
						<a href="<?php echo base_url("index.php/controller/projectDocuments/?id=") . $projectProfile['PROJECTID']; ?>" name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a> -->
						<!-- <a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"class="btn btn-default btn-xs"><i class="fa fa-flag"></i> View Logs</a> -->

					</div>
					<br>
					<div id="container" style="height: 600px;"></div>

					<!-- </section> -->
				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>
			$("#myTeam").addClass("active");
			$('.circlechart').circlechart(); // Initialization

		</script>
		<script>
			anychart.onDocumentReady(function (){

				var rawData = [
					<?php

					foreach ($ganttData as $key => $value) {

						// START: Formatting of TARGET START date
						$targetStartDate = $value['TASKSTARTDATE'];
						$formatted_startDate = date('Y-m-d', strtotime($targetStartDate));
						// END: Formatting of TARGET START date

						// START: Formatting of TARGET END date
						$targetEndDate = $value['TASKENDDATE'];
						$formatted_endDate = date('Y-m-d', strtotime($targetEndDate));
						// END: Formatting of TARGET END date

						// START: Formatting of ACTUAL START date
						$actualStartDate = $value['TASKACTUALSTARTDATE'];
						$formatted_actualStartDate = date('Y-m-d', strtotime($actualStartDate));
						// END: Formatting of ACTUAL START date

						// START: Formatting of ACTUAL END date
						$actualEndDate = $value['TASKACTUALENDDATE'];
						$formatted_actualEndDate = date('Y-m-d', strtotime($actualEndDate));
						// END: Formatting of ACTUAL END date

						// // START: Formatting of ADJUSTED START date
						// $adjustedStartDate = $value['TASKADJUSTEDSTARTDATE'];
						// $formatted_adjustedStartDate = date('Y-m-d', strtotime($adjustedStartDate));
						// // END: Formatting of ACTUAL END date
						//
						// // START: Formatting of ADJUSTED END date
						// $adjustedEndDate = $value['TASKADJUSTEDENDDATE'];
						// $formatted_adjustedEndDate = date('Y-m-d', strtotime($adjustedEndDate));
						// // END: Formatting of ACTUAL END date

						// START: Checks for progress value
						$progress = '0';
						if($value['TASKSTATUS'] == 'Complete' && $value['CATEGORY'] == 3){
							$progress = 100;
						}
						// END: Checks for progress value

						// START: Checks for parent
						$parent = '0';
						if($value['tasks_TASKPARENT'] != NULL){
							$parent = $value['tasks_TASKPARENT'];
						}
						// END: Checks for parent

						// // START: Checks for period
						// $period = '';
						// if($value['TASKADJUSTEDSTARTDATE'] == NULL && $value['TASKADJUSTEDENDDATE'] == NULL){
						// 	$period = $value['initialTaskDuration'];
						// } else if ($value['TASKADJUSTEDSTARTDATE'] == NULL && $value['TASKADJUSTEDENDDATE'] != NULL){
						// 	$period = $value['adjustedTaskDuration1'];
						// } else {
						// 	$period = $value['adjustedTaskDuration2'];
						// }
						// echo "<script>console.log(".$period.");</script>";
						// // END: Checks for period

						// START: Checks for dependecies
						$dependency = '';
						$type = '';
						foreach ($dependencies as $data) {
							if($data['PRETASKID'] == $value['TASKID']){
								$dependency = $data['tasks_POSTTASKID'];
								$type = 'finish-start';
							}
						}
						// END: Checks for dependecies

						// START: Checks for responsible
						$responsiblePerson = '';
						foreach ($responsible as $r) {
							if($r['tasks_TASKID'] == $value['TASKID']){
								$responsiblePerson = $r['FIRSTNAME'] . " " . $r['LASTNAME'];
							}
						}
						// END: Checks for responsible

						// START: Checks for accountable
						$accountablePerson = '';
						foreach ($accountable as $a) {
							if($a['tasks_TASKID'] == $value['TASKID']){
								$accountablePerson = $a['FIRSTNAME'] . " " . $a['LASTNAME'];
							}
						}
						// END: Checks for accountable

						// START: Checks for consulted
						$consultedPerson = '';
						foreach ($consulted as $c) {
							if($c['tasks_TASKID'] == $value['TASKID']){
								$consultedPerson = $c['FIRSTNAME'] . " " . $c['LASTNAME'];
							}
						}
						// END: Checks for consulted

						// START: Checks for informed
						$informedPerson = '';
						foreach ($informed as $i) {
							if($i['tasks_TASKID'] == $value['TASKID']){
								$informedPerson = $c['FIRSTNAME'] . " " . $i['LASTNAME'];
							}
						}
						// END: Checks for informed

						//START: CHECKS IF RACI IS EMPTY
						if($accountable == NULL || $consulted == NULL || $informed == NULL){
							echo "
							{
								'id': " . $value['TASKID'] . ",
								'name': '" . $value['TASKTITLE'] . "',
								'actualStart': '" . $formatted_startDate .  "T00:00',
								'actualEnd': '" . $formatted_endDate . "T13:00',
								'responsible': '',
								'accountable': '',
								'consulted': '',
								'informed': '',
								'period': '" . $value['initialTaskDuration'] . "',
								'progressValue': '0%',
								'parent': '" . $parent . "'
							},";
						} else { // START: RACI IS NOT EMPTY
							// START: CHECKS IF MAIN OR SUB
							if($value['CATEGORY'] == 2 || $value['CATEGORY'] == 1){
								// START: Planning - no baseline since task have not yet started
								if(($value['TASKACTUALSTARTDATE'] == NULL)){
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] . "',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "'
									},";
								} // END: Planning - no baseline since task have not yet started

								// START: Ongoing tasks - baselineEnd is the date today
								else if($value['TASKACTUALENDDATE'] == NULL){
									// not delayed
									if($value['TASKENDDATE'] > date('Y-m-d')){ // ongoing but not delayed
										// echo "<script> console.log(''); </script>";
										$color = "green";
									} else { // ongoing and delayed
										// echo "<script> console.log(''); </script>";
										$color = "#F53006";
									}
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] ."',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
										'baselineEnd': '" . date('M d, Y') . "',
										'baseline':{'fill': '" .$color. "'},
									},";
								} // END: Ongoing tasks - baselineEnd is the date today

								// START: Completed tasks - baselineStart and baselineEnd are present
								else {
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] . "',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
										'baselineEnd': '" . $formatted_actualEndDate . "'
									},";
								} // END: Completed tasks - baselineStart and baselineEnd are present

							} else { // START: IF TASK
								if(($value['TASKACTUALSTARTDATE'] == NULL)){
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] . "',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "'
									},";
								} // END: Planning - no baseline since task have not yet started

								// START: Ongoing tasks - baselineEnd is the date today
								else if($value['TASKACTUALENDDATE'] == NULL){
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] . "',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate ."',
										'baselineEnd': '" . date('M d, Y') . "'
									},";
								} // END: Ongoing tasks - baselineEnd is the date today

								// START: Completed tasks - baselineStart and baselineEnd are present
								else {
									echo "
									{
										'id': " . $value['TASKID'] . ",
										'name': '" . $value['TASKTITLE'] . "',
										'actualStart': '" . $formatted_startDate . "',
										'actualEnd': '" . $formatted_endDate . "',
										'responsible': '" . $responsiblePerson  ."',
										'accountable': '" . $accountablePerson ."',
										'consulted': '" . $consultedPerson  ."',
										'informed': '" . $informedPerson  ."',
										'period': '" . $value['initialTaskDuration'] ."',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
										'baselineEnd': '" . $formatted_actualEndDate . "'
									},";
								} // END: Completed tasks - baselineStart and baselineEnd are present
							} // END: CHECKS FOR CATEGORY
						} // END: CHECKS IF RACI IS EMPTY OR NOT
					} // END: Foreach
					?>

				];

				// data tree settings
				var treeData = anychart.data.tree(rawData, "as-table");
				var chart = anychart.ganttProject();      // chart type
				chart.data(treeData);                     // chart data

				// data grid getter
				var dataGrid = chart.dataGrid();

				dataGrid.column(0).labels({hAlign: 'center'});

				// create custom column
				var columnTitle = dataGrid.column(1);
				columnTitle.title("Task Name");
				columnTitle.setColumnFormat("name", "text");
				columnTitle.width(300);

				var columnStartDate = dataGrid.column(2);
				columnStartDate.title("Target Start Date");
				columnStartDate.labels({hAlign: 'center'});
				columnStartDate.setColumnFormat("actualStart", {
					"formatter": dateFormatter
				});
				columnStartDate.width(100);

				var columnEndDate = dataGrid.column(3);

				columnEndDate.title("Target End Date");
				columnEndDate.labels({hAlign: 'center'});
				columnEndDate.setColumnFormat("actualEnd", {
					"formatter": dateFormatter
				});
				columnEndDate.width(100);

				var columnPeriod = dataGrid.column(4);
				columnPeriod.title("Period");
				columnPeriod.setColumnFormat("period", "text");
				columnPeriod.width(80);
				columnPeriod.labels({hAlign: 'center'});

				var columnResponsible = dataGrid.column(5);
				columnResponsible.title("Responsible");
				columnResponsible.setColumnFormat("responsible", "text");
				columnResponsible.width(100);

				var columnAccountable = dataGrid.column(6);
				columnAccountable.title("Accountable");
				columnAccountable.setColumnFormat("accountable", "text");
				columnAccountable.width(100);

				var columnConsulted = dataGrid.column(7);
				columnConsulted.title("Consulted");
				columnConsulted.setColumnFormat("consulted", "text");
				columnConsulted.width(100);

				var columnInformed = dataGrid.column(9);
				columnInformed.title("Informed");
				columnInformed.setColumnFormat("informed", "text");
				columnInformed.width(100);

				chart.splitterPosition(650);
				chart.zoomTo("week", 2);
				chart.container('container').draw();      // set container and initiate drawing
			});

			function dateFormatter (value){
				// var stringDate = strtotime(value);
				var date = new Date(value);
				var month = date.toLocaleDateString("en-US", {month: "short"});
				var day = date.getDate();
				if(day < 10){
					day = "0"+day;
				}
				var year = date.getFullYear()
				return month + " " + day + ", " + year;
			}
		</script>
	</body>
</html>
