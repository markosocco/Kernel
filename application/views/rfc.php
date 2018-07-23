<html>
	<head>
		<title>Kernel - Change Requests</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/rfcStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Change Requests
					<small>What do I think needs changing?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<?php if($changeRequests != null):?>
					<div class="box box-danger">
						<div class="box-header">
						</div>
						<!-- /.box-header -->

						<div class="box-body">
							<table id="rfcList" class="table table-bordered table-hover">
								<thead>
								<tr>
									<th width="10%">Date Requested</th>
									<th class="text-center">Type</th>
									<th width="15%">Requester</th>
									<th>Task Name</th>
									<th width="10%">Start Date</th>
									<th width="11%">Target End Date</th>
									<th>Project</th>
								</tr>
								</thead>
								<tbody>
									<?php foreach($changeRequests as $changeRequest):
										$dateRequested = date_create($changeRequest['REQUESTEDDATE']);
										if($changeRequest['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
											$startDate = date_create($changeRequest['TASKSTARTDATE']);
										else
											$startDate = date_create($changeRequest['TASKADJUSTEDSTARTDATE']);

										if($changeRequest['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
											$endDate = date_create($changeRequest['TASKENDDATE']);
										else
											$endDate = date_create($changeRequest['TASKADJUSTEDENDDATE']);

										if($changeRequest['REQUESTTYPE'] == 1)
											$type = "Change Performer";
										else
											$type = "Change Date/s";
									?>
										<tr class="request" data-project = "<?php echo $changeRequest['PROJECTID']; ?>" data-request = "<?php echo $changeRequest['REQUESTID']; ?>">

											<form action = 'projectGantt' method="POST">
												<input type ='hidden' name='rfc' value='0'>
											</form>

											<td><?php echo date_format($dateRequested, "M d, Y"); ?></td>
											<td align="center">
												<?php if($changeRequest['REQUESTTYPE'] == 1):?>
													<i class="fa fa-user-times"></i>
												<?php else:?>
													<i class="fa fa-calendar"></i>
												<?php endif;?>
												<!-- <?php echo $type;?> -->
											</td>
											<td><?php echo $changeRequest['FIRSTNAME'] . " " .  $changeRequest['LASTNAME'] ;?></td>
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
				<?php else:?>
					<h3 class="box-title" style="text-align:center" >You have no change requests to approve</h3>
				<?php endif;?>

			</section>

		</div>


		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#rfc").addClass("active");

		$(document).on("click", ".request", function() {
			var $project = $(this).attr('data-project');
			var $request = $(this).attr('data-request');
			$("form").attr("name", "formSubmit");
			$("form").append("<input type='hidden' name='project_ID' value= " + $project + ">");
			$("form").append("<input type='hidden' name='request_ID' value= " + $request + ">");
			$("form").submit();
			});

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
