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
						<!-- IDK HOW TO MAKE THIS WORK. RETURNS TO projectGantt -->
						<a href="<?php echo base_url("index.php/controller/myProjects"); ?>" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to My Projects</a>
					</div>
					<h1>
						<?php echo $projectProfile['PROJECTTITLE']; ?>
							<a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"><i class="fa fa-edit"></i></a>
					</h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<li class="active"><?php echo $projectProfile['PROJECTTITLE']; ?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<h4><i><?php echo $projectProfile['PROJECTDESCRIPTION']; ?></i></h4>
					<div>

						<?php
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
						$current = date_create(date("Y-m-d")); // get current date
						?>

						<h4>Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($enddate, "F d, Y"); ?> (<?php echo $projectProfile['duration'];?> day/s)</h4>

						<h4 style="color:red">
							<?php if ($current >= $startdate):?>
								<?php echo $projectProfile['remaining'];?> Day/s Remaining
							<?php else:?>
								<?php echo $projectProfile['launching'];?> Day/s Remaining before Project Launch
							<?php endif;?>
						</h4>

						<form name="gantt" action ='projectDocuments' method="POST" id ="prjID">
							<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
							<input type="hidden" name="projectID_logs" value="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>

						<!-- IF USING GET METHOD
						<a href="<?php echo base_url("index.php/controller/projectDocuments/?id=") . $projectProfile['PROJECTID']; ?>" name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a> -->
						<!-- <a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"class="btn btn-default btn-xs"><i class="fa fa-flag"></i> View Logs</a> -->

						<a name="PROJECTID" class="btn btn-success btn" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a>

						<a name="PROJECTID_logs" class="btn btn-success btn" id="projectLog"><i class="fa fa-flag"></i> View Logs</a>

						<a name="" class="btn btn-default btn" id="makeTemplate"><i class="fa fa-window-maximize"></i> Make Project a Template</a>

						<a name="" class="btn btn-default btn" id="parkProject"><i class="fa fa-clock-o"></i> Park Project</a>

						<a name="" class="btn btn-danger btn" id="deleteProject"><i class="fa fa-remove"></i> Delete Project</a>

					</div>
					<br>
					<div id="container" style="height: auto;"></div>

					<!-- </section> -->
				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>
			$("#myProjects").addClass("active");

			// $("#projectDocu").click(function()
			// {
			// 	("#gantt").submit();
      // });

		</script>

		<!-- Javascript for Tasks -->

		<script>

			$('.select2').select2()

			$(function ()
			{
				//Date picker
 	     $('#startDate').datepicker({
 	       autoclose: true
 	     });

 	     $('#endDate').datepicker({
 	       autoclose: true
 	     });
		 });

			$("#projectDocu").click(function() //redirect to individual project profile
      {
				$("#prjID").submit();
      });

			$("#projectLog").click(function() //redirect to individual project logs
      {
				$("#prjID").attr("action","projectLogs");
				// console.log("hello");
				$("#prjID").submit();
      });
		</script>

		<script>

			anychart.onDocumentReady(function (){

					// name: task TITLE
					// actualStart: Start date
					// actualEnd: End date
					// baselineStart: Actual Start date
					// baselineEnd: Actual End date
					// period: actual end - actual start
					// progressValue - progress (completeness)
					// 'progress':{'fill': 'red'}

					// planned and actual in data
					// {
					//   'name': "revision",
					//   'actualStart': Date.UTC(2010, 5, 1, 8),
					//   'actualEnd': Date.UTC(2010, 5, 24, 18),
					//   'actual':
					//       {
					//           'fill':
					//           {
					//               'keys': ['orange', 'red'],
					//               'angle': 0
					//           },
					//       },
					//   'baselineStart': Date.UTC(2010, 4, 29, 9),
					//   'baselineEnd': Date.UTC(2010, 5, 27, 18),
					//   'baseline':
					//       {
					//           'stroke': '3 black',
					//           'fill': {'color': 'gray'}
					//       }
					//   '"connectTo": "5",
					//   "connectorType": "finish-start"'
					//      'connector':
					//        {
					//         'stroke': {color: '#3300CC .2'},
					//          'fill': {'color': '6600CC .5'}
					//        }
					// }

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

						if($value['CATEGORY'] == 1){
							echo "
														{
															'id': " . $value['TASKID'] . ",
															'name': '" . $value['TASKTITLE'] . "',
															'actualStart': '" . $formatted_startDate . "',
															'actualEnd': '" . $formatted_endDate . "',
															'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
															'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
															'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
															'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
															'period': '" . $value['taskDuration'] . "',
															'progressValue': '100%'
														},";
						} else {
							echo "
														{
															'id': " . $value['TASKID'] . ",
															'name': '" . $value['TASKTITLE'] . "',
															'actualStart': '" . $formatted_startDate . "',
															'actualEnd': '" . $formatted_endDate . "',
															'parent': '" . $value['tasks_TASKPARENT'] . "',
															'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
															'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
															'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
															'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
															'period': '" . $value['taskDuration'] . "',
															'progressValue': '100%'
														},";

						}

				// START: Completed task - ProgressValue = 100%
				//     if($value['TASKSTATUS'] == 'Complete'){
				//
				//       // START: Planning - no baseline since task have not yet started
				//           if($value['TASKACTUALSTARTDATE'] == NULL){
				//             echo "
				//               {
				//                 'id': " . $value['TASKID'] . ",
				//                 'name': '" . $value['TASKTITLE'] . "',
				//                 'actualStart': '" . $formatted_startDate . "',
				//                 'actualEnd': '" . $formatted_endDate . "',
				//                 'parent': '" . $value['tasks_TASKPARENT'] . "',
				//                 'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
				//                 'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
				//                 'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
				//                 'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
				//                 'period': '" . $value['taskDuration'] . "',
				//                 'progressValue': '100%'
				//               },";
				//           }
				//           // END: Planning - no baseline since task have not yet started
				//
				//       // START: Ongoing tasks - baselineEnd is the date today
				//           else if($value['TASKACTUALENDDATE'] == NULL){
				//           // START: Ongoing tasks - delayed // TODO:  FIX
				//             if($formatted_endDate < date('M d, Y')){
				//               echo "
				//                 {
				//                   'id': " . $value['TASKID'] . ",
				//                   'name': '" . $value['TASKTITLE'] . "',
				//                   'actualStart': '" . $formatted_startDate . "',
				//                   'actualEnd': '" . $formatted_endDate . "',
				//                   'parent': '" . $value['tasks_TASKPARENT'] . "',
				//                   'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
				//                   'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
				//                   'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
				//                   'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
				//                   'period': '" . $value['taskDuration'] . "',
				//                   'baselineStart': '" . $formatted_actualStartDate . "',
				//                   'baselineEnd': '" . date('M d, Y') . "',
				//                   'progressValue': '100%'
				//                 },";
				//             }
				//             // END: Ongoing tasks - delayed
				//
				//           // START: Ongoing tasks - but not delayed // TODO:  FIX
				//             else if ($formatted_endDate >= date('M d, Y')){
				//               echo "
				//                 {
				//                   'id': " . $value['TASKID'] . ",
				//                   'name': '" . $value['TASKTITLE'] . "',
				//                   'actualStart': '" . $formatted_startDate . "',
				//                   'actualEnd': '" . $formatted_endDate . "',
				//                   'parent': '" . $value['tasks_TASKPARENT'] . "',
				//                   'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
				//                   'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
				//                   'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
				//                   'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
				//                   'period': '" . $value['taskDuration'] . "',
				//                   'baselineStart': '" . $formatted_actualStartDate . "',
				//                   'baselineEnd': '" . date('M d, Y') . "',
				//                   'progressValue': '100%'
				//                 },";
				//             }
				//             // END: Ongoing tasks - but not delayed
				//           }
				//           // END: Ongoing tasks - baselineEnd is the date today
				//
				//       // START: Completed tasks - baselineStart and baselineEnd are present
				//           else{
				//             echo "
				//               {
				//                 'id': " . $value['TASKID'] . ",
				//                 'name': '" . $value['TASKTITLE'] . "',
				//                 'actualStart': '" . $formatted_startDate . "',
				//                 'actualEnd': '" . $formatted_endDate . "',
				//                 'parent': '" . $value['tasks_TASKPARENT'] . "',
				//                 'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
				//                 'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
				//                 'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
				//                 'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
				//                 'period': '" . $value['taskDuration'] . "',
				//                 'baselineStart': '" . $formatted_actualStartDate . "',
				//                 'baselineEnd': '" . $formatted_actualEndDate . "',
				//                 'progressValue': '100%'
				//               },";
				//             }
				//             // END: Completed tasks - baselineStart and baselineEnd are present
				//     }
				//     // END: Completed task - ProgressValue = 100%
				//
				// // START: ProgressValue = 0%
				//     else{
				//
				//       // START: Planning - no baseline since task have not yet started
				//           if($value['TASKACTUALSTARTDATE'] == NULL){
				//             echo "
				//               {
				//                 'id': " . $value['TASKID'] . ",
				//                 'name': '" . $value['TASKTITLE'] . "',
				//                 'actualStart': '" . $formatted_startDate . "',
				//                 'actualEnd': '" . $formatted_endDate . "',
				//                 'parent': '" . $value['tasks_TASKPARENT'] . "',
				//                 'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
				//                 'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
				//                 'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
				//                 'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
				//                 'period': '" . $value['taskDuration'] . "',
				//                 'progressValue': '0%'
				//               },";
				//           }
				//           // END: Planning - no baseline since task have not yet started
				//
				//       // START: Ongoing tasks - baselineEnd is the date today
				//           else if($value['TASKACTUALENDDATE'] == NULL){
				//           // START: Ongoing tasks - delayed // TODO:  FIX
				//             if($formatted_endDate < date('M d, Y')){
				//               echo "
				//                 {
				//                   'id': " . $value['TASKID'] . ",
				//                   'name': '" . $value['TASKTITLE'] . "',
				//                   'actualStart': '" . $formatted_startDate . "',
				//                   'actualEnd': '" . $formatted_endDate . "',
				//                   'parent': '" . $value['tasks_TASKPARENT'] . "',
				//                   'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
				//                   'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
				//                   'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
				//                   'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
				//                   'period': '" . $value['taskDuration'] . "',
				//                   'baselineStart': '" . $formatted_actualStartDate . "',
				//                   'baselineEnd': '" . date('M d, Y') . "',
				//                   'progressValue': '0%'
				//                 },";
				//             }
				//             // END: Ongoing tasks - delayed
				//
				//           // START: Ongoing tasks - but not delayed // TODO:  FIX
				//             else if ($formatted_endDate >= date('M d, Y')){
				//               echo "
				//                 {
				//                   'id': " . $value['TASKID'] . ",
				//                   'name': '" . $value['TASKTITLE'] . "',
				//                   'actualStart': '" . $formatted_startDate . "',
				//                   'actualEnd': '" . $formatted_endDate . "',
				//                   'parent': '" . $value['tasks_TASKPARENT'] . "',
				//                   'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
				//                   'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
				//                   'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
				//                   'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
				//                   'period': '" . $value['taskDuration'] . "',
				//                   'baselineStart': '" . $formatted_actualStartDate . "',
				//                   'baselineEnd': '" . date('M d, Y') . "',
				//                   'progressValue': '0%'
				//                 },";
				//             }
				//             // END: Ongoing tasks - but not delayed
				//           }
				//           // END: Ongoing tasks - baselineEnd is the date today
				//
				//       // START: Completed tasks - baselineStart and baselineEnd are present
				//           else{
				//             echo "
				//               {
				//                 'id': " . $value['TASKID'] . ",
				//                 'name': '" . $value['TASKTITLE'] . "',
				//                 'actualStart': '" . $formatted_startDate . "',
				//                 'actualEnd': '" . $formatted_endDate . "',
				//                 'parent': '" . $value['tasks_TASKPARENT'] . "',
				//                 'responsible': '" . $responsible[$key]['FIRSTNAME'] . " " . $responsible[$key]['LASTNAME']  ."',
				//                 'accountable': '" . $accountable[$key]['FIRSTNAME'] . " " . $accountable[$key]['LASTNAME']  ."',
				//                 'consulted': '" . $consulted[$key]['FIRSTNAME'] . " " . $consulted[$key]['LASTNAME']  ."',
				//                 'informed': '" . $informed[$key]['FIRSTNAME'] . " " . $informed[$key]['LASTNAME']  ."',
				//                 'period': '" . $value['taskDuration'] . "',
				//                 'baselineStart': '" . $formatted_actualStartDate . "',
				//                 'baselineEnd': '" . $formatted_actualEndDate . "',
				//                 'progressValue': '0%'
				//               },";
				//             }
				//             // END: Completed tasks - baselineStart and baselineEnd are present
				//     }
						// END: ProgressValue = 0%
					}
					// END: Foreach
					?>
				];

				// data tree settings
				var treeData = anychart.data.tree(rawData, "as-tree");
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

				chart.splitterPosition(1040);
				chart.container('container').draw();      // set container and initiate drawing

			});

