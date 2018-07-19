<html>
	<head>
		<title>Kernel - Add Task Dependencies</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/addDependenciesStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
		    <!-- Content Header (Page header) -->
		    <section class="content-header">
		      <h1>
		        <?php echo $project['PROJECTTITLE'] ?>

						<?php
						$startdate = date_create($project['PROJECTSTARTDATE']);
						$enddate = date_create($project['PROJECTENDDATE']);
						?>

						<?php $diff = $dateDiff + 1;?>
						<small><?php echo date_format($startdate, "F d, Y") . " - " . date_format($enddate, "F d, Y"). "\t(" . $diff;?>
						<?php if ($dateDiff < 1):?>
							day remaining)</small>
						<?php else:?>
							days remaining)</small>
						<?php endif;?>

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
		              <h3 class="box-title">Enter task dependencies</h3>
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
								<form id='addDependencies' name = 'addDependencies' action = 'addDependencies' method="POST">

                  <input type="hidden" name="project_ID" value="<?php echo $project['PROJECTID']; ?>">

									<?php $c = 0; ?>

  								<?php foreach ($mainActivity as $key=>$value): ?>
  		            <div class="box-body table-responsive no-padding">
  		              <table class="table table-hover" id="table_<?php echo $key;?>">

  										<?php if($key == 0): ?>

  										<thead>
  			                <tr>
  												<th>Task Title</th>
  												<th>Department</th>
  												<th>Start Date</th>
  												<th>Target End Date</th>
  												<th>Dependency</th>
  			                </tr>
  										</thead>

  									<?php endif; ?>

  										<tbody>
  											<tr>
  												<td><b><?php echo $value['TASKTITLE']; ?></b></td>
  												<td>
  													<?php
  														foreach ($allTasks as $row)
  														{
  															if($value['TASKTITLE'] == $row['TASKTITLE'])
  															{
  																$depts = array();

  																foreach ($departments as $row2)
  																{
  																	if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
  																	{
  																		$depts[] = $row2['DEPARTMENTNAME'];
  																	}
  																}

  																//TODO: Fix implode shit
  																foreach ($depts as $x)
  																{
  																	echo $x . ", ";
  																}
  															}
  														}
  													?>
  												</td>
  												<td><?php echo $value['TASKSTARTDATE']; ?></td>
  												<td><?php echo $value['TASKENDDATE']; ?></td>
  												<td></td>
  											</tr>

  											<?php foreach ($subActivity as $sKey => $sValue): ?>
  													<?php if ($sValue['tasks_TASKPARENT'] == $value['TASKID']): ?>
  														<tr>
  															<td><i><?php echo $sValue['TASKTITLE']; ?></i></td>
  															<td>
  																<?php
  																	foreach ($allTasks as $row)
  																	{
  																		if($sValue['TASKTITLE'] == $row['TASKTITLE'])
  																		{
  																			$depts = array();

  																			foreach ($departments as $row2)
  																			{
  																				if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
  																				{
  																					$depts[] = $row2['DEPARTMENTNAME'];
  																				}
  																			}

  																			//TODO: Fix implode shit
  																			foreach ($depts as $x)
  																			{
  																				echo $x . ", ";
  																			}
  																		}
  																	}
  																?>
  															</td>
  															<td><?php echo $sValue['TASKSTARTDATE']; ?></td>
  															<td><?php echo $sValue['TASKENDDATE']; ?></td>
  															<td></td>
  														</tr>

															<?php foreach ($tasks as $tKey => $tValue): ?>
																<?php if($tValue['tasks_TASKPARENT'] == $sValue['TASKID']): ?>
																	<tr>
																		<td>
																			<!-- TASK NAME @TASK LEVEL -->
																			<?php echo $tValue['TASKTITLE']; ?>
																		</td>
																		<!-- CHANGE TO NORMAL SELECT. 1:1 -->
																		<td width="40%">
																			<!-- DEPARTMENT @TASK LEVEL -->
																			<?php
		  																	foreach ($allTasks as $row)
		  																	{
		  																		if($tValue['TASKTITLE'] == $row['TASKTITLE'])
		  																		{
		  																			$depts = array();

		  																			foreach ($departments as $row2)
		  																			{
		  																				if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
		  																				{
		  																					$depts[] = $row2['DEPARTMENTNAME'];
		  																				}
		  																			}

		  																			//TODO: Fix implode shit
		  																			foreach ($depts as $x)
		  																			{
		  																				echo $x . ", ";
		  																			}
		  																		}
		  																	}
		  																?>
																		</td>
																		<td>
																			<!-- START DATE @TASK LEVEL -->
																			<?php echo $tValue['TASKSTARTDATE']; ?>
																		</td>
																		<td>
																			<!-- END DATE @TASK LEVEL -->
																			<?php echo $tValue['TASKENDDATE']; ?>
																		</td>
																		<!-- DEPENDENCY INPUT -->
																		<td width="50%">
																			<input type="hidden" name="taskID[]" value="<?php echo $tValue['TASKID']; ?>">
																			<select class="form-control select2" multiple="multiple" name = "dependencies[<?php echo $c; ?>][]" data-placeholder="Select Task">
																					<?php foreach ($groupedTasks as $gKey => $gValue): ?>
																							<option value ='<?php echo $gValue['TASKID']; ?>'>
																								<?php echo $gValue['TASKTITLE']; ?>
																							</option>
																					<?php endforeach; ?>
																			</select>
																		</td>

																	</tr>
																	<?php $c++; ?>
															<?php endif; ?>
														<?php endforeach; ?>

  												<?php endif; ?>
  											<?php endforeach; ?>
  										</tbody>
  									</table>
  		            </div>
  								<?php endforeach; ?>

  		            <!-- /.box-body -->
  								<div class="box-footer">
  									<button type="button" class="btn btn-success">Previous: Add Tasks</button>
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
