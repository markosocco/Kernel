<html>
	<head>
		<title>Kernel - Add Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/addTasksStyle.css")?>"> -->
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
		              <h3 class="box-title">Enter tasks for this project</h3>
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
								<form id='scheduleTasks' name = 'scheduleTasks' action = '<?php echo base_url('index.php/controller/scheduleTasks');?>' method="POST">

								<input type="hidden" name="project_ID" value="<?php echo $project['PROJECTID']; ?>">

								<?php foreach ($groupedTasks as $key=>$value): ?>
		            <div class="box-body table-responsive no-padding">
		              <table class="table table-hover" id="table_<?php echo $key;?>">

										<?php if($key == 0): ?>

										<thead>
			                <tr>
												<th></th>
												<th>Task Title</th>
												<th>Department</th>
												<th>Start Date</th>
												<th>Target End Date</th>
												<th></th>
			                </tr>
										</thead>

									<?php endif; ?>

										<tbody>
											<tr>
												<td></td>
												<td><b>Main 1</b></td>
												<td>Finance</td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-table="table_<?php echo $key;?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
												<td><i>Sub 1</i></td>
				                <td>HR</td>
												<td>Start</td>
												<td>End</td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													</div>
												</td>
												<!-- CHANGE TO NORMAL SELECT. 1:1 -->
												<td width="40%">
													<select class="form-control select2" multiple="multiple" name = "department_0[]" data-placeholder="Select Departments">
														<?php foreach ($departments as $row): ?>

															<option>
																<?php echo $row['DEPARTMENTNAME']; ?>
															</option>

														<?php endforeach; ?>
					                </select>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" required>
						                </div>
						              </div>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" required>
						                </div>
													</div>
												</td>
												<td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td>
											</tr>
											<tr>
												<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-table="table_<?php echo $key;?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
												<td><i>Sub 2</i></td>
				                <td>MIS</td>
												<td>Start</td>
												<td>End</td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													</div>
												</td>
												<!-- CHANGE TO NORMAL SELECT. 1:1 -->
												<td width="40%">
													<select class="form-control select2" multiple="multiple" name = "department_0[]" data-placeholder="Select Departments">
														<?php foreach ($departments as $row): ?>

															<option>
																<?php echo $row['DEPARTMENTNAME']; ?>
															</option>

														<?php endforeach; ?>
					                </select>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" required>
						                </div>
						              </div>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" required>
						                </div>
													</div>
												</td>
												<td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td>
											</tr>
											<tr>
												<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-table="table_<?php echo $key;?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
												<td><i>Sub 3</i></td>
				                <td>Marketing</td>
												<td>Start</td>
												<td>End</td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													</div>
												</td>
												<!-- CHANGE TO NORMAL SELECT. 1:1 -->
												<td width="40%">
													<select class="form-control select2" multiple="multiple" name = "department_0[]" data-placeholder="Select Departments">
														<?php foreach ($departments as $row): ?>

															<option>
																<?php echo $row['DEPARTMENTNAME']; ?>
															</option>

														<?php endforeach; ?>
					                </select>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" required>
						                </div>
						              </div>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" required>
						                </div>
													</div>
												</td>
												<td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td>
											</tr>
											<!-- DUMMY DATA HERE -->
											<tr>
												<td></td>
												<td><b>Main 2</b></td>
												<td>Finance</td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
											<tr>
												<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-table="table_<?php echo $key;?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
												<td><i>Sub 1</i></td>
				                <td>History</td>
												<td>Start</td>
												<td>End</td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													</div>
												</td>
												<!-- CHANGE TO NORMAL SELECT. 1:1 -->
												<td width="40%">
													<select class="form-control select2" multiple="multiple" name = "department_0[]" data-placeholder="Select Departments">
														<?php foreach ($departments as $row): ?>

															<option>
																<?php echo $row['DEPARTMENTNAME']; ?>
															</option>

														<?php endforeach; ?>
					                </select>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" required>
						                </div>
						              </div>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" required>
						                </div>
													</div>
												</td>
												<td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td>
											</tr>
											<tr>
												<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-table="table_<?php echo $key;?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
												<td><i>Sub 2</i></td>
				                <td>Theology</td>
												<td>Start</td>
												<td>End</td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													</div>
												</td>
												<!-- CHANGE TO NORMAL SELECT. 1:1 -->
												<td width="40%">
													<select class="form-control select2" multiple="multiple" name = "department_0[]" data-placeholder="Select Departments">
														<?php foreach ($departments as $row): ?>

															<option>
																<?php echo $row['DEPARTMENTNAME']; ?>
															</option>

														<?php endforeach; ?>
					                </select>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" required>
						                </div>
						              </div>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" required>
						                </div>
													</div>
												</td>
												<td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td>
											</tr>
											<tr>
												<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-table="table_<?php echo $key;?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
												<td><i>Sub 3</i></td>
				                <td>Math</td>
												<td>Start</td>
												<td>End</td>
												<td></td>
											</tr>
											<tr>
												<td></td>
												<td>
													<div class="form-group">
														<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													</div>
												</td>
												<!-- CHANGE TO NORMAL SELECT. 1:1 -->
												<td width="40%">
													<select class="form-control select2" multiple="multiple" name = "department_0[]" data-placeholder="Select Departments">
														<?php foreach ($departments as $row): ?>

															<option>
																<?php echo $row['DEPARTMENTNAME']; ?>
															</option>

														<?php endforeach; ?>
					                </select>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" required>
						                </div>
						              </div>
												</td>
												<td>
													<div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" required>
						                </div>
													</div>
												</td>
												<td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td>
											</tr>
											<!-- DUMMY DATA END -->
										</tbody>
									</table>
		            </div>
								<?php endforeach; ?>

		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-success">Previous: Add Sub Activities</button>
									<button type="submit" class="btn btn-success pull-right" id="scheduleTasks">Generate Gantt Chart</button>
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
			//Initialize Select2 Elements
	    $('.select2').select2()

			//Date picker
			$('body').on('focus',".taskStartDate", function(){
			    $(this).datepicker({
						format: 'yyyy-mm-dd',
	  	       autoclose: true,
						 orientation: 'bottom'
					});
			});

			$('body').on('focus',".taskEndDate", function(){
					$(this).datepicker({
						format: 'yyyy-mm-dd',
						 autoclose: true,
						 orientation: 'bottom'
					});
			});
		 });
		</script>

	</body>
</html>
