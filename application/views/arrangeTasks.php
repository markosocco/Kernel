<html>
	<head>
		<title>Kernel - Arrange tasks</title>

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
		              <h3 class="box-title">Arrange Task Sequence</h3>
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
										<thead>
		                <tr>
		                  <th></th>
		                  <th>Category</th>
											<th>Title</th>
											<th>Department</th>
											<th></th>
											<th></th>
		                </tr>
									</thead>
									<tbody>
										<?php foreach ($tasks as $row): ?>
											<tr>
												<td class="handle"><i class="fa fa-arrows"></i></td>
			                  <td><?php echo $row['CATEGORY']; ?></td>
			                  <td><?php echo $row['TASKTITLE']; ?></td>

												<?php
													foreach ($user as $x)
													{
														if ($row['users_USERID'] == $x['USERID'])
														{
															$deptID = $x['departments_DEPARTMENTID'];
														}
													}

													foreach ($department as $y)
													{
														if ($deptID == $y['DEPARTMENTID'])
														{
															$dept = $y['DEPARTMENTNAME'];
														}
													}
												?>

												<td><?php echo $dept; ?></td>
			                </tr>
										<? endforeach; ?>
									</tbody>
		              </table>
		            </div>
		            <!-- /.box-body -->
								<div class="box-footer">
									<button type="button" class="btn btn-warning">Previous: Add tasks</button>
									<button type="button" class="btn btn-success pull-right" id="scheduleTask">Next: Schedule tasks</button>
									<button type="button" class="btn btn-primary pull-right">Save</button>
								</div>
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
		$("#myProjects").addClass("active");

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

		 var el = document.getElementById('table');
		 var dragger = tableDragger(el, {
		   mode: 'row',
		   dragHandler: '.handle',
		   onlyBody: true,
		   animation: 300
		 });
		 dragger.on('drop',function(from, to){
		   console.log(from);
		   console.log(to);
		 });
		</script>

	</body>
</html>
