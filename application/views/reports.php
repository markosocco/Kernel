<html>
	<head>
		<title>Kernel - Reports</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/reportsStyle.css")?>"> -->
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

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
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
								<!-- SHOW ONLY TO THOSE PO'S WITH ONGOING PROJECTS -->
								<?php if($allOngoingProjects != NULL):?>
									<tr>
										<td>Project Status Report</td>
										<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#changeProj'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
									</tr>
									<tr>
										<td>Project Progress Report</td>
										<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#changeEmp'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
									</tr>
								<?php endif;?>

								<tr>
									<td>Project Summary</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#projectSummary'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>

								<tr>
									<td>Department Performance</td>
									<td align="center"><a href="<?php echo base_url("index.php/controller/reportsDepartmentPerformance"); ?>" target="_blank" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='top' title='Generate Report'><i class="fa fa-print"></i></a></td>
								</tr>

								<tr>
									<td>Departmental Performance per Project</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#deptPerfProj'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>

								<tr>
									<td>Team Performance</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#deptPerfDept'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>

								<tr>
									<td>Employee Performance</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#empPerfEmp'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr>

								<!-- <tr>
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
								</tr> -->

								<!-- <tr>
									<td>Employee Performance per Project</td>
									<td align="center"><a href="" target="_blank" class="btn btn-success generateBtn" data-toggle='modal' data-target='#empPerfProj'><i class="fa fa-print" data-toggle='tooltip' data-placement='top' title='Generate Report'></i></a></td>
								</tr> -->
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<!-- PROJECT SUMMARY -->
				<div class="modal fade" id="projectSummary" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Project Summary</h2>
							</div>
							<div class="modal-body">
								<form name="projSummReport" id="projSummReport" action="reportsProjectSummary" method="POST" target="_blank">
									<select name="project" id="projectSummarySelect" class="form-control select2" data-placeholder="Select Departments">
										<option disabled selected value = "0">-- Select a Project -- </option>
										<?php
											foreach ($allProjects as $value) {
												echo "<option value=" . $value['PROJECTID'] . ">" . $value['PROJECTTITLE'] . "</option>";
											}
										?>
									</select>
								</form>
								<div class="modal-footer">
									<button id="closeProjectSummary" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id="generateProjSumm" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='left' title='Generate Report'><i class="fa fa-print"></i></button>
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

				<!-- PROJECT STATUS REPORT -->
				<div class="modal fade" id="changeProj" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Project Status Report</h2>
							</div>
							<div class="modal-body">
								<form name="projStatusReport" id="projStatusReport" action="reportsProjectStatus" method="POST" target='_blank'>
									<h4>Status Interval: </h4>
									<div class="btn-group" id="btnStatus">
										<button type="button" id = "weeklyBtn" value = '7' class="btn btn-default intervalsStatus">Weekly</button>
										<button type="button" id = "monthlyBtn" value = '31' class="btn btn-default intervalsStatus">Monthly</button>
									</div>
									<input id="intervalValueStatus" type='hidden' name='interval' value= "">
									<br><br>

									<select id="projectStatus" name="project" class="form-control select2" data-placeholder="Select Departments">
										<option disabled selected value = "0">-- Select a Project -- </option>
										<?php
											foreach ($allOngoingProjects as $value) {
												echo "<option value=" . $value['PROJECTID'] . ">" . $value['PROJECTTITLE'] . "</option>";
											}
										?>
									</select>
								</form>

								<div class="modal-footer">
									<button id = "closeStatusReport" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id="generateStatusReport" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='left' title='Generate Report'><i class="fa fa-print"></i></button>
								</div>
							</div>
						</div>
						<!-- /.modal-content -->
					</div>
					<!-- /.modal-dialog -->
				</div>
				<!-- /.modal -->

				<!-- PROJECT PROGRESS REPORT -->
				<div class="modal fade" id="changeEmp" tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title">Project Progress Report</h2>
							</div>
							<div class="modal-body">
								<form name="projProgressReport" id="projProgressReport" action="reportsProjectProgress" method="POST" data-interval='' target="_blank">
									<h4>Progress Interval: </h4>
									<div class="btn-group" id="btnProgress">
										<button type="button" id = "weeklyBtn" value = '7' class="btn btn-default intervalsProgress">Weekly</button>
										<button type="button" id = "monthlyBtn" value = '31' class="btn btn-default intervalsProgress">Monthly</button>
									</div>
									<input id="intervalValueProgress" type='hidden' name='interval' value= "">
									<br><br>
									<select id="projectProgressSelect" name="project" class="form-control select2" data-placeholder="Select Departments">
										<option disabled selected value = "0">-- Select a Project -- </option>
										<?php
											foreach ($allOngoingProjects as $value) {
												echo "<option value=" . $value['PROJECTID'] . ">" . $value['PROJECTTITLE'] . "</option>";
											}
										?>
									</select>
								</form>

								<div class="modal-footer">
									<button id="closeProjectProgress" type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
									<button id="generateProgressReport" class="btn btn-success generateBtn" data-toggle='tooltip' data-placement='left' title='Generate Report'><i class="fa fa-print"></i></button>
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

		$(document).ready(function() {

			// PROJECT SUMMARY REPORT
			$("#generateProjSumm").click(function()
			{
				$("#projSummReport").submit();
			});

			$("#closeProjectSummary").click(function()
			{
				$("#projectSummarySelect").val("0");
			});

			// PROJECT STATUS REPORT
			$(".btn-group > .btn").click(function(){
			    $(".btn-group > .btn").removeClass("active");
			    $(this).addClass("active");
					$("#intervalValueStatus").attr("value", $(this).val());
			});

			$("#generateStatusReport").click(function()
			{
	      $("#projStatusReport").submit();
			});

			$("#closeStatusReport").click(function()
			{
				$("#projectStatus").val("0");
				$(".intervalsStatus").removeClass("active");
			});

			// PROJECT PROGRESS REPORT
			$(".btn-group > .btn").click(function(){
			    $(".btn-group > .btn").removeClass("active");
			    $(this).addClass("active");
					$("#intervalValueProgress").attr("value", $(this).val());
			});

			$("#generateProgressReport").click(function()
			{
				$("#projProgressReport").submit();
			});

			$("#closeProjectProgress").click(function()
			{
				$("#projectProgressSelect").val("0");
				$(".intervalsProgress").removeClass("active");
			});
		});
		</script>

	</body>
</html>
