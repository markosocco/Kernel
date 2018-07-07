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
					<th>Status</th>
					<?php
						foreach ($delayedTaskPerUser as $row) {
							echo "<tr style='color:red'>";
								echo "<td>" . $row['PROJECTTITLE'] . "</td>";
								echo "<td>" . $row['TASKTITLE'] . "</td>";
								echo "<td>" . $row['TASKENDDATE'] . "</td>";
								echo "<td> DELAYED </td>";
							echo "</tr>";
						}

						foreach ($tasks3DaysBeforeDeadline as $data) {
							echo "<tr>";
								echo "<td>" . $data['PROJECTTITLE'] . "</td>";
								echo "<td>" . $data['TASKTITLE'] . "</td>";
								echo "<td>" . $data['TASKENDDATE'] . "</td>";
								echo "<td>" . $data['TASKDATEDIFF'] . " day/s before deadline</td>";
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
