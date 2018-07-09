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

			<button type="button" class="btn btn-primary"><i class="fa fa-download"></i> Test Alert</button>

			<!--  -->
			<div class="col-md-4">
				<div class="box box-default">
					<div class="box-header with-border">
						<i class="fa fa-warning"></i>

						<h3 class="box-title">Alerts</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="alert alert-danger alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-ban"></i> Alert!</h4>
							Danger alert preview. This alert is dismissable. A wonderful serenity has taken possession of my entire
							soul, like these sweet mornings of spring which I enjoy with my whole heart.
						</div>
						<div class="alert alert-info alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-info"></i> Alert!</h4>
							Info alert preview. This alert is dismissable.
						</div>
						<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-warning"></i> Alert!</h4>
							Warning alert preview. This alert is dismissable.
						</div>
						<div class="alert alert-success alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h4><i class="icon fa fa-check"></i> Alert!</h4>
							Success alert preview. This alert is dismissable.
						</div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>

			<!--  -->

			<?php if($delayedTaskPerUser != NULL || $tasks3DaysBeforeDeadline != NULL): ?>
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
		<?php endif;?>
		</div>

		<?php require("footer.php"); ?>

	</div> <!--.wrapper closing div-->

	<script>
		$("#dashboard").addClass("active");
	</script>

	</body>
</html>
