<html>
	<head>
		<title>Kernel - Add Sub Activities</title>
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

								<?php foreach ($groupedTasks as $key=>$value): ?>
		            <div class="box-body table-responsive no-padding">
		              <table class="table table-hover" id="table_<?php echo $key;?>">

										<?php if($key == 0): ?>

											<thead>
			                <tr>
												<td></td>
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
											<td class="btn" id="addRow"><a class="btn addButton" data-id="<?php echo $key; ?>" data-table="table_<?php echo $key;?>"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
											<td><?php echo $value['TASKTITLE']; ?></td>
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
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td></td>
			                <td><div class="form-group">
												<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
											</div></td>
											<td width="40%">
				                <select class="form-control select2" multiple="multiple" name = "department_0[]" data-placeholder="Select Departments">
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

										<tr id="table_<?php echo $key; ?>_Row_0">
											<!-- NEW LINE WILL BE INSERTED HERE -->
										</tr>


									</tbody>
								</table>
		            </div>
								<?php endforeach; ?>

		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-success">Previous: Add Main Activities</button>
									<button type="submit" class="btn btn-success pull-right" id="scheduleTasks">Next: Add Tasks</button>
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

		 var i = 0;
		 var x = 2;

		 $(document).on("click", "a.addButton", function() {

			 var currTable = $(this).attr('data-id');
			 var t = $(this).attr('data-table');

			 console.log(currTable);
			 console.log(t);
			 console.log(i);
			 //HELLO

			 $('#table_' + currTable + '_Row_' + i).html("<td></td><td><div class ='form-group'><input type='text' class='form-control' placeholder='Enter task title' name ='title[]' required></div></td>  <td><select class='form-control select2' multiple='multiple' name = 'department_" + i + "[]' data-placeholder='Select Departments'> <?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>';  }?>" + "</select></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'><i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskStartDate' name='taskStartDate[]' required></div></div></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'><i class='fa fa-calendar'></i></div><input type='text' class='form-control pull-right taskEndDate' name='taskEndDate[]' required></div></div></td> <td class='btn'><a class='btn delButton' data-id = " + i +" counter = " + x + "><i class='glyphicon glyphicon-trash'></i></a></td>");

	 			 $('.select2').select2();
				 // $('#row' + i).html("<td id='num" + i + "'>" + x + "</td><td><div class='form-group'><select class ='form-control' name = 'category" + i + "'><option disabled selected value> -- Select Category -- </option><option>Main Activity</option><option>Sub Activity</option><option>Task</option></select></div></td> <td><div class ='form-group'><input type='text' class='form-control' placeholder='Enter task title' name ='title" + i +"'</div></td>  <td><select class='form-control' id ='dept' name = 'department" + i +"'><option disabled selected value> -- Select Department -- </option>" + "<?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>'; } ?>" + "</select></td>  <td class='btn'><a class='btn addButton'><i class='glyphicon glyphicon-plus-sign'></i></a></td> <td class='btn'><a class='btn delButton' data-id = " + i +" counter = " + x + "><i class='glyphicon glyphicon-trash'></i></a></td>");

				 $('#table_' + currTable).append('<tr id="table_' + currTable + '_Row_' + (i + 1) + '"></tr>');
				 i++;
				 x++;
			});

			$(document).on("click", "a.delButton", function() {
					if (x > 2)
					{
						x = x -1;
						var j = $(this).attr('data-id');

						$('#table_Row_' + j).remove();
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

		 // var el = document.getElementById('table');
		 // var dragger = tableDragger(el, {
		 //   mode: 'row',
		 //   dragHandler: '.handle',
		 //   onlyBody: true,
		 //   animation: 300
		 // });
		 // dragger.on('drop',function(from, to){
		 //   console.log(from);
		 //   console.log(to);
		 // });
		</script>

	</body>
</html>
