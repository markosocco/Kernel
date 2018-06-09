<!-- DELETE AFTER -->

<html>
	<head>
		<title>Gantt Chart</title>

		<meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <!-- Tell the browser to be responsive to screen width -->
	  <!-- <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
	  <!-- Font Awesome -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/font-awesome/css/font-awesome.min.css">
	  <!-- Ionicons -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/Ionicons/css/ionicons.min.css">
	  <!-- Theme style -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/AdminLTE.css">
		<!-- Select2 -->
	  <link rel="stylesheet" href="../../assets/bower_components/select2/dist/css/select2.min.css">
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="../../assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
	        page. However, you can choose any other skin. Make sure you
	        apply the skin class to the body tag so the changes take effect. -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/skins/skin-red.css">
    <!-- Gantt Chart style -->
    <link href="<?php echo base_url()."assets/"; ?>css/jsgantt.css" rel="stylesheet" type="text/css"/>
	  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	  <!--[if lt IE 9]>
	  <script src="<?php echo base_url()."assets/"; ?>https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="<?php echo base_url()."assets/"; ?>https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	  <![endif]-->

	  <!-- Google Font -->
	  <link rel="stylesheet"
	        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

		<!-- <link rel = "stylesheet" href = "<?php //echo base_url("/assets/css/newProjectTaskStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse">
		<div class="wrapper">

			<div class="content-wrapper">
		    <!-- Content Header (Page header) -->
		    <section class="content-header">
		      <h1>
		        <!-- <?php //echo $project['PROJECTTITLE'] ?>

						<?php //if ($dateDiff <= 1):
						//	$diff = $dateDiff + 1;?>
							//<small><?php //echo $project['PROJECTSTARTDATE'] . " - " . $project['PROJECTENDDATE'] . "\t" . $diff . " day remaining"?></small>
							<?php //endif; ?>

						<?php //if ($dateDiff > 1):
							//$diff = $dateDiff + 1;
							?>
						<small><?php //echo $project['PROJECTSTARTDATE'] . " - " . $project['PROJECTENDDATE'] . "\t" . $diff . " days remaining"?></small>
						<?php //endif; ?> -->
		      </h1>
		      <!-- <ol class="breadcrumb">
		        <li class ="active"><a href="<?php //echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
		        <li class="active">New Project</li>
						<li class="active"><?php //echo $project['PROJECTTITLE'] . " Tasks" ?></li>
		      </ol> -->
		    </section>

		    <!-- Main content -->
		    <section class="content container-fluid">

					<div style="position:relative" class="gantt" id="GanttChartDIV">

		      </div>
		    </section>
		    <!-- /.content -->
		  </div>
		</div>
		<!-- ./wrapper -->

		<!-- REQUIRED JS SCRIPTS -->

		<!-- jQuery 3 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- AdminLTE App -->
		<script src="<?php echo base_url()."assets/"; ?>dist/js/adminlte.min.js"></script>
		<!-- date-range-picker -->
		<script src="../../assets/bower_components/moment/min/moment.min.js"></script>
		<script src="../../assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- bootstrap datepicker -->
		<script src="../../assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<!-- Select2 -->
		<script src="../../assets/bower_components/select2/dist/js/select2.full.min.js"></script>
		<script>
		  $(function ()
			{
				//Initialize Select2 Elements
		    $('.select2').select2()

				//Date picker
 	     $('#taskStartDate').datepicker({
 	       autoclose: true
 	     })

 	     $('#taskEndDate').datepicker({
 	       autoclose: true
 	     })
		  })
		</script>

		<!-- Javascript for Gantt Chart -->
    <script src="<?php echo base_url()."assets/"; ?>jsgantt/jsgantt.js" type="text/javascript"></script>
    <script type="text/javascript">

    var g = new JSGantt.GanttChart(document.getElementById('GanttChartDIV'), 'day');

    // pID:	(required) a unique numeric ID used to identify each row
    // pName:	(required) the task Label
    // pStart:	(required) the task start date, can enter empty date ('') for groups. You can also enter specific time (e.g. 2013-02-20 09:00) for additional precision or half days
    // pEnd:	(required) the task end date, can enter empty date ('') for groups
    // pClass:	(required) the css class for this task
    // pLink:	(optional) any http link to be displayed in tool tip as the "More information" link.
    // pMile:	(optional) indicates whether this is a milestone task - Numeric; 1 = milestone, 0 = not milestone
    // pRes:	(optional) resource name
    // pComp:	(required) completion percent, numeric
    // pGroup:	(optional) indicates whether this is a group task (parent) - Numeric; 0 = normal task, 1 = standard group task, 2 = combined group task*
    // pParent:	(required) identifies a parent pID, this causes this task to be a child of identified task. Numeric, top level tasks should have pParent set to 0
    // pOpen:	(required) indicates whether a standard group task is open when chart is first drawn. Value must be set for all items but is only used by standard group tasks. Numeric, 1 = open, 0 = closed
    // pDepend:	(optional) comma separated list of id's this task is dependent on. A line will be drawn from each listed task to this item. Each id can optionally be followed by a dependency type suffix.
        //Valid values are: 'FS' - Finish to Start (default if suffix is omitted), 'SF' - Start to Finish, 'SS' - Start to Start, 'FF' - Finish to Finish. If present the suffix must be added directly to the id e.g. '123SS'
    // pCaption:	(optional) caption that will be added after task bar if CaptionType set to "Caption"
    // pNotes:	(optional) Detailed task information that will be displayed in tool tip for this task
    // pGantt:	(required) javascript JSGantt.GanttChart object from which to take settings. Defaults to "g" for backwards compatibility

    <?php

    foreach($ganttData as $row)
    {

      // $curTask = $row['CATEGORY'];

// DATE FORMATTING
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

// TODO: Completion rate computation
// TODO: Check if parent or not
// TODO: How to group?

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

// TODO: CHECK FOR PREREQ


      echo "g.AddTaskItem(new JSGantt.TaskItem(" . $row['TASKID'] . ", '" .
      $row['TASKTITLE'] . "','" . $formattedStartDate . "','" . $formattedEndDate . "'," .
      "'gtaskRed', '', 0, '" . $departmentName . "', " . $completion . ", " . $group . ", " . $parent . ", 1, '', '', '', g));";

    }

    echo "g.Draw();";

    ?>

    </script>

	</body>
</html>
