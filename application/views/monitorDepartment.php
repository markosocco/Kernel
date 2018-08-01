<html>
	<head>
		<title>Kernel - Monitor Members</title>

		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorMembersStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Project Name
						<small>(date to date)</small>
					</h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/monitorProject"); ?>"><i class="fa fa-dashboard"></i> My Team</a></li>
					</ol>

				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
					<div class="row">
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="box box-danger">
								<div class="box-header with-border">
									<h3 class="box-title">Overall Performance</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
	                <div style="display:inline-block; text-align:center; width:49%;">
	                  <div class="circlechart"
	                    data-percentage=""> Completeness
	                  </div>
	                </div>
	                <div style="display:inline-block; text-align:center; width:49%;">
	                  <div class="circlechart"
	                   data-percentage=""> Timeliness
	                 </div>
	               </div>
	              </div>
							</div>
		        </div>
						<!-- START LOOP HERE -->
						<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="box box-danger clickable">
								<div class="box-header with-border">
									<h3 class="box-title">deptName Performance</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
	                <div style="display:inline-block; text-align:center; width:49%;">
	                  <div class="circlechart"
	                    data-percentage=""> Completeness
	                  </div>
	                </div>
	                <div style="display:inline-block; text-align:center; width:49%;">
	                  <div class="circlechart"
	                   data-percentage=""> Timeliness
	                 </div>
	               </div>
	              </div>
							</div>
		        </div>
						<!-- END LOOP HERE -->
					</div>
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorProject").addClass("active");
      $('.circlechart').circlechart(); // Initialization
		</script>
	</body>
</html>
