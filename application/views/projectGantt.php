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
					<h1><?php echo $projectProfile['PROJECTTITLE']; ?></h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<h4><i><?php echo $projectProfile['PROJECTDESCRIPTION']; ?></i></h4>
					<div>

						<?php // compute for days remaining and fix date format
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
						$current = date_create(date("Y-m-d")); // get current date
						$edate = date_format($enddate, "Y-m-d");
						$sdate = date_format($startdate, "Y-m-d");
						$enddate2 = date_create($edate);
						$startdate2 = date_create($sdate);
						if ($current > $startdate2) //if ongoing
							$datediff = date_diff($enddate2, $current);
						else // if planned
							$datediff = date_diff($startdate2, $current);
						?>

						<h4>Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($enddate, "F d, Y"); ?></h4>
						<h4>Project duration here (100 days)</h4>
						<h4 style="color:red"><?php echo $datediff->format('%a');?> Days Remaining</h4>
						<a href="<?php echo base_url()."index.php/controller/projectDocuments"; ?>" class="btn btn-success btn-xs"><i class="fa fa-folder"></i> View Documents</a>
						<a href="<?php echo base_url()."index.php/controller/projectLog"; ?>" class="btn btn-default btn-xs"><i class="fa fa-flag"></i> View Logs</a>
					</div>
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
