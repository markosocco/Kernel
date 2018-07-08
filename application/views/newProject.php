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
		        <li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
		        <li class="active">New Project</li>
		      </ol>
		    </section>

		    <!-- Main content -->
		    <section class="content container-fluid">

					<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Input project details</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
						<form role="form" name = "addProject" id = "addProject" action = "addTasks" method = "POST">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Project Title</label>
									<input type="text" class="form-control" id="projectTitle" name="projectTitle" placeholder="Enter Project Title" required>
                </div>
                <div class="form-group">
									<label>Project Details</label>
									<textarea class="form-control" rows="3" placeholder="Enter project detals..." name="projectDetails" required></textarea>
                </div>

								<div class="row">
					        <div class="col-md-4">
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

									<div class="col-md-4">
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

									<div class="col-md-4">
										<div class="form-group">
											<label for="projectperiod">Project Period</label>
											<input type="text" class="form-control" id="projectPeriod" name="projectTitle" placeholder="" disabled>
										</div>
									</div>
								</div>


              <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-forward"></i> Next: Add Main Activities</button>
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
 	       autoclose: true
 	     });

			 $("#startDate").on("change", function() {
				$("#endDate").prop('disabled', false);
				$('#endDate').data('datepicker').setStartDate(new Date($(this).val()));
				if(new Date($("#endDate").val()) < new Date($("#startDate").val())) //Removes Target Date Input if new Start Date comes after it
					$("#endDate").val("");
				var diff = new Date($("#endDate").datepicker("getDate") - $("#startDate").datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;
				if ($("#startDate").val() != "" && $("#endDate").val() != "" && period >=1)
					$("#projectPeriod").attr("placeholder", period + " day/s");
			 	else
					$("#projectPeriod").attr("placeholder", "");
			 });

 	     $('#endDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true
 	     });

			 $("#endDate").on("change", function() {
				var diff = new Date($("#endDate").datepicker("getDate") - $("#startDate").datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;
				if ($("#startDate").val() != "" && $("#endDate").val() != "" && period >=1)
				 	$("#projectPeriod").attr("placeholder", period + " day/s");
				else
					$("#projectPeriod").attr("placeholder", "");
			 });
		 });

		</script>

	</body>
</html>
