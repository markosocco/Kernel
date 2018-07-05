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

						<a name="PROJECTID" class="btn btn-success btn-xs" id="projectDocu"><i class="fa fa-folder"></i> View Documents</a>

						<a name="PROJECTID_logs" class="btn btn-success btn-xs" id="projectLog"><i class="fa fa-flag"></i> View Logs</a>

						<a name="" class="btn btn-default btn-xs" id="makeTemplate"><i class="fa fa-window-maximize"></i> Make Project a Template</a>

						<a name="" class="btn btn-default btn-xs" id="parkProject"><i class="fa fa-clock-o"></i> Park Project</a>

						<a name="" class="btn btn-danger btn-xs" id="deleteProject"><i class="fa fa-remove"></i> Delete Project</a>

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
				$("#prjID").submit();
      });

			$("#projectLog").click(function() //redirect to individual project logs
      {
				$("#prjID").attr("action","projectLogs");
				// console.log("hello");
				$("#prjID").submit();
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
