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
					<h1><?php echo $projectProfile['PROJECTTITLE']; ?></h1>
        </h2>Department Name Here</h2>

				<!-- Main content -->
				<section class="content container-fluid">
					<div style="margin-bottom:10px">
						<!-- IDK HOW TO MAKE THIS WORK. RETURNS TO projectGantt -->
						<a href="<?php echo base_url("index.php/controller/myTeam"); ?>" class="btn btn-default btn-xs"><i class="fa fa-arrow-left"></i> Return to My Team</a>
					</div>
					<h4><i><?php echo $projectProfile['PROJECTDESCRIPTION']; ?></i></h4>
					<div>

						<?php
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
						$current = date_create(date("Y-m-d")); // get current date
						?>

						<h4>Duration: <?php echo date_format($startdate, "F d, Y"); ?> to <?php echo date_format($enddate, "F d, Y"); ?> (<?php echo $projectProfile['duration'] + 1;?> day/s)</h4>

						<h4 style="color:red">
							<?php if ($current >= $startdate):?>
								<?php echo $projectProfile['remaining'] + 1;?> Day/s Remaining
							<?php else:?>
								<?php echo $projectProfile['launching'];?> Day/s Remaining before Project Launch
							<?php endif;?>
						</h4>

						<form name="gantt" action ='projectDocuments' method="POST" id ="prjID">
							<input type="hidden" name="project_ID" value="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>

						<!-- IF USING GET METHOD
						<a href="<?php echo base_url("index.php/controller/projectDocuments/?id=") . $projectProfile['PROJECTID']; ?>" name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a> -->

						<a name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a>

						<a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"class="btn btn-default btn-xs"><i class="fa fa-flag"></i> View Logs</a>
						<a href="<?php echo base_url("index.php/controller/projectLogs/?id=") . $projectProfile['PROJECTID']; ?>"class="btn btn-default btn-xs"><i class="fa fa-edit"></i> Edit Project</a>
					</div>
					<div style="position: relative" class="gantt" id="GanttChartDIV"></div>

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
				// var $id = $(this).attr('data-id');
				$("#prjID").submit();
        // window.location.replace("<?php echo base_url("index.php/controller/projectGantt/?id="); ?>" + $id);
      });
		</script>

			<!-- Javascript for Gantt Chart -->
	    <script src="<?php echo base_url()."assets/"; ?>jsgantt/jsgantt.js" type="text/javascript"></script>
	    <script type="text/javascript">

		    var g = new JSGantt.GanttChart(document.getElementById('GanttChartDIV'), 'day');

		    <?php

		    foreach($ganttData as $row)
		    {
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
							$dependency = $data['PRETASKID'];

							echo "g.AddTaskItem(new JSGantt.TaskItem(" . $row['TASKID'] . ", '" .
				      $row['TASKTITLE'] . "','" . $row['TASKSTARTDATE'] . "','" . $row['TASKENDDATE'] . "'," .
				      "'gtaskBlue', '', 0, '" . $departmentName . "', " . $complete . ", " . $group . ", " .
							$parent . ", 1, '". $dependency."SS', '', '', g));";
						}
					}
				}

		      echo "g.AddTaskItem(new JSGantt.TaskItem(" . $row['TASKID'] . ", '" .
		      $row['TASKTITLE'] . "','" . $row['TASKSTARTDATE'] . "','" . $row['TASKENDDATE'] . "'," .
		      "'gtaskBlue', '', 0, '" . $departmentName . "', " . $complete . ", " . $group . ", " .
					$parent . ", 1, '". $dependency."', '', '', g));";

		    }

		    echo "g.Draw();";

		    ?>
		</script>
	</body>
</html>
