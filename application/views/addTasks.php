<html>
	<head>
		<title>Kernel - Add Tasks</title>

		<meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <!-- Tell the browser to be responsive to screen width -->
	  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
	  <!-- Font Awesome -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/font-awesome/css/font-awesome.min.css">
	  <!-- Ionicons -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/Ionicons/css/ionicons.min.css">
	  <!-- Theme style -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/AdminLTE.css">
		<!-- Select2 -->
	  <link rel="stylesheet" href="../../assets/bower_components/select2/dist/css/select2.min.css">
		<!-- bootstrap datepicker -->
		<link rel="stylesheet" href="../../assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
	  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
	        page. However, you can choose any other skin. Make sure you
	        apply the skin class to the body tag so the changes take effect. -->
	  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/skins/skin-red.css">

	  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	  <!--[if lt IE 9]>
	  <script src="<?php echo base_url()."assets/"; ?>https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	  <script src="<?php echo base_url()."assets/"; ?>https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	  <![endif]-->

	  <!-- Google Font -->
	  <link rel="stylesheet"
	        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

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
		              <h3 class="box-title">Step 2: Enter all tasks for this project</h3>
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
		              <table class="table table-hover" id="table">
		                <tr>
		                  <th>No.</th>
		                  <th>Category</th>
											<th>Title</th>
											<th>Department</th>
											<th></th>
											<th></th>
		                </tr>
										<form id='addTasks' name = 'addTasks' action='' method="POST">
		                <tr id="row0">
											<td>1</td>
		                  <td><div class="form-group">
			                  <select class="form-control" name = "categories[]">
													<option disabled selected value> -- Select Category -- </option>
													<option>Main Activity</option>
				                  <option>Sub Activity</option>
				                  <option>Task</option>
			                  </select>
			                </div></td>
		                  <td><div class="form-group">
			                  <input type="text" class="form-control" placeholder="Enter task title" name = "title[]">
			                </div></td>
											<td><select class="form-control" name = "depts[]">
												<option disabled selected value> -- Select Department -- </option>

												<?php $counter = 0; ?>

												<?php foreach ($departments as $row): ?>

													<option>
														<?php echo $row['DEPARTMENTNAME']; ?>
													</option>

											<?php endforeach; ?>
											</select></td>
										<td class="btn" id="addRow"><a class="btn addButton"><i class="glyphicon glyphicon-plus-sign"></i></a></td>
										<td class='btn'><a class='btn delButton' data-id = " + i +"><i class='glyphicon glyphicon-trash'></i></a></td>
										<!-- <td class="btn"><a class="btn delButton"></a></td> -->
		                </tr>
										<tr id="row1"></tr>
		              </table>
<<<<<<< HEAD
								</form>
								</div>
=======
>>>>>>> d190edf97a9f66864dc25ee77e1d8496488a2356
		            <!-- /.box-body -->
		          </div>
							<div class="box-footer">
								<button type="button" class="btn btn-warning">Return to step 1</button>
<<<<<<< HEAD
								<button type="button" class="btn btn-primary">Save</button>
								<button type="button" class="btn btn-success" id="step3" data-id= <?php echo $project['PROJECTID']; ?>>Proceed to step 3</button>
=======
								<button type="button" class="btn btn-success pull-right">Proceed to step 3</button>
								<button type="button" class="btn btn-primary pull-right">Save</button>
>>>>>>> d190edf97a9f66864dc25ee77e1d8496488a2356
							</div>
		          <!-- /.box -->
		        </div>

				</div>

		      </div>
		    </section>
		    <!-- /.content -->
		  </div>

		</div>
		<!-- ./wrapper -->

		<!-- REQUIRED JS SCRIPTS -->

		<!-- jQuery 3 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- AdminLTE App -->
		<script src="<?php echo base_url()."assets/"; ?>dist/js/adminlte.min.js"></script>
		<!-- date-range-picker -->
		<script src="../../assets/bower_components/moment/min/moment.min.js"></script>
		<script src="../../assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
		<!-- bootstrap datepicker -->
		<script src="../../assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
		<!-- Select2 -->
		<script src="../../assets/bower_components/select2/dist/js/select2.full.min.js"></script>
		<script src="../../tabledragger/dist/table-dragger.min.js"></script>
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
		 });

		 $(document).ready(function() {
			 var i = 1;
			 var x = 2;

			 $(document).on("click", "a.addButton", function() {

					 $('#row' + i).html("<td id='num' " + i + ">" + x + "</td><td><div class='form-group'><select class ='form-control' name = 'categories[]'><option disabled selected value> -- Select Category -- </option><option>Main Activity</option><option>Sub Activity</option><option>Task</option></select></div></td> <td><div class ='form-group'><input type='text' class='form-control' placeholder='Enter task title' name ='title[]'</div></td>  <td><select class='form-control' id ='dept' name = 'depts[]'><option disabled selected value> -- Select Department -- </option>" + "<?php foreach ($departments as $row) { echo '<option>' . $row['DEPARTMENTNAME'] . '</option>'; } ?>" + "</select></td>  <td class='btn'><a class='btn addButton'><i class='glyphicon glyphicon-plus-sign'></i></a></td> <td class='btn'><a class='btn delButton' data-id = " + i +" counter = " + x + "><i class='glyphicon glyphicon-trash'></i></a></td>");

					 $('#table').append('<tr id="row' + (i + 1) + '"></tr>');
					 i++;
					 x++;
				});

				$(document).on("click", "a.delButton", function() {

					var i = $(this).attr('data-id');
					//var before = i - 1;
					// var after = i + 1;
					// var counter = $(this).attr('counter');
					// console.log(counter);

					$('#row' + i).remove();


					// for (var x = 0; x <= counter; x++)
					// {
					// 	//$('.num' + i ).text(i);
					// 	//console.log(i);
					// 	//console.log(i);
					// 	i--;
					// }

 				});

				$("#step3").click(function()
        {
					var $id = $(this).attr('data-id');
					$("#addTasks").attr("action", "<?php echo base_url('index.php/controller/addTasksToProject/?id=');?> " + $id);
					$("#addTasks").submit();
        	});
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
		</script>
	</body>
</html>
