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

					<a href="<?php echo base_url("index.php/controller/monitorProject"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to My Projects"><i class="fa fa-arrow-left"></i></a>
					<br><br>
					<h1>
						<?php echo $projectProfile['PROJECTTITLE'];?>
						<?php
						$projectStart = date_create($projectProfile['PROJECTSTARTDATE']);
						$projectEnd = date_create($projectProfile['PROJECTENDDATE']);
						?>
						<small>(<?php echo date_format($projectStart, "F d, Y");?> to <?php echo date_format($projectEnd, "F d, Y");?>)</small>
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
									<h3 class="box-title">Project Performance</h3>
								</div>
								<!-- /.box-header -->
								<div class="box-body">
	                <div style="display:inline-block; text-align:center; width:49%;">
	                  <div class="circlechart"
	                    data-percentage="<?php
												if($projectCompleteness['completeness'] == NULL){
													echo 0;
												} else {
													if($projectCompleteness['completeness'] == 100.00){
														echo 100;
													} elseif ($projectCompleteness['completeness'] == 0.00) {
														echo 0;
													} else {
														echo $projectCompleteness['completeness'];
													}
												}
												?>"> Completeness
	                  </div>
	                </div>
	                <div style="display:inline-block; text-align:center; width:49%;">
	                  <div class="circlechart"
	                   data-percentage="<?php
											 if($projectTimeliness['timeliness'] == NULL){
												 echo 0;
											 } else {
												 if($projectTimeliness['timeliness'] == 100.00){
													 echo 100;
												 } elseif ($projectTimeliness['timeliness'] == 0.00) {
													 echo 0;
												 } else {
													 echo $projectTimeliness['timeliness'];
												 }
											 }
											 ?>"> Timeliness
	                 </div>
	               </div>
	              </div>
							</div>
		        </div>
					</div>

					<form id="deptForm" action = 'monitorDepartmentDetails'  method="POST">
						<input type='hidden' name='project_ID' value= "<?php echo $projectProfile['PROJECTID'] ;?>">
					</form>

					<div class='row'>
						<?php foreach($departments as $department):?>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="box box-danger clickable dept" data-id="<?php echo $department['DEPARTMENTID'];?>">
									<div class="box-header with-border">
										<h3 class="box-title"><?php echo $department['DEPARTMENTNAME'];?> Performance</h3>
									</div>
									<!-- /.box-header -->
									<div class="box-body">
		                <div style="display:inline-block; text-align:center; width:49%;">
		                  <div class="circlechart"
		                    data-percentage="<?php
													if($department['completeness'] == NULL){
														echo 0;
													} else {
														if($department['completeness'] == 100.00){
															echo 100;
														} elseif ($department['completeness'] == 0.00) {
															echo 0;
														} else {
															echo $department['completeness'];
														}
													}
													?>"> Completeness
		                  </div>
		                </div>
		                <div style="display:inline-block; text-align:center; width:49%;">
		                  <div class="circlechart"
		                   data-percentage="<?php
	  										 if($department['timeliness'] == NULL){
	  											 echo 0;
	  										 } else {
	  											 if($department['timeliness'] == 100.00){
	  												 echo 100;
	  											 } elseif ($department['timeliness'] == 0.00) {
	  												 echo 0;
	  											 } else {
	  												 echo $department['timeliness'];
	  											 }
	  										 }
	  										 ?>"> Timeliness
		                 </div>
		               </div>
		              </div>
								</div>
			        </div>
						<?php endforeach;?>
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

			$(document).on("click", ".dept", function() {
	      var $id = $(this).attr('data-id');
	      $("#deptForm").attr("name", "formSubmit");
	      $("#deptForm").append("<input type='hidden' name='dept_ID' value= " + $id + ">");
	      $("#deptForm").submit();
	    });
		</script>
	</body>
</html>
