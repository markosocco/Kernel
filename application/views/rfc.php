<html>
	<head>
		<title>Kernel - Request for Change</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/rfcStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Request for Change
					<small>What do I think needs changing?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box">
					<div class="box-header">
					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<table id="rfcList" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>Request Type</th>
								<th>Date Requested</th>
								<th>Task Name</th>
								<th>Task Start Date</th>
								<th>Task Target End Date</th>
								<th>Project</th>
							</tr>
							</thead>
							<tbody>
								<?php foreach($changeRequests as $changeRequest):
									$dateRequested = date_create($changeRequest['REQUESTEDDATE']);
									$startDate = date_create($changeRequest['TASKSTARTDATE']);
									$endDate = date_create($changeRequest['TASKENDDATE']);
									if($changeRequest['REQUESTTYPE'] == 1)
										$type = "Change Performer";
									else
										$type = "Change Date/s";

								?>
									<tr>
										<td><?php echo $type;?></td>
										<td><?php echo date_format($dateRequested, "M d, Y"); ?></td>
										<td><?php echo $changeRequest['TASKTITLE'];?></td>
										<td><?php echo date_format($startDate, "M d, Y"); ?></td>
										<td><?php echo date_format($endDate, "M d, Y"); ?></td>
										<td><?php echo $changeRequest['PROJECTTITLE'];?></td>
									</tr>
								<?php endforeach;?>

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
		$("#rfc").addClass("active");

		$(function () {
			$('#rfcList').DataTable({
				'paging'      : false,
				'lengthChange': false,
				'searching'   : true,
				'ordering'    : true,
				'info'        : false,
				'autoWidth'   : false
			});
		});
		</script>

	</body>
</html>
