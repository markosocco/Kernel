<html>
	<head>
		<title>Kernel - New Project</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/newProjectStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php include_once("frame.php"); ?>

			<div class="content-wrapper">
		    <!-- Content Header (Page header) -->
		    <section class="content-header">
		      <h1>
		        Create a new project
		        <small>Let's create a new project</small>
		      </h1>
		      <ol class="breadcrumb">
		        <li><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
		        <li class="active">New Project</li>
		      </ol>
		    </section>

		    <!-- Main content -->
		    <section class="content container-fluid">
					<div class="container-fluid">
					  <ul class="list-unstyled multi-steps">
					    <li class="is-active">Project Details</li>
					    <li>Add Main Activities</li>
					    <li>Add Sub Activities</li>
					    <li>Add Tasks</li>
					    <li>Add Dependencies</li>
					  </ul>
					</div>
					<br>

					<div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Input project details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
						<form role="form" name = "addProject" id = "addProject" action = "addTasks" method = "POST">
							<?php if (isset($_SESSION['templates'])): ?>
								<input type="hidden" name="templates" value="<?php echo $project['PROJECTID']; ?>">
							<?php endif;?>
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Project Title</label>
									<?php if (isset($_SESSION['templates'])): ?>
										<input type="text" class="form-control" id="projectTitle" name="projectTitle" placeholder="Enter Project Title" value ="<?php echo $project['PROJECTTITLE']; ?>" required>
									<?php else: ?>
										<input type="text" class="form-control" id="projectTitle" name="projectTitle" placeholder="Enter Project Title" required>
									<?php endif; ?>
                </div>
                <div class="form-group">
									<label>Project Details</label>
									<?php if (isset($_SESSION['templates'])): ?>
										<textarea class="form-control" rows="5" placeholder="Enter project details..." name="projectDetails" required><?php echo $project['PROJECTDESCRIPTION']; ?></textarea>
									<?php else: ?>
										<textarea class="form-control" rows="5" placeholder="Enter project details..." name="projectDetails" required></textarea>
									<?php endif; ?>
                </div>

								<div class="row">
					        <div class="col-md-3">
										<div class="form-group">
			                <label>Start Date</label>
			                <div class="input-group date">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar"></i>
			                  </div>
			                  <input type="text" class="form-control pull-right" id="startDate" name="startDate" required>
			                </div>
			              </div>
									</div>

									<div class="col-md-3">
			              <div class="form-group">
			                <label>Target End Date</label>
			                <div class="input-group date">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar"></i>
			                  </div>
			                  <input type="text" class="form-control pull-right" id="endDate" name ="endDate" required>
			                </div>
			              </div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label for="projectperiod">Project Period</label>

											<?php if (isset($_SESSION['templates'])): ?>
												<?php
													$startdate = date_create($project['PROJECTSTARTDATE']);
													$enddate = date_create($project['PROJECTACTUALENDDATE']);
													$temp = date_diff($enddate, $startdate);
													$dFormat = $temp->format('%d');
													$diff = (int)$dFormat + 1;

													if ($diff >= 1)
													{
														$period = $diff . " day";
													}

													else
													{
														$period = $diff . " days";
													}
												?>
												<input type="text" class="form-control" id="projectPeriod" value="<?php echo $period; ?>" readonly>
											<?php else: ?>
												<input type="text" class="form-control" id="projectPeriod" value="" readonly>
											<?php endif; ?>
										</div>
									</div>
								</div>


              <div class="box-footer">
                <button type="submit" class="btn btn-success pull-right"><i class="fa fa-forward"></i> Add Main Activities</button>
              </div>
            </form>
          </div>

		    </section>
		    <!-- /.content -->
		  </div>
			<?php include_once("footer.php"); ?>

		</div>
		<!-- ./wrapper -->

		<script>
			$("#myProjects").addClass("active");
			$("#endDate").prop('disabled', true);

			var currDate = new Date();
		  $(function ()
			{
				//Date picker
 	    $('#startDate').datepicker({
				 format: 'yyyy-mm-dd',
				 startDate: currDate,
 	       autoclose: true,
				 orientation: 'auto'
 	     });

			 $("#startDate").on("change", function() {
				$("#endDate").prop('disabled', false);
				$('#endDate').data('datepicker').setStartDate(new Date($(this).val()));
				if(new Date($("#endDate").val()) < new Date($("#startDate").val())) //Removes Target Date Input if new Start Date comes after it
					$("#endDate").val("");
				var diff = new Date($("#endDate").datepicker("getDate") - $("#startDate").datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;
				if ($("#startDate").val() != "" && $("#endDate").val() != "" && period >=1)
				{
					if(period > 1)
						$("#projectPeriod").attr("value", period + " days");
					else
						$("#projectPeriod").attr("value", period + " day");
				}
				else
					$("#projectPeriod").attr("value", "");
			 });

 	     $('#endDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true,
				 orientation: 'auto'
 	     });

			 $("#endDate").on("change", function() {
				var diff = new Date($("#endDate").datepicker("getDate") - $("#startDate").datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;
				if ($("#startDate").val() != "" && $("#endDate").val() != "" && period >=1)
				{
					if(period > 1)
						$("#projectPeriod").attr("value", period + " days");
					else
						$("#projectPeriod").attr("value", period + " day");
				}
				else
					$("#projectPeriod").attr("value", "");
			 });
		 });

		</script>

	</body>
</html>
