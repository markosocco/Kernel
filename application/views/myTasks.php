<html>
	<head>
		<title>Kernel - My Tasks</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTasksStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						My Tasks
						<small>What do I need to do</small>
					</h1>
					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myTasks"); ?>"><i class="fa fa-dashboard"></i> My Tasks</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<!-- <div id="filterButtons">
						<h5>Arrange by</h5>
					</div> -->

					<div class="row">
		        <div class="col-xs-12">
		          <div class="box">
		            <div class="box-header">
		              <h3 class="box-title">Arrange by</h3>
									<button type="button" class="btn btn-info btn-xs" style="margin-left:">Project</button>
									<h3 class="box-title">or</h3>
									<button type="button" class="btn btn-info btn-xs" style="margin-left:">Priority</button>
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
		              <table class="table table-hover">
		                <tr>
		                  <th>Task</th>
		                  <th>Project</th>
		                  <th>Start Date</th>
		                  <th>Target End Date</th>
		                  <th align="center"></th>
											<th align="center"></th>
		                </tr>
										<tr>
											<td>Find something something from somewhere</td>
											<td>Store Opening - SM Southmall</td>
											<td>06/32/2020</td>
											<td>06/33/2021</td>
											<td align="center"><button type="button" class="btn btn-primary" style="margin-left: 1%">Delegate</button></td>
											<td align="center"><button type="button" class="btn btn-success" style="margin-left: 1%">Done</button></td>
										</tr>

		              </table>
		            </div>
		            <!-- /.box-body -->
		          </div>
		          <!-- /.box -->
		        </div>
				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>
			$("#myTasks").addClass("active");
		</script>
	</body>
</html>
