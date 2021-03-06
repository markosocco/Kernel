<html>
	<head>
		<title>Kernel - Add Sub Activities</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/addSubsStyle.css")?>"> -->
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
		    </section>

		    <!-- Main content -->
		    <section class="content container-fluid">
					<div class="container-fluid">
					  <ul class="list-unstyled multi-steps">
					    <li>Input Project Details</li>
					    <li>Add Main Activities</li>
					    <li class="is-active">Add Sub Activities</li>
					    <li>Add Tasks</li>
					    <li>Identify Dependencies</li>
					  </ul>
					</div>
					<br>
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box box-danger">
		            <div class="box-header">
		              <h3 class="box-title">Enter sub activities for this project</h3>
		            </div>
		            <!-- /.box-header -->
								<form id='arrangeTasks' name = 'arrangeTasks' action = '<?php echo base_url('index.php/controller/arrangeTasks');?>' method="POST">

									<input type="hidden" name="project_ID" value="<?php echo $project['PROJECTID']; ?>">

									<!-- START OF TEMPLATES -->
								<?php if (isset($_SESSION['templates'])): ?>

									<input type="hidden" name="templates" value="<?php echo $templateProject['PROJECTID']; ?>">

									<?php foreach ($groupedTasks as $key => $value): ?>
										<div class="box-body table-responsive no-padding">
											<table class="table table-hover" id="table_<?php echo $key;?>">

												<?php if($key == 0): ?>

													<thead>
													<tr>
														<th></th>
														<th width="27.5%">Sub Activity Name</th>
														<th width="27.5%">Department</th>
														<th width="15%">Start Date</th>
														<th width="15%">Target End Date</th>
														<th width="10%">Period</th>
														<th width="5%"></th>
													</tr>
												</thead>

											<?php endif; ?>

											<tbody>

												<tr>
													<td></td>
													<!-- <td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-mainAct=<?php echo $value['TASKID']; ?> counter="1" data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td> -->
													<td width="27.5%"><b><?php echo $value['TASKTITLE']; ?></b></td>
													<td width="27.5%"><b>
														<?php
															$depts = array();

															foreach ($tasks as $row)
															{
																if($value['TASKTITLE'] == $row['TASKTITLE'])
																{
																	foreach ($departments as $row2)
																	{
																		if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																		{
																			$depts[] = $row2['DEPARTMENTNAME'];
																		}
																	}
																}
															}

														 echo implode(", ", $depts);
														?>
													</b></td>

													<?php
														$startdate = date_create($value['TASKSTARTDATE']);
														$enddate = date_create($value['TASKENDDATE']);
														$diff = date_diff($enddate, $startdate);
														$dDiff = intval($diff->format('%a'));
													?>

													<td width="15%"><b><?php echo date_format($startdate, "M d, Y"); ?></b></td>
													<td width="15%"><b><?php echo date_format($enddate, "M d, Y") ?></b></td>
													<td width="10%">
														<div class="form-group">
															<b>
																<?php
																	if (($dDiff + 1) <= 1)
																		echo ($dDiff + 1) . " day";
																	else
																		echo ($dDiff + 1) . " days";
																?>
															</b>
														</div>
													</td>
													<td width="5%"></td>
												</tr>

														<?php if (isset($templateMainActivity[$key])): ?>
															<?php foreach ($templateSubActivity as $sKey=> $tSub): ?>
																<?php if($tSub['tasks_TASKPARENT'] == $templateMainActivity[$key]['TASKID']): ?>
																	<tr>
																			<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-mainAct=<?php echo $value['TASKID']; ?> counter="1" data-sum = "<?php echo count($groupedTasks); ?>" data-dept=<?php echo json_encode($depts); ?>><i class="glyphicon glyphicon-plus-sign"></i></a></td>
																		<td><div class="form-group">

																			<input type="hidden" name="mainActivity_ID[]" value="<?php echo $value['TASKID']; ?>">

																			<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" value="<?php echo $tSub['TASKTITLE'];?>" required>

																			<input type="hidden" name="row[]" value="<?php echo $sKey; ?>">
																		</div></td>
																		<td>
																			<select id ="select<?php echo $key; ?>" class="form-control select2" multiple="multiple" name = "department[<?php echo $sKey; ?>][]" data-placeholder="Select Departments">
																				<?php
																					foreach ($tasks as $row)
																					{
																						if($value['TASKTITLE'] == $row['TASKTITLE'])
																						{
																							foreach ($departments as $row2Key => $row2)
																							{
																								if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																								{
																									echo "<option>" . $row2['DEPARTMENTNAME'] . "</option>";
																								}
																							}
																						}
																					}
																				?>
																			</select>
																		</td>
																		<td><div class="form-group">
																			<div class="input-group date">
																				<div class="input-group-addon">
																					<i class="fa fa-calendar"></i>
																				</div>
																				<input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $tSub['TASKID'];?>-0"
																				data-mainAct="<?php echo $tSub['TASKID'];?>" data-num="0"
																				data-mainStart<?php echo $tSub['TASKID']; ?> = "<?php echo $value['TASKSTARTDATE']; ?>"
																				data-mainEnd<?php echo $tSub['TASKID']; ?> = "<?php echo $value['TASKENDDATE']; ?>" required>
																			</div>
																			<!-- /.input group -->
																		</div></td>
																			<td><div class="form-group">
																				<div class="input-group date">
																					<div class="input-group-addon">
																						<i class="fa fa-calendar"></i>
																					</div>
																					<input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $tSub['TASKID'];?>-0"
																					data-mainAct="<?php echo $tSub['TASKID']; ?>" data-num="0" required>
																				</div>
																			</div></td>
																			<td>
																				<div class="form-group">
																					<input id = "projectPeriod_<?php echo $tSub['TASKID']; ?>-0" type="text" class="form-control period" value="" readonly>
																				</div>
																			</td>
																			<!-- <td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td> -->
																			<td></td>
																	</tr>
																	<?php endif; ?>
																<?php endforeach; ?>

															<?php else: ?>

																<tr>
																	<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-mainAct=<?php echo $value['TASKID']; ?> counter="1" data-sum = "<?php echo count($groupedTasks); ?>" data-dept=<?php echo json_encode($depts); ?>><i class="glyphicon glyphicon-plus-sign"></i></a></td>
									                <td><div class="form-group">

																		<input type="hidden" name="mainActivity_ID[]" value="<?php echo $value['TASKID']; ?>">

																		<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
																		<input type="hidden" name="row[]" value="<?php echo $key; ?>">
																	</div></td>
																	<td>
																		<select id ="select<?php echo $key; ?>" class="form-control select2" multiple="multiple" name = "department[<?php echo $key; ?>][]" data-placeholder="Select Departments">
																			<?php
																				foreach ($tasks as $row)
																				{
																					if($value['TASKTITLE'] == $row['TASKTITLE'])
																					{
																						foreach ($departments as $row2Key => $row2)
																						{
																							if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																							{
																								echo "<option>" . $row2['DEPARTMENTNAME'] . "</option>";
																							}
																						}
																					}
																				}
																			?>
										                </select>
																	</td>
																	<td><div class="form-group">
										                <div class="input-group date">
										                  <div class="input-group-addon">
										                    <i class="fa fa-calendar"></i>
										                  </div>
										                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $value['TASKID'];?>-0"
																			data-mainAct="<?php echo $value['TASKID'];?>" data-num="0"
																			data-mainStart<?php echo $value['TASKID']; ?> = "<?php echo $value['TASKSTARTDATE']; ?>"
																			data-mainEnd<?php echo $value['TASKID']; ?> = "<?php echo $value['TASKENDDATE']; ?>" required>
										                </div>
										                <!-- /.input group -->
										              </div></td>
																		<td><div class="form-group">
											                <div class="input-group date">
											                  <div class="input-group-addon">
											                    <i class="fa fa-calendar"></i>
											                  </div>
											                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $value['TASKID'];?>-0"
																				data-mainAct="<?php echo $value['TASKID']; ?>" data-num="0" required>
											                </div>
																		</div></td>
																		<td>
																			<div class="form-group">
																				<input id = "projectPeriod_<?php echo $value['TASKID']; ?>-0" type="text" class="form-control period" value="" readonly>
																			</div>
																		</td>
																		<!-- <td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td> -->
																		<td></td>
																</tr>

															<?php endif; ?>

											</tbody>
										</table>
										</div>
									<?php endforeach; ?>
									<!-- END OF TEMPLATES -->

								<?php else: ?>

									<?php foreach ($groupedTasks as $key=>$value): ?>

			            <div class="box-body table-responsive no-padding">
			              <table class="table table-hover" id="table_<?php echo $key;?>">

											<?php if($key == 0): ?>

												<thead>
				                <tr>
													<th></th>
													<th width="27.5%">Sub Activity Name</th>
													<th width="27.5%">Department</th>
													<th width="15%">Start Date</th>
													<th width="15%">Target End Date</th>
													<th width="10%">Period</th>
													<th width="5%"></th>
				                </tr>
											</thead>

										<?php endif; ?>

										<tbody>

											<tr>
												<td></td>
												<!-- <td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-mainAct=<?php echo $value['TASKID']; ?> counter="1" data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td> -->
												<td width="27.5%"><b><?php echo $value['TASKTITLE']; ?></b></td>
												<td width="27.5%"><b>
													<?php

														$depts = array();

														foreach ($tasks as $row)
														{
															if($value['TASKTITLE'] == $row['TASKTITLE'])
															{
																foreach ($departments as $row2)
																{
																	if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																	{
																		$depts[] = $row2['DEPARTMENTNAME'];
																	}
																}
															}
														}

													 echo implode(", ", $depts);
													?>
												</b></td>

												<?php
													$startdate = date_create($value['TASKSTARTDATE']);
													$enddate = date_create($value['TASKENDDATE']);
													$diff = date_diff($enddate, $startdate);
													$dDiff = intval($diff->format('%a'));
												?>

												<td width="15%"><b><?php echo date_format($startdate, "M d, Y"); ?></b></td>
												<td width="15%"><b><?php echo date_format($enddate, "M d, Y") ?></b></td>
												<td width="10%">
													<div class="form-group">
														<b>
															<?php
																if (($dDiff + 1) <= 1)
																	echo ($dDiff + 1) . " day";
																else
																	echo ($dDiff + 1) . " days";
															?>
														</b>
													</div>
												</td>
												<td width="5%"></td>
											</tr>
											<tr>
												<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-mainAct=<?php echo $value['TASKID']; ?> counter="1" data-sum = "<?php echo count($groupedTasks); ?>" data-dept=<?php echo json_encode($depts); ?>><i class="glyphicon glyphicon-plus-sign"></i></a></td>
				                <td><div class="form-group">

													<input type="hidden" name="mainActivity_ID[]" value="<?php echo $value['TASKID']; ?>">

													<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													<input type="hidden" name="row[]" value="<?php echo $key; ?>">
												</div></td>
												<td>
													<select id ="select<?php echo $key; ?>" class="form-control select2" multiple="multiple" name = "department[<?php echo $key; ?>][]" data-key = "<?php echo $key; ?>" data-placeholder="Select Departments">
														<?php
															foreach ($tasks as $row)
															{
																if($value['TASKTITLE'] == $row['TASKTITLE'])
																{
																	foreach ($departments as $row2Key => $row2)
																	{
																		if($row['USERID'] == $row2['users_DEPARTMENTHEAD'])
																		{
																			echo "<option>" . $row2['DEPARTMENTNAME'] . "</option>";
																		}
																	}
																}
															}
														?>
					                </select>
												</td>
												<td><div class="form-group">
					                <div class="input-group date">
					                  <div class="input-group-addon">
					                    <i class="fa fa-calendar"></i>
					                  </div>
					                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $value['TASKID'];?>-0"
														data-mainAct="<?php echo $value['TASKID'];?>" data-num="0"
														data-mainStart<?php echo $value['TASKID']; ?> = "<?php echo $value['TASKSTARTDATE']; ?>"
														data-mainEnd<?php echo $value['TASKID']; ?> = "<?php echo $value['TASKENDDATE']; ?>" required>
					                </div>
					                <!-- /.input group -->
					              </div></td>
													<td><div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $value['TASKID'];?>-0"
															data-mainAct="<?php echo $value['TASKID']; ?>" data-num="0" required>
						                </div>
													</div></td>
													<td>
														<div class="form-group">
															<input id = "projectPeriod_<?php echo $value['TASKID']; ?>-0" type="text" class="form-control period" value="" readonly>
														</div>
													</td>
													<!-- <td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td> -->
													<td></td>
											</tr>

										</tbody>
									</table>
			            </div>
									<?php endforeach; ?>
								<?php endif; ?>

		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-success"><i class="fa fa-backward"></i> Add Main Activities</button>
									<button type="submit" class="btn btn-success pull-right" id="scheduleTasks"><i class="fa fa-forward"></i> Add Tasks</button>
									<!-- <button id ="skipStep" type="button" class="btn btn-primary pull-right" style="margin-right: 5%"><i class="fa fa-fast-forward"></i> Generate Gantt Chart</button> -->
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

		 var i = <?php echo (count($groupedTasks)); ?>;
		 // var i = 2;
		 var x = 2;

		 $(document).on("click", "a.addButton", function() {

			 var currTable = $(this).attr('data-id');
			 var mainAct = $(this).attr('data-mainAct');
			 var counter = parseInt($(this).attr('counter'));
			 var key = $(this).attr('data-key');
			 var depts = $(this).attr('data-dept');
			 var d = JSON.parse(depts);

			 var department = "";

			 for (var k = 0; k < d.length; k++)
			 {
				 department = department + "<option>" + d[k] + "</option>";
			 }

				 $('#table_' + currTable).append("<tr id='table_" +
				 						currTable + "_Row_" + (i + 1) +
										"'><td></td><td><div class ='form-group'> <input type='hidden' name='mainActivity_ID[]' value='" +
										mainAct + "'> <input type='text' class='form-control' placeholder='Enter task title' name ='title[]' required>  <input type='hidden' name = 'row[]' value='" + i + "' >  </div></td>" +
										"<td><select id = 'select" + i + "' class='form-control select2' multiple='multiple' name = '' data-placeholder='Select Departments'> " +
										 department +
										"</select></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
										"<i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskStartDate' " +
										"name='taskStartDate[]' id='start_" + mainAct + "-" + counter +"' data-mainAct = '" + mainAct + "' data-num='" + counter +
										"' required></div></div></td> <td><div class='form-group'><div class='input-group date'>" +
										"<div class='input-group-addon'><i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskEndDate'" +
										"name='taskEndDate[]' id='end_" + mainAct + "-" + counter + "' data-mainAct = '" + mainAct + "' data-num='" + counter +
										"' required></div></div></td> <td> <div class = 'form-group'> <input id='projectPeriod_" + mainAct + "-" + counter + "' type ='text' class='form-control' value='' readonly> </div> </td> <td class='btn'><a class='btn delButton' data-id = " + currTable +
										" counter = " + x + " data-table = " + (i+1) + "><i class='glyphicon glyphicon-trash'></i></a></td></tr>");

					$("#end_" + mainAct + "-" + counter).prop('disabled', true);

				  $('.select2').select2();
					$("#select" + i).attr("name", "department[" + i + "][]");

				 counter++;
 				 $("a.addButton").attr('counter', counter);

				 i++;
				 x++;
			});

			$(document).on("click", "a.delButton", function() {
					if (x > 2)
					{
						var tableNum = $(this).attr('data-id');
						var rowNum = $(this).attr('data-table');

						// console.log(tableNum);
						// console.log(rowNum);
						// x = x -1;
						// var j = $(this).attr('data-id');
						// var k = $(this).attr('data-table');
						//
						$('#table_' + tableNum + '_Row_' + rowNum).remove();
					}
				});

				$(document).on("click", "#skipStep", function()
				{
							$("form").attr('action', 'projectGantt');
							$("form").submit();
					});

			 });

		  $(function ()
			{
				//Initialize Select2 Elements
		    $('.select2').select2();
				$(".taskEndDate").prop('disabled', true);

				//Date picker
				$('body').on('focus',".taskStartDate", function(){
					var mainAct = $(this).attr('data-mainAct');
  				var mainStart = $("#start_" + mainAct + "-0").attr('data-mainStart' + mainAct);
					var mainEnd = $("#start_" + mainAct + "-0").attr('data-mainEnd' + mainAct);
				    $(this).datepicker({
							format: 'yyyy-mm-dd',
		  	       autoclose: true,
							 startDate: mainStart,
							 endDate: mainEnd,
							 orientation: 'auto'
						});
				});

				$("body").on("change", ".taskStartDate", function(e) {
					var mainAct = $(this).attr('data-mainAct');
					var counter = $(this).attr('data-num');
					var newDate = $(this).val();

 				$("#end_" + mainAct + "-" + counter).prop('disabled', false);
				var diff = new Date($("#end_" + mainAct + "-" + counter).datepicker("getDate") - $("#start_" + mainAct + "-" + counter).datepicker("getDate"));
 				var period = (diff/1000/60/60/24)+1;
 				if ($("#start_" + mainAct + "-" + counter).val() != "" &&  $("#end_" + mainAct + "-" + counter).val() != "" && period >=1)
 				{
 					if(period > 1)
 						$("#projectPeriod_" + mainAct + "-" + counter).attr("value", period + " days");
 					else
 						$("#projectPeriod_" + mainAct + "-" + counter).attr("value", period + " day");
 				}
 				else
				{
					$("#projectPeriod_" + mainAct + "-" + counter).attr("value", "");
					$("#end_" + mainAct + "-" + counter).val("");
				}

				var mainEnd = $("#start_" + mainAct + "-0").attr('data-mainEnd' + mainAct);
				$("#end_" + mainAct + "-" + counter).data('datepicker').setStartDate(new Date($("#start_" + mainAct + "-" + counter).val()));
				$("#end_" + mainAct + "-" + counter).data('datepicker').setEndDate(new Date(mainEnd));
				$("#end_" + mainAct + "-" + counter).data('datepicker').setDate(new Date($("#start_" + mainAct + "-" + counter).val()));
				$("#end_" + mainAct + "-" + counter).val("");
				$("#projectPeriod_" + mainAct + "-" + counter).attr("value", "");

 			 });

				$('body').on('focus',".taskEndDate", function(){
					var mainAct = $(this).attr('data-mainAct');
					var counter = $(this).attr('data-num');

						$(this).datepicker({
							format: 'yyyy-mm-dd',
							 autoclose: true,
							 orientation: 'auto'
						});
				});

				$("body").on("change", ".taskEndDate", function() {
	 				var mainAct = $(this).attr('data-mainAct');
					var counter = $(this).attr('data-num');
	 				var diff = new Date($("#end_" + mainAct + "-" + counter).datepicker("getDate") - $("#start_" + mainAct + "-" + counter).datepicker("getDate"));
	 				var period = (diff/1000/60/60/24)+1;

					// console.log("#projectPeriod_" + mainAct + "-" + counter);

	 				if ($("#start_" + mainAct + "-" + counter).val() != "" &&  $("#end_" + mainAct + "-" + counter).val() != "" && period >=1)
	 				{
	 					if(period > 1)
	 						$("#projectPeriod_" + mainAct + "-" + counter).attr("value", period + " days");
	 					else
	 						$("#projectPeriod_" + mainAct + "-" + counter).attr("value", period + " day");
	 				}
	 				else
	 					$("#projectPeriod_" + mainAct + "-" + counter).attr("value", "");
 			 });

		 });
		</script>

	</body>
</html>
