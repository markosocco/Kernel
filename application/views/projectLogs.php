<html>
	<head>
		<title>Kernel - Project Logs</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectLogsStyle.css")?>"> -->
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<div style="margin-bottom:10px">
					<button id="backBtn" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to Project</button>
					<form id="backForm" action = 'projectGantt' method="POST" data-id="<?php echo $projectID; ?>">
					</form>
				</div>
				<h1>
					Project title - Project Logs
					<small>What is happeing to this project?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<?php if($projectLog == null):?>
					<h3 class="box-title" style="text-align:center">There are no project logs</h3>
				<?php else:?>

				<div class="box">
					<div class="box-header">
						<!-- <h3 class="box-title">Generate Reports</h3> -->
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="logsList" class="table table-bordered table-hover">
							<tbody>
								<?php
									foreach ($projectLog as $row) {
										echo "<tr>";
											echo "<td>" . $row['TIMESTAMP'] . "</td>";
											echo "<td>" . $row['LOGDETAILS'] . "</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			<?php endif;?>

			</section>

		</div>

		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#myProjects").addClass("active");

		$(document).on("click", "#backBtn", function() {
			var $project = $("#backForm").attr('data-id');
			$("#backForm").attr("name", "formSubmit");
			$("#backForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$("#backForm").submit();
			});
		</script>

	</body>
</html>
