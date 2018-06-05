<html>
	<head>
		<title>Kernel - Project Tasks</title>

		<meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <!-- Tell the browser to be responsive to screen width -->
	  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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

	  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	  <!--[if lt IE 9]>
	  <script src="<?php echo base_url()."assets/"; ?>https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="<?php echo base_url()."assets/"; ?>https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	  <![endif]-->

	  <!-- Google Font -->
	  <link rel="stylesheet"
	        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

		<style>
	    .example-modal .modal {
	      position: relative;
	      top: auto;
	      bottom: auto;
	      right: auto;
	      left: auto;
	      display: block;
	      z-index: 1;
	    }

	    .example-modal .modal {
	      background: transparent !important;
	    }
	  </style>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/newProjectTaskStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<div class="wrapper">

			<div class="content-wrapper">
		    <!-- Content Header (Page header) -->
		    <section class="content-header">
		      <h1>
		        <?php echo $project['PROJECTTITLE'] ?>

						<?php if ($dateDiff <= 1):
							$diff = $dateDiff + 1;?>
							<small><?php echo $project['PROJECTSTARTDATE'] . " - " . $project['PROJECTENDDATE'] . "\t" . $diff . " day remaining"?></small>
							<?php endif; ?>

						<?php if ($dateDiff > 1):
							$diff = $dateDiff + 1;
							?>
						<small><?php echo $project['PROJECTSTARTDATE'] . " - " . $project['PROJECTENDDATE'] . "\t" . $diff . " days remaining"?></small>
						<?php endif; ?>
		      </h1>
		      <ol class="breadcrumb">
		        <li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
		        <li class="active">New Project</li>
						<li class="active"><?php echo $project['PROJECTTITLE'] . " Tasks" ?></li>
		      </ol>
		    </section>

		    <!-- Main content -->
		    <section class="content container-fluid">
					<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
						Add new task
					</button>

					<div class="modal fade" id="modal-default">
	          <div class="modal-dialog">
	            <div class="modal-content">
	              <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                  <span aria-hidden="true">&times;</span></button>
	                <h4 class="modal-title">Add new task</h4>
	              </div>
	              <div class="modal-body">
									<div class="form-group">
	                  <label>Select task category</label>
	                  <select class="form-control">
											<option disabled selected value> -- select an option -- </option>
											<option>Main Activity</option>
		                  <option>Sub Activity</option>
		                  <option>Task</option>
	                  </select>
	                </div>
									<!--Display if category picked above is sub activity  -->
									<div class="form-group">
		                <label>Select parent main activity</label>
		                <select class="form-control select2" style="width: 100%;">
											<option disabled selected value> -- select an option -- </option>
		                  <option>Loop through the list of main activity in the project</option>
		                </select>
		              </div>
									<!--Display if category picked above is task -->
									<div class="form-group">
		                <label>Select parent sub activity</label>
		                <select class="form-control select2" style="width: 100%;">
											<option disabled selected value> -- select an option -- </option>
		                  <option>Loop through the list of sub activity in the project</option>
		                </select>
		              </div>
									<div class="form-group">
	                  <label>Task Title</label>
	                  <input type="text" class="form-control" placeholder="Enter task title">
	                </div>
									<div class="form-group">
		                <label>Start Date:</label>

		                <div class="input-group date">
		                  <div class="input-group-addon">
		                    <i class="fa fa-calendar"></i>
		                  </div>
		                  <input type="text" class="form-control pull-right" id="taskStartDate" name="taskStartDate" required>
		                </div>
		                <!-- /.input group -->
		              </div>
		              <!-- /.form group -->
		              <div class="form-group">
		                <label>Target End Date:</label>

		                <div class="input-group date">
		                  <div class="input-group-addon">
		                    <i class="fa fa-calendar"></i>
		                  </div>
		                  <input type="text" class="form-control pull-right" id="taskEndDate" name ="taskEndDate" required>
		                </div>
										<div class="form-group">
		                  <label>Department assigned</label>
		                  <select class="form-control">
												<option disabled selected value> -- select an option -- </option>
												<option>Loop through department table</option>
		                  </select>
		                </div>
										<!--Display if project owner is same department with department chosen above  -->
										<div class="form-group">
											<label>Select team member</label>
			                <select class="form-control select2" style="width: 100%;">
												<option disabled selected value> -- select an option -- </option>
												<option>Loop through all employess with the same department as the project owner</option>
			                </select>
			              </div>
		              </div>
	              </div>
	              <div class="modal-footer">
	                <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button> -->
	                <button type="button" class="btn btn-primary">Add task</button>
	              </div>
	            </div>
	            <!-- /.modal-content -->
	          </div>
	          <!-- /.modal-dialog -->
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

	</body>
</html>
