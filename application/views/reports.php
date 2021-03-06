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
									<td align="center"><a href="<?php echo base_url("index.php/controller/reportsPlannedProjects"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a></td>
								</tr>
								<tr>
									<td>Parked Projects</td>
									<td align="center"><a href="<?php echo base_url("index.php/controller/reportsParkedProjects"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a></td>
								</tr>
								<tr>
									<td>Project Performance</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#projectPerformance'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Employee Performance per Project</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#empPerfProj'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Employee Performance per Employee</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#empPerfEmp'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Departmental Performance per Department</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#deptPerfDept'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Departmental Performance per Project</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#deptPerfProj'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Change Requests per Project</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#changeProj'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Change Requests per Employee</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#changeEmp'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
								<tr>
									<td>Change Requests per Department</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#changeDept'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<!-- PROJECT PERFORMANCE -->
				<div class="modal fade" id="projectPerformance" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Project Performance</h2>
							</div>
							<div class="modal-body">
								<select class="form-control select2" data-placeholder="Select Departments">

									<option>Store Opening - DLSU Andrew</option>
									<option>Store Opening - DLSU Bloemen</option>
									<option>Store Opening - DLSU Pericos</option>
									<option>New Product Launch - Green Tea Popcorn</option>

								</select>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
									<a href="<?php echo base_url("index.php/controller/reportsProjectPerformance"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- EMPLOYEE PERFORMANCE PER PROJECT  -->
				<div class="modal fade" id="empPerfProj" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Employee Performance per Project</h2>
							</div>
							<div class="modal-body">
								<select class="form-control select2" data-placeholder="Select Departments">

										<option>Store Opening - DLSU Andrew</option>
										<option>Store Opening - DLSU Bloemen</option>
										<option>Store Opening - DLSU Pericos</option>
										<option>New Product Launch - Green Tea Popcorn</option>

								</select>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
									<a href="<?php echo base_url("index.php/controller/reportsEmployeesPerformancePerProject"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- EMPLOYEE PERFORMANCE PER EMPLOYEE -->
				<div class="modal fade" id="empPerfEmp" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Employee Performance per Employee</h2>
							</div>
							<div class="modal-body">
								<select class="form-control select2" data-placeholder="Select Departments">

									<option>Minnie Mouse</option>
									<option>Piglet Pig</option>
									<option>Tiger the Tiger</option>
									<option>Spongebob</option>

								</select>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
									<a href="<?php echo base_url("index.php/controller/reportsEmployeesPerformancePerEmployee"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- DEPARTMENTAL PERFORMANCE PER DEPARTMENT -->
				<div class="modal fade" id="deptPerfDept" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Department Performance per Department</h2>
							</div>
							<div class="modal-body">
								<select class="form-control select2" data-placeholder="Select Departments">

										<option>Marketing</option>
										<option>HR</option>
										<option>MIS</option>
										<option>Procurement</option>
										<option>Finance</option>
										<option>Store Operations</option>
										<option>IDK</option>

								</select>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
									<a href="<?php echo base_url("index.php/controller/reportsDepartmentalPerformancePerDepartment"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- DEPARTMENT PERFORMANCE PER PROJECT -->
				<div class="modal fade" id="deptPerfProj" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Department Performance per Project</h2>
							</div>
							<div class="modal-body">
								<select class="form-control select2" data-placeholder="Select Departments">

									<option>Store Opening - DLSU Andrew</option>
									<option>Store Opening - DLSU Bloemen</option>
									<option>Store Opening - DLSU Pericos</option>
									<option>New Product Launch - Green Tea Popcorn</option>

								</select>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
									<a href="<?php echo base_url("index.php/controller/reportsDepartmentalPerformancePerProject"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- CHANGE REQUESTS PER PROJECT -->
				<div class="modal fade" id="changeProj" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Change Requests per Project</h2>
							</div>
							<div class="modal-body">
								<select class="form-control select2" data-placeholder="Select Departments">

									<option>Store Opening - DLSU Andrew</option>
									<option>Store Opening - DLSU Bloemen</option>
									<option>Store Opening - DLSU Pericos</option>
									<option>New Product Launch - Green Tea Popcorn</option>

								</select>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
									<a href="<?php echo base_url("index.php/controller/reportsChangeRequestsPerProject"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- CHANGE REQUESTS PER EMPLOYEE -->
				<div class="modal fade" id="changeEmp" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Change Requests per Employee</h2>
							</div>
							<div class="modal-body">
								<select class="form-control select2" data-placeholder="Select Departments">

									<option>Minnie Mouse</option>
									<option>Piglet Pig</option>
									<option>Tiger the Tiger</option>
									<option>Spongebob</option>

								</select>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
									<a href="<?php echo base_url("index.php/controller/reportsChangeRequestsPerEmployee"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- CHANGE REQUESTS PER DEPARTMENT -->
				<div class="modal fade" id="changeDept" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Change Requests per Department</h2>
							</div>
							<div class="modal-body">
								<select class="form-control select2" data-placeholder="Select Departments">

										<option>Marketing</option>
										<option>HR</option>
										<option>MIS</option>
										<option>Procurement</option>
										<option>Finance</option>
										<option>Store Operations</option>
										<option>IDK</option>

								</select>

								<div class="modal-footer">
									<button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
									<a href="<?php echo base_url("index.php/controller/reportsChangeRequestsPerDepartment"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

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
