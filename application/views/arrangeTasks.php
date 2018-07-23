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
					<div class="container-fluid">
					  <ul class="list-unstyled multi-steps">
					    <li>Project Details</li>
					    <li>Add Main Activities</li>
					    <li class="is-active">Add Sub Activities</li>
					    <li>Add Tasks</li>
					    <li>Add Dependencies</li>
					  </ul>
					</div>
					<br>
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box box-danger">
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

									<input type="hidden" name="templates" value="0" id="projectDate" data-startDate="<?php echo $project['PROJECTSTARTDATE'];?>" data-endDate="<?php echo $project['PROJECTENDDATE'];?>">

								<?php if (isset($_SESSION['templates'])): ?>
								<?php else: ?>
									<?php foreach ($groupedTasks as $key=>$value): ?>

			            <div class="box-body table-responsive no-padding">
			              <table class="table table-hover" id="table_<?php echo $key;?>">

											<?php if($key == 0): ?>

												<thead>
				                <tr>
													<th></th>
													<th width="27.5%">Task Name</th>
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
												<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-mainAct=<?php echo $value['TASKID']; ?> counter="1" data-sum = "<?php echo count($groupedTasks); ?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
												<td width="27.5%"><b><?php echo $value['TASKTITLE']; ?></b></td>
												<td width="27.5%"><b>
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
																		// $depts[] = $row2['DEPARTMENTNAME'];
																		echo $row2['DEPARTMENTNAME'] . ", ";
																	}
																}

																//TODO: Fix implode shit
																// foreach ($depts as $x)
																// {
																// 	echo $x . ", ";
																// }
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
												<td></td>
				                <td><div class="form-group">

													<input type="hidden" name="mainActivity_ID[]" value="<?php echo $value['TASKID']; ?>">

													<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													<input type="hidden" name="row[]" value="<?php echo $key; ?>">
												</div></td>
												<td>
													<select id ="select<?php echo $key; ?>" class="form-control select2" multiple="multiple" name = "department[<?php echo $key; ?>][]" data-placeholder="Select Departments">
														<?php foreach ($departments as $row): ?>

															<option>
																<?php echo $row['DEPARTMENTNAME']; ?>
															</option>

														<?php endforeach; ?>
					                </select>
												</td>
												<td><div class="form-group">
					                <div class="input-group date">
					                  <div class="input-group-addon">
					                    <i class="fa fa-calendar"></i>
					                  </div>
					                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start_<?php echo $key; ?>-0" data-mainAct="<?php echo $key; ?>" data-num="0" required>
					                </div>
					                <!-- /.input group -->
					              </div></td>
													<td><div class="form-group">
						                <div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end_<?php echo $key; ?>-0" data-mainAct="<?php echo $key; ?>" data-num="0" required>
						                </div>
													</div></td>
													<td>
														<div class="form-group">
															<input id = "projectPeriod0" type="text" class="form-control" value="" readonly>
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
									<button id ="skipStep" type="button" class="btn btn-primary pull-right" style="margin-right: 5%"><i class="fa fa-fast-forward"></i> Skip This Step</button>
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

			var i = <?php echo (count($groupedTasks)); ?>;
		 // var i = 2;
		 var x = 2;

		 $(document).on("click", "a.addButton", function() {

			 var currTable = $(this).attr('data-id');
			 var mainAct = $(this).attr('data-mainAct');
			 var counter = parseInt($(this).attr('data-sum'));

				 $('#table_' + currTable).append("<tr id='table_" +
				 						currTable + "_Row_" + (i + 1) +
										"'><td></td><td><div class ='form-group'> <input type='hidden' name='mainActivity_ID[]' value='" +
										mainAct + "'> <input type='text' class='form-control' placeholder='Enter task title' name ='title[]' required>  <input type='hidden' name = 'row[]' value='" + i + "' >  </div></td>" +
										"<td><select id = 'select" + i + "' class='form-control select2' multiple='multiple' name = '' data-placeholder='Select Departments'> " +
										"<?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>';  }?>" +
										"</select></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
										"<i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskStartDate' " +
										"name='taskStartDate[]' id='start_" + mainAct + "-" + counter +"' data-mainAct = '" + mainAct + "' data-num='" + counter +
										"' required></div></div></td> <td><div class='form-group'><div class='input-group date'>" +
										"<div class='input-group-addon'><i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskEndDate'" +
										"name='taskEndDate[]' id='end_" + mainAct + "-" + counter + "' data-mainAct = '" + mainAct + "' data-num='" + counter +
										"' required></div></div></td> <td> <div class = 'form-group'> <input id='projectPeriod" + i + "' type ='text' class='form-control' value='' readonly> </div> </td> <td class='btn'><a class='btn delButton' data-id = " + currTable +
										" counter = " + x + " data-table = " + (i+1) + "><i class='glyphicon glyphicon-trash'></i></a></td></tr>");

					$("#end_" + mainAct + "-" + counter).prop('disabled', true);

				 var newCount = counter + 1;
				 // tot++;
				 $("a.addButton").attr('counter', newCount);

				  $('.select2').select2();
					$("#select" + i).attr("name", "department[" + i + "][]");

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
							console.log("hello");
					});

			 });

		  $(function ()
			{
				//Initialize Select2 Elements
		    $('.select2').select2();
				$(".taskEndDate").prop('disabled', true);

				//Date picker
				$('body').on('focus',".taskStartDate", function(){
				    $(this).datepicker({
							format: 'yyyy-mm-dd',
		  	       autoclose: true,
							 startDate: $("#projectDate").attr('data-startDate'),
							 endDate: $("#projectDate").attr('data-endDate'),
							 orientation: 'auto'
						});
				});

				$("body").on("change", ".taskStartDate", function(e) {
					var mainAct = $(this).attr('data-mainAct');
					var counter = $(this).attr('data-num');
					var newDate = $(this).val();

 				$("#end_" + mainAct + "-" + counter).prop('disabled', false);
				if(new Date($("#end_" + mainAct + "-" + counter).val()) < newDate) //Removes Target Date Input if new Start Date comes after it
					$("#end_" + mainAct + "-" + counter).val("");
 			 });

				$('body').on('focus',".taskEndDate", function(){
					var mainAct = $(this).attr('data-mainAct');
					var counter = $(this).attr('data-num');
						$(this).datepicker({
							format: 'yyyy-mm-dd',
							 autoclose: true,
							 startDate: $("#start_" + mainAct + "-" + counter).val(),
							 endDate: $("#projectDate").attr('data-endDate'),
							 orientation: 'auto'
						});
				});
		 });
		</script>

	</body>
</html>
