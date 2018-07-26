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
						<a href="<?php echo base_url("index.php/controller/myTeam"); ?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-left"></i> Return to My Team</a>
					</div>
					<h1><?php echo $projectProfile['PROJECTTITLE']; ?></h1>
        	</h2>Department Name Here</h2>
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
		</script>
		<script>
			anychart.onDocumentReady(function (){

				var rawData = [
					<?php

					foreach ($ganttData as $key => $value) {

						// START: Formatting of TARGET START date
						$startDate = $value['TASKSTARTDATE'];
						$formatted_startDate = date('M d, Y', strtotime($startDate));
						// END: Formatting of TARGET START date

						// START: Formatting of TARGET END date
						$endDate = $value['TASKENDDATE'];
						$formatted_endDate = date('M d, Y', strtotime($endDate));
						// END: Formatting of TARGET END date

						// START: Formatting of ACTUAL START date
						$actualStartDate = $value['TASKACTUALSTARTDATE'];
						$formatted_actualStartDate = date('M d, Y', strtotime($actualStartDate));
						// END: Formatting of ACTUAL START date

						// START: Formatting of ACTUAL END date
						$actualEndDate = $value['TASKACTUALENDDATE'];
						$formatted_actualEndDate = date('M d, Y', strtotime($actualEndDate));
						// END: Formatting of ACTUAL END date

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
							// echo "<script>console.log(".$parent.");</script>";
						}
						// END: Checks for parent

						// START: Checks for period
						$period = '';
						if($value['TASKADJUSTEDSTARTDATE'] == NULL && $value['TASKADJUSTEDENDDATE'] == NULL){
							$period = $value['initialTaskDuration'];
						} else if ($value['TASKADJUSTEDSTARTDATE'] == NULL && $value['TASKADJUSTEDENDDATE'] != NULL){
							$period = $value['adjustedTaskDuration1'];
						} else {
							$period = $value['adjustedTaskDuration2'];
						}
						// END: Checks for period

						// START: Checks for dependecies
						$dependency = '';
						$type = '';
						if($dependencies != NULL){
							foreach ($dependencies as $data) {
								if($data['PRETASKID'] == $value['TASKID']){
									$dependency = $data['tasks_POSTTASKID'];
									$type = 'finish-start';
								}
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
						if($accountable == NULL || $consulted == NULL || $informed == NULL ){
							echo "
							{
								'id': " . $value['TASKID'] . ",
								'name': '" . $value['TASKTITLE'] . "',
								'actualStart': '" . $formatted_startDate . "',
								'actualEnd': '" . $formatted_endDate . "',
								'responsible': '',
								'accountable': '',
								'consulted': '',
								'informed': '',
								'period': '" . $progress . "',
								'progressValue': '0%'
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
										'period': '" . $period . "',
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
										'period': '" . $period . "',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
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
										'period': '" . $period . "',
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
										'period': '" . $period . "',
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
										'period': '" . $period . "',
										'progressValue': '" . $progress . "%',
										'parent': '" . $parent . "',
										'connectTo': '" . $dependency . "',
										'connectorType': '" . $type . "',
										'baselineStart': '" . $formatted_actualStartDate . "',
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
										'period': '" . $period . "',
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

				// create custom column
				var columnTitle = dataGrid.column(1);
				columnTitle.title("Task Name");
				columnTitle.setColumnFormat("name", "text");
				columnTitle.width(300);

				var columnStartDate = dataGrid.column(2);
				columnStartDate.title("Target Start Date");
				columnStartDate.setColumnFormat("actualStart", "dateCommonLog");
				columnStartDate.width(100);

				var columnEndDate = dataGrid.column(3);

				columnEndDate.title("Target End Date");
				columnEndDate.setColumnFormat("actualEnd", "dateCommonLog");
				columnEndDate.width(100);

				var columnPeriod = dataGrid.column(4);
				columnPeriod.title("Period");
				columnPeriod.setColumnFormat("period", "text");
				columnPeriod.width(80);

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

				//get chart timeline link to change color

				chart.zoomTo("week", 3, "firstDate");
				chart.splitterPosition(650);
				chart.container('container').draw();      // set container and initiate drawing

			});
		</script>
	</body>
</html>
