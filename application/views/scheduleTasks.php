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

						<?php
						$startdate = date_create($project['PROJECTSTARTDATE']);
						$enddate = date_create($project['PROJECTENDDATE']);
						?>

						<?php $diff = $dateDiff + 1;?>
						<small><?php echo date_format($startdate, "F d, Y") . " - " . date_format($enddate, "F d, Y"). "\t(" . $diff;?>
						<?php if ($dateDiff < 1):?>
							day)</small>
						<?php else:?>
							days)</small>
						<?php endif;?>

		      </h1>
		      <ol class="breadcrumb">
		        <li><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<li>New Project</li>
						<li>Main Activity</li>
						<li>Sub Activity</li>
						<li class = 'active'>Tasks</li>
		      </ol>
		    </section>

		    <!-- Main content -->
		    <section class="content container-fluid">
					<div class="container-fluid">
					  <ul class="list-unstyled multi-steps">
					    <li>Project Details</li>
					    <li>Add Main Activities</li>
					    <li>Add Sub Activities</li>
					    <li class="is-active">Add Tasks</li>
					    <li>Add Dependencies</li>
					  </ul>
					</div>
					<br>
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box box-danger">
		            <div class="box-header">
		              <h3 class="box-title">Enter tasks for this project</h3>
		              <div class="box-tools">
		                <!-- <div class="input-group input-group-sm" style="width: 150px;">
		                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

		                  <div class="input-group-btn">
		                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
		                  </div>
		                </div> -->
		              </div>
		            </div>
		            <!-- /.box-header -->
								<form id='scheduleTasks' name = 'scheduleTasks' action = '<?php echo base_url('index.php/controller/scheduleTasks');?>' method="POST">

								<input type="hidden" name="project_ID" value="<?php echo $project['PROJECTID']; ?>">

								<?php if (isset($_SESSION['templates'])): ?>


									<?php $c = 0; ?>

									<?php foreach ($mainActivity as $key=>$value): ?>

										<!-- MAIN ACT TABLE START -->

									<div class="box-body table-responsive no-padding">
			              <table class="table table-hover" id="table_<?php echo $key;?>">

											<?php if($key == 0): ?>

											<thead>
				                <tr>
													<th width="65px"></th>
													<th width="30%">Task Title</th>
													<th width="30%">Department</th>
													<th width="15%">Start Date</th>
													<th width="15%">Target End Date</th>
													<th width="65px">Period</th>
													<th width="65px"></th>
				                </tr>
											</thead>

										<?php endif; ?>

											<tbody>
												<tr>
													<td></td>
													<td><b><?php echo $value['TASKTITLE']; ?></b></td>
													<td><b>
														<?php
															foreach ($tasks as $row)
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
													</b></td>

													<?php
														$startdate = date_create($value['TASKSTARTDATE']);
														$enddate = date_create($value['TASKENDDATE']);
														$diff = date_diff($enddate, $startdate);
														$dDiff = intval($diff->format('%d'));
													?>

													<td><b><?php echo date_format($startdate, "M d, Y"); ?></b></td>
													<td><b><?php echo date_format($enddate, "M d, Y"); ?></b></td>
													<td></td>
												</tr>
											</tbody>
											</table>

											<!-- MAIN ACTIVITY TABLE END  -->

												<?php foreach ($subActivity as $sKey => $sValue): ?>

													<!-- SUB ACT TABLE START -->

														<?php if ($sValue['tasks_TASKPARENT'] == $value['TASKID']): ?>
															<table class="table table-hover" id = "ma<?php echo $key; ?>_s<?php echo $sKey; ?>">
																<thead>
																	<tr>
																		<th width="5%"></th>
																		<th width="30%"></th>
																		<th width="30%"></th>
																		<th width="15%"></th>
																		<th width="15%"></th>
																		<th width="5%"></th>
																	</tr>
																</thead>
															<tbody>
															<tr>
																<td class="btn" id="addRow"><a class="btn addButton" data-subTot="<?php echo count($subActivity); ?>" data-mTable = "<?php echo $key; ?>" data-sTable="<?php echo $sKey; ?>" data-subAct="<?php echo $sValue['TASKID']; ?>" counter="1" data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
																<td style="padding-left:20px;"><i><?php echo $sValue['TASKTITLE']; ?></i></td>
																<td><i>
																	<?php
																		foreach ($tasks as $row)
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
																</i></td>

																<?php
																	$sdate = date_create($sValue['TASKSTARTDATE']);
																	$edate = date_create($sValue['TASKENDDATE']);
																	$diff = date_diff($enddate, $startdate);
																	$dDiff = intval($diff->format('%d'));
																?>

																<td><i><?php echo date_format($sdate, "M d, Y"); ?></i></td>
																<td><i><?php echo date_format($edate, "M d, Y"); ?></i></td>
																<td></td>
															</tr>

															<?php if (isset($templateSubActivity[$sKey])): ?>
																<?php foreach ($templateTasks as $tKey=> $tTask): ?>
																	<?php if ($tTask['tasks_TASKPARENT'] == $templateSubActivity[$sKey]['TASKID']): ?>
																		<tr>
																			<td></td>
																			<td>
																				<div class="form-group">

																					<input type="hidden" name="subActivity_ID[]" value="<?php echo $sValue['TASKID']; ?>">

																					<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" value = "<?php echo $tTask['TASKTITLE']; ?>" required>
																					<input type="hidden" name="row[]" value="<?php echo $c; ?>">
																				</div>
																			</td>
																			<td style="padding-bottom:15px;">
																				<select id ="select<?php echo $c; ?>" class="form-control select2" name = "department[<?php echo $c; ?>][]" data-placeholder="Select Departments">
																					<?php foreach ($departments as $row): ?>
																						<option></option>

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
																						<input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $sValue['TASKID'];?>-<?php echo $tKey; ?>"
																						data-subAct="<?php echo $sValue['TASKID'];?>" data-num="<?php echo $tKey; ?>"
																						data-subStart<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKSTARTDATE']; ?>"
																						data-subEnd<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKENDDATE']; ?>" required>
																					</div>
																				</div>
																			</td>
																			<td>
																				<div class="form-group">
																					<div class="input-group date">
																						<div class="input-group-addon">
																							<i class="fa fa-calendar"></i>
																						</div>
																						<input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $sValue['TASKID'];?>-<?php echo $tKey; ?>"
																						data-subAct="<?php echo $sValue['TASKID']; ?>" data-num="<?php echo $tKey; ?>" required>
																					</div>
																				</div>
																			</td>
																			<td>
																				<div class="form-group">
																					<input id = "projectPeriod_<?php echo $sValue['TASKID']; ?>-<?php echo $tKey; ?>" type="text" class="form-control period" value="" readonly>
																				</div>
																			</td>
																			<td></td>
																			<!-- <td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td> -->
																		</tr>
																	<?php endif; ?>
																<?php endforeach; ?>
															<?php endif; ?>

														</tbody>
													</table>

													<?php $c++; ?>

													<?php endif; ?>
												<?php endforeach; ?>
			            </div>

									<!-- SUB ACT TABLE END -->
									<?php endforeach; ?>


								<?php else: ?>
									<?php $c = 0; ?>

									<?php foreach ($mainActivity as $key=>$value): ?>

										<!-- MAIN ACT TABLE START -->

									<div class="box-body table-responsive no-padding">
			              <table class="table table-hover" id="table_<?php echo $key;?>">

											<?php if($key == 0): ?>

											<thead>
				                <tr>
													<th width="65px"></th>
													<th width="30%">Task Title</th>
													<th width="30%">Department</th>
													<th width="15%">Start Date</th>
													<th width="15%">Target End Date</th>
													<th width="65px">Period</th>
													<th width="65px"></th>
				                </tr>
											</thead>

										<?php endif; ?>

											<tbody>
												<tr>
													<td></td>
													<td><b><?php echo $value['TASKTITLE']; ?></b></td>
													<td><b>
														<?php
															foreach ($tasks as $row)
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
													</b></td>

													<?php
														$startdate = date_create($value['TASKSTARTDATE']);
														$enddate = date_create($value['TASKENDDATE']);
														$diff = date_diff($enddate, $startdate);
														$dDiff = intval($diff->format('%d'));
													?>

													<td><b><?php echo date_format($startdate, "M d, Y"); ?></b></td>
													<td><b><?php echo date_format($enddate, "M d, Y"); ?></b></td>
													<td></td>
												</tr>
											</tbody>
											</table>

											<!-- MAIN ACTIVITY TABLE END  -->

												<?php foreach ($subActivity as $sKey => $sValue): ?>

													<!-- SUB ACT TABLE START -->

														<?php if ($sValue['tasks_TASKPARENT'] == $value['TASKID']): ?>
															<table class="table table-hover" id = "ma<?php echo $key; ?>_s<?php echo $sKey; ?>">
																<thead>
																	<tr>
																		<th width="5%"></th>
																		<th width="30%"></th>
																		<th width="30%"></th>
																		<th width="15%"></th>
																		<th width="15%"></th>
																		<th width="5%"></th>
																	</tr>
																</thead>
															<tbody>
															<tr>
																<td class="btn" id="addRow"><a class="btn addButton" data-subTot="<?php echo count($subActivity); ?>" data-mTable = "<?php echo $key; ?>" data-sTable="<?php echo $sKey; ?>" data-subAct="<?php echo $sValue['TASKID']; ?>" counter="1" data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
																<td style="padding-left:20px;"><i><?php echo $sValue['TASKTITLE']; ?></i></td>
																<td><i>
																	<?php
																		foreach ($tasks as $row)
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
																</i></td>

																<?php
																	$sdate = date_create($sValue['TASKSTARTDATE']);
																	$edate = date_create($sValue['TASKENDDATE']);
																	$diff = date_diff($enddate, $startdate);
																	$dDiff = intval($diff->format('%d'));
																?>

																<td><i><?php echo date_format($sdate, "M d, Y"); ?></i></td>
																<td><i><?php echo date_format($edate, "M d, Y"); ?></i></td>
																<td></td>
															</tr>
															<tr>
																<td></td>
																<td>
																	<div class="form-group">

																		<input type="hidden" name="subActivity_ID[]" value="<?php echo $sValue['TASKID']; ?>">

																		<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
																		<input type="hidden" name="row[]" value="<?php echo $c; ?>">
																	</div>
																</td>
																<td style="padding-bottom:15px;">
																	<select id ="select<?php echo $c; ?>" class="form-control select2" name = "department[<?php echo $c; ?>][]" data-placeholder="Select Departments">
																		<?php foreach ($departments as $row): ?>
																			<option></option>

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
																			<input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $sValue['TASKID'];?>-<?php echo $sKey; ?>"
																			data-subAct="<?php echo $sValue['TASKID'];?>" data-num="<?php echo $sKey; ?>"
																			data-subStart<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKSTARTDATE']; ?>"
																			data-subEnd<?php echo $sValue['TASKID']; ?> = "<?php echo $sValue['TASKENDDATE']; ?>" required>
																		</div>
																	</div>
																</td>
																<td>
																	<div class="form-group">
																		<div class="input-group date">
																			<div class="input-group-addon">
																				<i class="fa fa-calendar"></i>
																			</div>
																			<input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $sValue['TASKID'];?>-<?php echo $sKey; ?>"
																			data-subAct="<?php echo $sValue['TASKID']; ?>" data-num="<?php echo $sKey; ?>" required>
																		</div>
																	</div>
																</td>
																<td>
																	<div class="form-group">
																		<input id = "projectPeriod_<?php echo $sValue['TASKID']; ?>-<?php echo $sKey; ?>" type="text" class="form-control period" value="" readonly>
																	</div>
																</td>
																<td></td>
																<!-- <td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td> -->
															</tr>
														</tbody>
													</table>

													<?php $c++; ?>

													<?php endif; ?>
												<?php endforeach; ?>
			            </div>

									<!-- SUB ACT TABLE END -->
									<?php endforeach; ?>
								<?php endif; ?>

		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-success"><i class="fa fa-backward"></i> Add Sub Activities</button>
									<button type="submit" class="btn btn-success pull-right" id="scheduleTasks"><i class="fa fa-forward"></i> Add Dependencies</button>
									<!-- <button id ="skipStep" type="button" class="btn btn-primary pull-right" style="margin-right: 5%"><i class="fa fa-fast-forward"></i> Skip This Step</button> -->
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
		$.fn.datepicker.defaults.format = 'yyyy-mm-dd';
		$.fn.datepicker.defaults.autoclose = 'true';

		$(document).ready(function() {

		 var i = <?php echo (count($subActivity)); ?>;
		 var x = 2;

		 $(document).on("click", "a.addButton", function() {

			 var mTable = $(this).attr('data-mTable');
			 var sTable = $(this).attr('data-sTable');
			 var tot = $(this).attr('data-subTot');
			 var subAct = $(this).attr('data-subAct');
			 var counter = parseInt($(this).attr('data-sum'));

				 $('#ma' + mTable + '_s' + sTable).append(
					 					"<tr id='ma" + mTable + "_s" + (i) +
										"'><td></td><td><div class ='form-group'> <input type='hidden' name='subActivity_ID[]' value='" +
										subAct + "'> <input type='text' class='form-control' placeholder='Enter task title' name ='title[]' required>  <input type='hidden' name = 'row[]' value='" + i + "' >  </div></td>" +
										"<td><select id = 'select" + i + "' class='form-control select2' name = '' data-placeholder='Select Departments'> " +
										"<option></option> <?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>';  }?>" +
										"</select></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
										"<i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskStartDate' " +
										"name='taskStartDate[]' id='start_" + subAct + "-" + counter +"' data-subAct = '" + subAct + "' data-num='" + counter +
										"' required></div></div></td> <td><div class='form-group'><div class='input-group date'>" +
										"<div class='input-group-addon'><i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskEndDate'" +
										"name='taskEndDate[]' id='end_" + subAct + "-" + counter + "' data-subAct = '" + subAct + "' data-num='" + counter +
										"' required></div></div></td><td><div class='form-group'><input id = 'projectPeriod_" + subAct + "-" + counter + "' type='text'" +
										" class='form-control period' value=''readonly></div></td> <td class='btn'><a class='btn delButton' data-mTable = " + mTable +
										" counter = " + x + " data-sTable = " + (i) + "><i class='glyphicon glyphicon-trash'></i></a></td></tr>");

					$("#end_" + subAct + "-" + counter).prop('disabled', true);

				 var newCount = counter + 1;
				 var newTot = tot + 1;

				 $("a.addButton").attr('counter', newCount);
				 $("a.addButton").attr('data-subTot', newTot);

				  $('.select2').select2();
					$("#select" + i).attr("name", "department[" + i + "][]");

				 i++;
				 x++;
			});

			$(document).on("click", "a.delButton", function() {
					if (x > 2)
					{
						var mTable = $(this).attr('data-mTable');
						var sTable = $(this).attr('data-sTable');

						$('#ma' + mTable + '_s' + sTable).remove();
					}
				});
			 });

	  $(function ()
		{
			//Initialize Select2 Elements
	    $('.select2').select2();
			$(".taskEndDate").prop('disabled', true);

			//Date picker
			$('body').on('focus',".taskStartDate", function(){
				var subAct = $(this).attr('data-subAct');
				var counter = $(this).attr('data-num');
				var subStart = $("#start_" + subAct + "-" + counter).attr('data-subStart' + subAct);
				var subEnd = $("#start_" + subAct + "-" + counter).attr('data-subEnd' + subAct);

					$(this).datepicker({
						format: 'yyyy-mm-dd',
						 autoclose: true,
						 startDate: subStart,
						 endDate: subEnd,
						 orientation: 'auto'
					});
			});

			$("body").on("change", ".taskStartDate", function(e) {
				var subAct = $(this).attr('data-subAct');
				var counter = $(this).attr('data-num');
				var newDate = $(this).val();

			$("#end_" + subAct + "-" + counter).prop('disabled', false);
			var diff = new Date($("#end_" + subAct + "-" + counter).datepicker("getDate") - $("#start_" + subAct + "-" + counter).datepicker("getDate"));
			var period = (diff/1000/60/60/24)+1;
			if ($("#start_" + subAct + "-" + counter).val() != "" &&  $("#end_" + subAct + "-" + counter).val() != "" && period >=1)
			{
				if(period > 1)
					$("#projectPeriod_" + subAct + "-" + counter).attr("value", period + " days");
				else
					$("#projectPeriod_" + subAct + "-" + counter).attr("value", period + " day");
			}
			else
			{
				$("#projectPeriod_" + subAct + "-" + counter).attr("value", "");
				$("#end_" + subAct + "-" + counter).val("");
			}

			var subEnd = $("#start_" + subAct + "-" + counter).attr('data-subEnd' + subAct);
			$("#end_" + subAct + "-" + counter).data('datepicker').setStartDate(new Date($("#start_" + subAct + "-" + counter).val()));
			$("#end_" + subAct + "-" + counter).data('datepicker').setEndDate(new Date(subEnd));

			});

			$('body').on('focus',".taskEndDate", function(){
				var subAct = $(this).attr('data-subAct');
				var counter = $(this).attr('data-num');

					$(this).datepicker({
						format: 'yyyy-mm-dd',
						 autoclose: true,
						 orientation: 'auto'
					});
			});

			$("body").on("change", ".taskEndDate", function() {
				var subAct = $(this).attr('data-subAct');
				var counter = $(this).attr('data-num');
				var diff = new Date($("#end_" + subAct + "-" + counter).datepicker("getDate") - $("#start_" + subAct + "-" + counter).datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;
				if ($("#start_" + subAct + "-" + counter).val() != "" &&  $("#end_" + subAct + "-" + counter).val() != "" && period >=1)
				{
					if(period > 1)
						$("#projectPeriod_" + subAct + "-" + counter).attr("value", period + " days");
					else
						$("#projectPeriod_" + subAct + "-" + counter).attr("value", period + " day");
				}
				else
					$("#projectPeriod_" + subAct + "-" + counter).attr("value", "");
			});

	 });

		</script>

	</body>
</html>
