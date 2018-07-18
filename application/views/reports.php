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
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Generate Reports</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="reportList" class="table table-bordered table-hover">
							<tbody>
								<tr>
									<td>Projects Per Department</td>
									<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i> Generate Report</button></td>
								</tr>

								<!-- SAMPLE DATA. PLEASE DELETE  -->
								<td>HOW DO WE PRINT REPORTS PER PROJECT</td>
								<td align="center"><button type="button" class="btn btn-success generateBtn"><i class="fa fa-print"></i> Generate Report</button></td>
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

		$(document).ready(function() {
			$(".generateBtn").click(function(){
				alert("Place download function here!");
			});
		});
		</script>

	</body>
</html>
