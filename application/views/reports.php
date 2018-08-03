<html>
	<head>
		<title>Kernel - Reports</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/reportsStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Reports
					<small>What do I show the boss?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box box-danger">
					<div class="box-header">
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="reportList" class="table table-bordered table-hover">
							<tbody>
								<tr>
									<td>Projects Per Department</td>
									<td align="center"><a href="<?php echo base_url("index.php/controller/reportsProjectPerDept"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a></td>
								</tr>
								<tr>
									<td>Ongoing Projects</td>
									<td align="center"><a href="<?php echo base_url("index.php/controller/reportsOngoingProjects"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a></td>
								</tr>
								<tr>
									<td>Planned Projects</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i></button></td>
								</tr>
								<tr>
									<td>Parked Projects</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i></button></td>
								</tr>
								<tr>
									<td>Project Performance</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i></button></td>
								</tr>
								<tr>
									<td>Employee Performance per Project</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i></button></td>
								</tr>
								<tr>
									<td>Project Performance of a User</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i></button></td>
								</tr>
								<tr>
									<td>Departmetal Performance per Department</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i></button></td>
								</tr>
								<tr>
									<td>Departmetal Performance per Project</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i></button></td>
								</tr>
								<tr>
									<td>Change Requests</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i></button></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

			</section>

		</div>

		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#reports").addClass("active");

		// $(document).ready(function() {
		// 	$(".generateBtn").click(function(){
		// 		alert("Place download function here!");
		// 	});
		// });
		</script>

	</body>
</html>
