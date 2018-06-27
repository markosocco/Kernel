<html>
	<head>
		<title>Kernel - Arrange tasks</title>

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
		              <h3 class="box-title">Arrange and Schedule Tasks</h3>
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
		                  <th></th>
											<th>No.</th>
		                  <th>Category</th>
											<th>Title</th>
											<th>Department</th>
											<th>Start Date</th>
											<th>Target End Date</th>
											<th>Dependencies</th>
		                </tr>
									</thead>
									<tbody>

										<?php $i = 1; ?>
											<?php foreach ($tasks as $row): ?>

			                <tr class='row' id = "<?php echo $row['TASKID']; ?>" data-id='<?php echo $project['PROJECTSTARTDATE']; ?>'>

												<input type="hidden" name="task_ID[]" value="<?php echo $row['TASKID']; ?>">

												<td class="handle"><i class="fa fa-arrows"></i></td>
												<td> <?php echo $i; ?></td>

												<?php
													switch ($row['CATEGORY'])
													{
														case 1:
															$cat = 'Main Activity';
															break;
														case 2:
															$cat = 'Sub Activity';
															break;
														case 3:
															$cat = 'Task';
													}
												?>

			                  <td><?php echo $cat; ?></td>
			                  <td><?php echo $row['TASKTITLE']; ?></td>
												<td><?php echo $row['dName']; ?></td>
												<td><div class="form-group">
					                <div class="input-group date">
					                  <div class="input-group-addon">
					                    <i class="fa fa-calendar"></i>
					                  </div>
					                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" data-id="<?php echo $row['TASKID'];?>" required>
					                </div>
					                <!-- /.input group -->
					              </div>
											</td>
												<td><div class="form-group">
					                <div class="input-group date">
					                  <div class="input-group-addon">
					                    <i class="fa fa-calendar"></i>
					                  </div>
					                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" data-id="<?php echo $row['TASKID'];?>" required>
					                </div>
												</div></td>
												<td><div class="form-group">
					                <select class="form-control select2" multiple="multiple" data-placeholder="Select prerequisites" style="width: 100%;" id = "selector" name = "dependencies_ <?php echo $row['TASKID']; ?>[]" >
															<?php $x = 1; ?>
															<?php foreach ($tasks as $row): ?>
																<?php echo "<option value = '" . $row['TASKID'] . "'>" . $x . "</option>"; ?>
																<?php $x++; ?>
														<?php endforeach; ?>
														<?php $i++; ?>
					                </select>
					              </div></td>
			                </tr>
										<?php endforeach; ?>
									</tbody>
		              </table>
		            </div>

		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-success">Previous: Add tasks</button>
									<button type="submit" class="btn btn-success pull-right" id="ganttChart">Next: Generate Gantt chart</button>
									<button type="button" class="btn btn-primary pull-right" style="margin-right: 5%">Save</button>
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
