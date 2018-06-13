<html>
	<head>
		<title>Kernel - My Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTasksStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>Project Title here</h1>
					<h4>Project Details here. Project Details here. Project Details here. Project Details here. Project Details here. Project Details here.</h4>
					<h4>Start date to End date</h4>
					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myTasks"); ?>"><i class="fa fa-dashboard"></i> My Tasks</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<div style="position: relative" class="gantt" id="GanttChartDIV">

				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>
			$("#myProjects").addClass("active");
		</script>

			<!-- Javascript for Gantt Chart -->
	    <script src="<?php echo base_url()."assets/"; ?>jsgantt/jsgantt.js" type="text/javascript"></script>
	    <script type="text/javascript">

		    var g = new JSGantt.GanttChart(document.getElementById('GanttChartDIV'), 'day');

		    <?php

		    foreach($ganttData as $row)
		    {

		      // $curTask = $row['CATEGORY'];

		      $startDate = $row['TASKSTARTDATE'];

		      $startMonth = substr($startDate, 0, 2);
		      $startDay = substr($startDate, 3, 2);
		      $startYear = substr($startDate, 6, 4);
		      $formattedStartDate = $startYear . "-" . $startMonth . "-" . $startDay;

		      $endDate = $row['TASKENDDATE'];

		      $endMonth = substr($endDate, 0, 2);
		      $endDay = substr($endDate, 3, 2);
		      $endYear = substr($endDate, 6, 4);
		      $formattedEndDate = $endYear . "-" . $endMonth . "-" . $endDay;

		// CHANGING OF DEPARTMENT NAME
		      if($row['DEPARTMENTNAME'] == "Facilities Administration")
		      {
		        $departmentName = 'FAD';
		      } else {
		        $departmentName = $row['DEPARTMENTNAME'];
		      }

		        $parent = 1;
		        $hasChildren = false;
		        $completion = 100;
		        $MAcounter = 0;
						$group = 0;
						//
		        // if ($row['CATEGORY'] == 1){
		        //   $MAcounter++;
		        // }
						//
		        // foreach ($ganttData as $row) {
						//
		        //   if($row['CATEGORY'] != $curTask){
		        //     if ($row['CATEGORY'] == 1)
		        //     {
		        //       $MAcurrent = $row;
		        //     }
						//
		        //     else {
		        //       $SAcurrent = $row;
		        //     }
		        //   }
		        // }


		// // CATEGORY 3 = TASKS
		//       if ($row['CATEGORY'] == 3){
		//         $group = 0;
		//
		//         if ($row['TASKSTATUS'] == 'Pending'){
		//           $completion = 0;
		//         }
		//
		// // CATEGORY 2 = SUBACTIVITY
		//       } else if ($row['CATEGORY'] == 2) {
		//         if ($row['TASKID'] == $row['tasks_TASKPARENT']){
		//           echo "console.log(". $row['TASKID'] ." + ' yes ' + ". $row['tasks_TASKPARENT'] .");";
		//         }
		//
		// // CATEGORY 1 = MAIN ACTIVITY
		//       } else {
		//           $group = 1;
		//       }

		// FOR GROUPING
					$currentTask = $row['TASKID'];
		      $group = 0;
					$isParent = 0; // 1 = true, 0 = false
					foreach ($ganttData as $data) {
						if($data['tasks_TASKPARENT'] == $currentTask ){
							$group = 1;
						}
					}

		// FOR PARENT
					if($row['tasks_TASKPARENT'] == NULL){
						$parent = 0;
					} else {
						$parent = $row['tasks_TASKPARENT'];
					}

		// FOR COMPLETION
					if($row['TASKSTATUS'] == 'Pending'){
						$complete = 0;
					} else {
						$complete = 100;
					}

		// TODO: CHECK FOR PREREQ
				$dependency = '';
				foreach ($dependencies as $data) {
					if($currentTask == $data['tasks_POSTTASKID']){
						if($row['TASKSTARTDATE'] == $data["TASKSTARTDATE"])
						{
							echo "console.log('".$row['TASKSTARTDATE']." = ".$data["TASKSTARTDATE"]."');";
							$dependency = $data['PRETASKID'];

							echo "g.AddTaskItem(new JSGantt.TaskItem(" . $row['TASKID'] . ", '" .
				      $row['TASKTITLE'] . "','" . $formattedStartDate . "','" . $formattedEndDate . "'," .
				      "'gtaskRed', '', 0, '" . $departmentName . "', " . $complete . ", " . $group . ", " .
							$parent . ", 1, '". $dependency."SS', '', '', g));";
						}
					}
				}


		      echo "g.AddTaskItem(new JSGantt.TaskItem(" . $row['TASKID'] . ", '" .
		      $row['TASKTITLE'] . "','" . $formattedStartDate . "','" . $formattedEndDate . "'," .
		      "'gtaskRed', '', 0, '" . $departmentName . "', " . $complete . ", " . $group . ", " .
					$parent . ", 1, '". $dependency."', '', '', g));";

		    }

		    echo "g.Draw();";

		    ?>
		</script>
	</body>
</html>
