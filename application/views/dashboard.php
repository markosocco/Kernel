<html>
	<head>
		<title>Kernel - Dashboard</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/dashboardStyle.css")?>"> -->
	</head>
	<body>
		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					WELCOME, <b><?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?></b>
				</h1>
			</section>

			<table id="logsList" class="table table-bordered table-hover">
				<tbody>
					<th>Project Title</th>
					<th>Task</th>
					<th>Task End Date</th>
					<?php
						foreach ($delayedTaskPerUser as $row) {
							echo "<tr>";
								echo "<td>" . $row['PROJECTTITLE'] . "</td>";
								echo "<td>" . $row['TASKTITLE'] . "</td>";
								echo "<td>" . $row['TASKENDDATE'] . "</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>

		</div>

		<?php require("footer.php"); ?>

	</div> <!--.wrapper closing div-->

	<script>
		$("#dashboard").addClass("active");
	</script>

	</body>
</html>
