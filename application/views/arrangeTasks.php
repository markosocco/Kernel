<html>
	<head>
		<title>Kernel - Add Sub Activity</title>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/newProjectTaskStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

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
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box">
		            <div class="box-header">
		              <h3 class="box-title">Enter sub activities for this project</h3>
		              <div class="box-tools">
		                <div class="input-group input-group-sm" style="width: 150px;">
		                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

		                  <div class="input-group-btn">
		                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
		                  </div>
		                </div>
		              </div>
		            </div>
		            <!-- /.box-header -->
								<form id='arrangeTasks' name = 'arrangeTasks' action = '<?php echo base_url('index.php/controller/arrangeTasks');?>' method="POST">

									<input type="hidden" name="project_ID" value="<?php echo $project['PROJECTID']; ?>">

		            <div class="box-body table-responsive no-padding">
		              <table class="table table-hover" id="table">
										<thead>
		                <tr>
											<th>Sub Activity Title</th>
											<th>Department</th>
											<th>Start Date</th>
											<th>Target End Date</th>
											<th></th>
		                </tr>
									</thead>
									<tbody>
		                <!-- <tr class='row' id = "<?php //echo $row['TASKID']; ?>" data-id='<?php //echo $project['PROJECTSTARTDATE']; ?>'> -->
										<!-- <input type="hidden" name="task_ID[]" value="<?php echo $row['TASKID']; ?>"> -->
										<td><div class="form-group">
											<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
										</div></td>
										<td><select class="form-control" name = "department[]" required>
											<option disabled selected value> -- Select Department -- </option>

											<?php foreach ($departments as $row): ?>

												<option>
													<?php echo $row['DEPARTMENTNAME']; ?>
												</option>

											<?php endforeach; ?>
										</select></td>
										<td><div class="form-group">
			                <div class="input-group date">
			                  <div class="input-group-addon">
			                    <i class="fa fa-calendar"></i>
			                  </div>
			                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" required>
			                </div>
			                <!-- /.input group -->
			              </div></td>
											<td><div class="form-group">
				                <div class="input-group date">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" required>
				                </div>
											</div></td>
											<td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td>
		                </tr>
										<tr>
											<!-- NEW LINE WILL BE INSERTED HERE -->
										</tr>
										<tfoot>
											<tr>
												<td class="btn" id="addRow" colspan="3"><a class="btn addButton"><i class="glyphicon glyphicon-plus-sign"></i> Add more sub activities</a></td>
											</tr>
										</tfoot>
									</tbody>
		              </table>
		            </div>

		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-success">Previous: Main Activities</button>
									<button type="submit" class="btn btn-success pull-right" id="ganttChart">Next: Generate Gantt chart</button>
									<!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5%">Skip This Step</button> -->
								</div>
								</form>
		          </div>
		          <!-- /.box -->
		        </div>
		      </div>
		    </section>
		    <!-- /.content -->
		  </div>
			<?php require("footer.php"); ?>


		</div>
		<!-- ./wrapper -->

		<script type='text/javascript'>

		$("#myProjects").addClass("active");

		  $(function ()
			{
				//

				$(".row").on("click", function() {
					var startDate = $('.row').attr('data-id');
					console.log("HELLO " + startDate);
 			 });

				//Initialize Select2 Elements
		    $('.select2').select2()

				//Date picker
 	     $('.taskStartDate').datepicker({
				 format: 'yyyy-mm-dd',

 	       autoclose: true
 	     });

 	     $('.taskEndDate').datepicker({
				 format: 'yyyy-mm-dd',
 	       autoclose: true
 	     });
		 });

		 var el = document.getElementById('table');
		 var dragger = tableDragger(el, {
		   mode: 'row',
		   dragHandler: '.handle',
		   onlyBody: true,
		   animation: 300
		 });
		 dragger.on('drop',function(from, to){
		   console.log(from);
		   console.log(to);
		 });
		</script>

	</body>
</html>
