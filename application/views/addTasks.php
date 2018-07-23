<html>
	<head>
		<title>Kernel - Add Main Activities</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/addMainsStyle.css")?>">
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
		        <li><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
		        <li>New Project</li>
						<!-- <li class="active"><?php echo $project['PROJECTTITLE'] . " Tasks" ?></li> -->
						<li class="active">Main Activity</li>
		      </ol>
		    </section>

		    <!-- Main content -->
		    <section class="content container-fluid">
					<div id="progressContainer">
						<ul class="progressbar">
		          <li>Project Details</li>
		          <li class="active">Add Main Activities</li>
		          <li>Add Sub Activities</li>
		          <li>Add Tasks</li>
							<li>Add Dependecies</li>
		  			</ul>
					</div>
					<br><br>
					<div class="row">
		        <div class="col-xs-12">
		          <div class="box">
		            <div class="box-header">
		              <h3 class="box-title">Enter main activities for this project</h3>
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
								<form id='addTasks' name = 'addTasks' action = 'addTasksToProject' method="POST">

									<?php if (isset($_SESSION['templates'])): ?>
									<input type="hidden" name="templates" value="0">
								<?php endif; ?>

		            <div class="box-body table-responsive no-padding">
		              <table class="table table-hover" id="table">
		                <tr>
											<th width="30%">Main Activity Name</th>
											<th width="30%">Department</th>
											<th width="15%">Start Date</th>
											<th width="15%">Target End Date</th>
											<th width="10%">Period</th>
											<th></th>
		                </tr>

		                <tr id="row0">
		                  <td>
												<div class="form-group">
			                  	<input type="text" class="form-control" placeholder="Enter task title" name = "title[]" required>
													<input type="hidden" name="row[]" value="0">
			                	</div>
											</td>
											<td>
				                <select id ="select0" class="form-control select2" multiple="multiple" name = "department[0][]" data-placeholder="Select Departments">

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
					                  <input type="text" class="form-control pull-right taskStartDate" name="taskStartDate[]" id="start-0" data-mainAct="0" data-start="<?php echo $project['PROJECTSTARTDATE'];?>" data-end="<?php echo $project['PROJECTENDDATE'];?>" required>
					                </div>
					                <!-- /.input group -->
					              </div>
											</td>
											<td>
												<div class="form-group">
					                <div class="input-group date">
					                  <div class="input-group-addon">
					                    <i class="fa fa-calendar"></i>
					                  </div>
					                  <input type="text" class="form-control pull-right taskEndDate" name ="taskEndDate[]" id="end-0" data-mainAct="0" required>
					                </div>
												</div>
											</td>
											<td>
												<div class="form-group">
			                  	<input id = "projectPeriod0" type="text" class="form-control" value="" readonly>
			                	</div>
											</td>
											<td class='btn'>
												<a id = "del0" class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a>
											</td>
										<!-- <td class="btn"><a class="btn delButton"></a></td> -->
		                </tr>

										<tr id="row1">
											<!-- NEW LINE WILL BE INSERTED HERE  -->
										</tr>

										<tfoot>
											<tr align="center">
												<td class="btn" id="addRow" colSpan="6"><a class="btn addButton"  data-counter = "1"><i class="glyphicon glyphicon-plus-sign"></i> Add more main activities</a></td>
											</tr>
										</tfoot>

		              </table>

								</div>
		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-success"><i class="fa fa-backward"></i> Project details</button>
									<button type="submit" class="btn btn-success pull-right" id="arrangeTask" data-id= <?php echo $project['PROJECTID']; ?>><i class="fa fa-forward"></i> Add Sub Activities</button>
									<!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 25%"><i class="fa fa-file-excel-o"></i> Import Spreadsheet File</button> -->
									<!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5%"><i class="fa fa-window-maximize"></i> Use a Template</button> -->
									<!-- <button type="button" class="btn btn-primary pull-right" style="margin-right: 5%">Save</button> -->
								</div>
								</form>
		          </div>
		          <!-- /.box -->
		        </div>

				</div>

		      </div>
		    </section>
		    <!-- /.content -->
				<?php require("footer.php"); ?>

		  </div>

		</div>
		<!-- ./wrapper -->

		<!-- REQUIRED JS SCRIPTS -->

		<script>

			$.fn.datepicker.defaults.format = 'yyyy-mm-dd';

			$("#myProjects").addClass("active");
			$(".taskEndDate").prop('disabled', true);

		  $(function ()
			{
				//Initialize Select2 Elements
		    $('.select2').select2()

				//Date picker

			 $('body').on('focus',".taskStartDate", function(){
					 $(this).datepicker({
						 autoclose: true,
						 orientation: 'auto',
						 format: 'yyyy-mm-dd',
						 startDate: $("#start-0").attr('data-start'), // start date of project
						 endDate: $("#start-0").attr('data-end') // end date of project
					 });
			 });

			 $("body").on("change", ".taskStartDate", function(e) {
				 var mainAct = $(this).attr('data-mainAct');

			 $("#end-" + mainAct).prop('disabled', false);
			 // $('#end-' + mainAct).data('datepicker').setStartDate(new Date($("#start-" + mainAct).val()));
			 $("#end-" + mainAct).startDate = "2018-07-30";
			 if(new Date($("#end-" + mainAct).val()) < $(this).val()) //Removes Target Date Input if new Start Date comes after it
				 $("#end-" + mainAct).val("");

				var diff = new Date($("#end-"+mainAct).datepicker("getDate") - $("#start-" + mainAct).datepicker("getDate"));
 				var period = (diff/1000/60/60/24)+1;
				if ( $("#start-" + mainAct).val() != "" &&  $("#end-" + mainAct).val() != "" && period >=1)
 				{
					if(period > 1)
						$("#projectPeriod" + mainAct).attr("value", period + " days");
					else
						$("#projectPeriod" + mainAct).attr("value", period + " day");
 				}
 				else
 					$("#projectPeriod").attr("value", "");
			});

			 $('body').on('focus',".taskEndDate", function(){
				 var mainAct = $(this).attr('data-mainAct');
				 var counter = $(this).attr('data-num');

					 $(this).datepicker({
		 	       autoclose: true,
						 orientation: 'auto',
						 format: 'yyyy-mm-dd',
						 startDate: $("#start-"+mainAct).val(),
						 endDate: $("#start-0").attr('data-end') // end date of project
					 });
			 });

			 $("body").on("change", ".taskEndDate", function() {
				var mainAct = $(this).attr('data-mainAct');
				var diff = new Date($("#end-"+mainAct).datepicker("getDate") - $("#start-" + mainAct).datepicker("getDate"));
				var period = (diff/1000/60/60/24)+1;
				if ( $("#start-" + mainAct).val() != "" &&  $("#end-" + mainAct).val() != "" && period >=1)
				{
					if(period > 1)
						$("#projectPeriod" + mainAct).attr("value", period + " days");
					else
						$("#projectPeriod" + mainAct).attr("value", period + " day");
				}
				else
					$("#projectPeriod" + mainAct).attr("value", "");
			 });
		 });

		 $(document).ready(function() {

			 var i = 1;
			 var x = 2;

			 $(document).on("click", "a.addButton", function() {

				 // var str = new String("department[\'dept\'][]");

				 // console.log("hello "+ str);
				 var counter = parseInt($(this).attr('data-counter'));

				 $('#row' + i).html("<td><div class ='form-group'><input type='text' class='form-control' placeholder='Enter task title' name ='title[]' required>  <input type='hidden' name = 'row[]' value='" + i + "' ></div></td> " +
				 " <td> <select id ='select" + i + "' class='form-control select2' multiple='multiple' name = '' data-placeholder='Select Departments'> <?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>';  }?>" +
				 "</select></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
				 "<i class='fa fa-calendar'></i></div> <input type='text' class='form-control pull-right taskStartDate' name='taskStartDate[]' id='start-" + i + "' data-mainAct='"+ i +"' required></div></div></td> <td><div class='form-group'><div class='input-group date'><div class='input-group-addon'>" +
				 "<i class='fa fa-calendar'></i></div> <input type='text' class='form-control pull-right taskEndDate' name='taskEndDate[]' id='end-" + i + "' data-mainAct='" + i + "' required></div></div></td> <td> <div class = 'form-group'> <input id='projectPeriod" + i + "' type ='text' class='form-control' value='' readonly> </div> </td> <td class='btn'><a id='del" + counter + "' class='btn delButton' data-id = " + i +" counter = " + x + "><i class='glyphicon glyphicon-trash'></i></a></td>");

				 // counter++;

				 	$('.select2').select2();
					$("#end-" + i).prop('disabled', true);
					$("#select" + i).attr("name", "department[" + i + "][]");

					counter++;
					$("a.addButton").attr('data-counter', counter);
					console.log(counter);
					 // $('#row' + i).html("<td id='num" + i + "'>" + x + "</td><td><div class='form-group'><select class ='form-control' name = 'category" + i + "'><option disabled selected value> -- Select Category -- </option><option>Main Activity</option><option>Sub Activity</option><option>Task</option></select></div></td> <td><div class ='form-group'><input type='text' class='form-control' placeholder='Enter task title' name ='title" + i +"'</div></td>  <td><select class='form-control' id ='dept' name = 'department" + i +"'><option disabled selected value> -- Select Department -- </option>" + "<?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>'; } ?>" + "</select></td>  <td class='btn'><a class='btn addButton'><i class='glyphicon glyphicon-plus-sign'></i></a></td> <td class='btn'><a class='btn delButton' data-id = " + i +" counter = " + x + "><i class='glyphicon glyphicon-trash'></i></a></td>");

					 $('#table').append('<tr id="row' + (i + 1) + '"></tr>');
					 i++;
					 x++;
				});

				$(document).on("click", "a.delButton", function() {
						if (x > 2)
						{
							var j = $(this).attr('data-id');
							console.log("this is j = " + j);

							var counter = $("a.addButton").attr('data-counter');

							$('#row' + j).remove();

							counter--;
							$("a.addButton").attr('data-counter', counter);

							// for (var a = j; a <= counter; a++)
							// {
							// 	$("#row" + (j + 1)).attr('data-id', (a-1));
							// 	$("#select" + a).attr('name', 'department_' + (counter - 1) + '[]');
							// }
							//
							// console.log(x);
							//
							// console.log("after removing: " + x);
						}
					});

					// $(document).on("click", "#arrangeTask", function() {
					//
	 				//  var counter = $("a.addButton").attr('data-counter');
					//
					//  for (var a = 1; a <= counter; a++)
					//  {
					// 	 $("#select" + a).attr('name', 'department_' + a);
					//  }
					//
					//  // console.log(counter);
	 				// });
        });

				// var el = document.getElementById('table');
				// var dragger = tableDragger(el, {
				//  mode: 'row',
				//  dragHandler: '.handle',
				//  onlyBody: true,
				//  animation: 300
				// });

				// $(document).on("drop", "a.handle", function() {
				//
 				// });

				// dragger.on('drop',function(from, to){
				//  console.log(from);
				//  console.log(to);
				// });
				// THIS SHIT BETTER WORK
		</script>
	</body>
</html>
