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

								<?php $c = 0; ?>

								<?php foreach ($mainActivity as $key=>$value): ?>

									<!-- MAIN ACT TABLE START -->

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
												<td><b><?php echo $value['TASKTITLE']; ?></b></td>
												<td>
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
												</td>
												<td><?php echo $value['TASKSTARTDATE']; ?></td>
												<td><?php echo $value['TASKENDDATE']; ?></td>
												<td></td>
											</tr>
										</tbody>
										</table>

										<!-- MAIN ACTIVITY TABLE END  -->

											<?php foreach ($subActivity as $sKey => $sValue): ?>

												<!-- SUB ACT TABLE START -->

													<?php if ($sValue['tasks_TASKPARENT'] == $value['TASKID']): ?>
														<table id = "ma<?php echo $key; ?>_s<?php echo $sKey; ?>">
														<tbody>
														<tr>
															<td class="btn" id="addRow"><a class="btn addButton" data-subTot="<?php echo count($subActivity); ?>" data-mTable = "<?php echo $key; ?>" data-sTable="<?php echo $sKey; ?>" data-subAct="<?php echo $sValue['TASKID']; ?>" counter="1" data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
															<td><i><?php echo $sValue['TASKTITLE']; ?></i></td>
															<td>
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
															</td>
															<td><?php echo $sValue['TASKSTARTDATE']; ?></td>
															<td><?php echo $sValue['TASKENDDATE']; ?></td>
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
															<!-- CHANGE TO NORMAL SELECT. 1:1 -->
															<td width="40%">
																<select id ="select<?php echo $c; ?>" class="form-control select2" multiple="multiple" name = "department[<?php echo $c; ?>][]" data-placeholder="Select Departments">
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
													</tbody>
												</table>

												<?php $c++; ?>

												<?php endif; ?>
											<?php endforeach; ?>
		            </div>

								<!-- SUB ACT TABLE END -->
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

		$(document).ready(function() {

		 var i = <?php echo (count($subActivity)); ?>;
		 var x = 2;

		 $(document).on("click", "a.addButton", function() {

			 var mTable = $(this).attr('data-mTable');
			 var sTable = $(this).attr('data-sTable');
			 var tot = $(this).attr('data-subTot');
			 var subAct = $(this).attr('data-subAct');
			 var counter = parseInt($(this).attr('data-sum'));

				 $('#ma' + mTable + '_s' + sTable).append("<tr id='ma" +
				 						mTable + "_s" + (i) +
										"'><td></td><td><div class ='form-group'> <input type='hidden' name='subActivity_ID[]' value='" +
										subAct + "'> <input type='text' class='form-control' placeholder='Enter task title' name ='title[]' required>  <input type='hidden' name = 'row[]' value='" + i + "' >  </div></td>" +
										"<td><select id = 'select" + i + "' class='form-control select2' multiple='multiple' name = '' data-placeholder='Select Departments'> " +
										"<?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>';  }?>" +
										"</select></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
										"<i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskStartDate' " +
										"name='taskStartDate[]' id='start_" + subAct + "-" + counter +"' data-subAct = '" + subAct + "' data-num='" + counter +
										"' required></div></div></td> <td><div class='form-group'><div class='input-group date'>" +
										"<div class='input-group-addon'><i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskEndDate'" +
										"name='taskEndDate[]' id='end_" + subAct + "-" + counter + "' data-mainAct = '" + subAct + "' data-num='" + counter +
										"' required></div></div></td> <td class='btn'><a class='btn delButton' data-mTable = " + mTable +
										" counter = " + x + " data-sTable = " + (i) + "><i class='glyphicon glyphicon-trash'></i></a></td></tr>");

					// $("#end_" + subAct + "-" + counter).prop('disabled', true);

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
