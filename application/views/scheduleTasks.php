<html>
	<head>
		<title>Kernel - Add Tasks</title>
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
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box">
		            <div class="box-header">
		              <h3 class="box-title">Activities and tasks</h3>
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
		            <div class="box-body table-responsive no-padding">
		              <table class="table table-hover">
		                <tr>
		                  <th>No.</th>
		                  <th>Category</th>
											<th>Title</th>
											<th>Department</th>
											<th>Start Date</th>
											<th>Target End Date</th>
											<th>Dependencies</th>
		                </tr>
		                <tr>
		                  <td>1</td>
		                  <td>Main</td>
		                  <td>Receive lease offer</td>
											<td>Marketing</td>
											<td><div class="form-group">
				                <div class="input-group date">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control pull-right" id="taskStartDate" name="taskStartDate" required>
				                </div>
				                <!-- /.input group -->
				              </div></td>
											<td><div class="form-group">
				                <div class="input-group date">
				                  <div class="input-group-addon">
				                    <i class="fa fa-calendar"></i>
				                  </div>
				                  <input type="text" class="form-control pull-right" id="taskEndDate" name ="taskEndDate" required>
				                </div>
											</div></td>
											<td><div class="form-group">
				                <select class="form-control select2" multiple="multiple" data-placeholder="Select prerequisites"
				                        style="width: 100%;">
				                  <option>List per number of total task count</option>
				                </select>
				              </div></td>
		                </tr>
		              </table>
		            </div>
		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-warning">Previous: Add Sub Activities</button>
									<button type="button" class="btn btn-success pull-right" id="ganttChart">Next: Generate Gantt chart</button>
									<button type="button" class="btn btn-primary pull-right">Save</button>
								</div>
		          </div>
		          <!-- /.box -->
		        </div>
		      </div>
		    </section>
		    <!-- /.content -->
		  </div>

		</div>
		<!-- ./wrapper -->

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