// OTHER CONDITIONS
			// if(date('Y-m-d') < $value['TASKSTARTDATE'] and $value['TASKSTATUS'] != 'Complete'){
			//   echo "
			//   {
			//       'id':" . $value['TASKID'] . ",
			//       'name': '" . $value['TASKTITLE'] . "',
			//       'actualStart': '" . $formattedStartDate . "',
			//       'actualEnd': '" . $formattedEndDate . "',
			//       'actual':
			//       {
			//         'fill':
			//         {
			//           'keys': ['grey'],
			//         },
			//       },
			//       'baselineStart': '" . $value['TASKACTUALSTARTDATE'] . "',
			//       'baselineEnd': '" . $value['TASKACTUALENDDATE'] . "',
			//       'period': '" . $value['taskDuration'] . "',
			//   },
			//   ";
			// }

			// if(date('Y-m-d') < $value['TASKSTARTDATE'] and $value['TASKSTATUS'] != 'Complete'){
			//   echo "
			//   {
			//       'id':" . $value['TASKID'] . ",
			//       'name': '" . $value['TASKTITLE'] . "',
			//       'actualStart': '" . $formattedStartDate . "',
			//       'actualEnd': '" . $formattedEndDate . "',
			//       'actual':
			//       {
			//         'fill':
			//         {
			//           'keys': ['grey'],
			//         },
			//       },
			//       'baselineStart': '" . $formattedActualStartDate . "',
			//       'baselineEnd': '" . $formattedActualEndDate . "',
			//       'period': '" . $value['taskDuration'] . "',
			//   },
			//   ";
			// }
			//
			// if(date('Y-m-d') > $value['TASKENDDATE'] and $value['TASKSTATUS'] != 'Complete'){
			//   echo "
			//   {
			//       'id':" . $value['TASKID'] . ",
			//       'name': '" . $value['TASKTITLE'] . "',
			//       'actualStart': '" . $formattedStartDate . "',
			//       'actualEnd': '" . $formattedEndDate . "',
			//       'actual':
			//       {
			//         'fill':
			//         {
			//           'keys': ['red'],
			//         },
			//       },
			//       'baselineStart': '" . $formattedActualStartDate . "',
			//       'baselineEnd': '" . $formattedActualEndDate . "',
			//       'period': '" . $value['taskDuration'] . "',
			//   },
			//   ";
			// }

			// echo "
			//   {
			//       'name': '" . $value['TASKTITLE'] . "',
			//       'actualStart': '" . $formatted_startDate . "',
			//       'actualEnd': '" . $formatted_endDate . "',
			//       'department': '" . $formatted_actualStartDate  . "',
			//       'responsible': '" . $formatted_actualEndDate . "',
			//       'period': '" . $value['taskDuration'] . "'
			//   },";

				// if(date('Y-m-d') < $value['TASKSTARTDATE'] and $value['TASKSTATUS'] != 'Complete'){
				//   echo "
				//     {
				//       'actual': {
				//         'fill': {
				//           'keys': ['grey'],
				//         }
				//       }
				//     },";
				// }
				//
				// if(date('Y-m-d') < $value['TASKSTARTDATE'] and $value['TASKSTATUS'] = 'Complete'){
				//   echo "
				//     {
				//       'actual': {
				//         'fill': {
				//           'keys': ['green'],
				//         }
				//       }
				//     },";
				// }

		</script>
	</body>
</html>
