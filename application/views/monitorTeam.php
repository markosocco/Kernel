<html>
	<head>
		<title>Kernel - Monitor Team</title>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myTeamStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini fixed">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Monitor Team
						<small>What's happening to my team?</small>
					</h1>

					<ol class="breadcrumb">
						<?php $dateToday = date('F d, Y | l');?>
						<p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
					</ol>
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
						<div class="row">

							<form id = 'employeeDrillDown' action = 'monitorMembers'  method="POST">
							</form>

							<?php foreach ($staff as $key => $row): ?>
								<?php if ($row['USERID'] != $_SESSION['USERID']): ?>
								<div class="col-md-4 employee clickable" data-id="<?php echo $row['USERID']; ?>">
									<!-- Widget: user widget style 1 -->
									<div class="box box-widget widget-user">
										<!-- Add the bg color to the header using any of the bg-* classes -->
										<div class="widget-user-header bg-aqua-active">
											<h3 class="widget-user-username"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></h3>
											<h5 class="widget-user-desc"><?php echo $row['POSITION']; ?></h5>
										</div>
										<div class="widget-user-image">
											<img src="<?php echo $row['IDPIC']; ?>" class="img-circle" alt="User Image">
											<!-- <img src="<?php echo base_url()."assets/"; ?>media/idpic.png" class="img-circle" alt="User Image"> -->
										</div>
										<div class="box-footer">
											<div class="row">
												<div class="col-sm-4 border-right">
													<div class="description-block">
														<h5 class="description-header">
															<?php if (in_array($row['USERID'], $pCountStaff)): ?>
																<?php foreach ($projectCount as $pCount): ?>
																 <?php if ($row['USERID'] == $pCount['USERID']): ?>
																	 <?php echo $pCount['projectCount']; ?>
																 <?php endif; ?>
															 <?php endforeach; ?>
															<?php else: ?>
																0
															<?php endif; ?>
														</h5>
														<span class="description-text">PROJECTS</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
												<div class="col-sm-4 border-right">
													<div class="description-block">
														<h5 class="description-header">
															<?php if (in_array($row['USERID'], $tCountStaff)): ?>
																<?php foreach ($taskCount as $tCount): ?>
																 <?php if ($row['USERID'] == $tCount['USERID']): ?>
																	 <?php echo $tCount['taskCount']; ?>
																 <?php endif; ?>
															 <?php endforeach; ?>
															<?php else: ?>
																0
															<?php endif; ?>
														</h5>
														<span class="description-text">TASKS</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
												<div class="col-sm-4">
													<div class="description-block">
														<h5 class="description-header">
															<?php foreach ($performance as $p): ?>
																<?php if ($p['USERID'] == $row['USERID']): ?>
																	<?php if ($p['timeliness'] == NULL): ?>
																		0%
																	<?php elseif ($p['timeliness'] == 100.00): ?>
																		100%
																	<?php elseif ($p['timeliness'] == 0.00): ?>
																		0%
																	<?php else: ?>
																		<?php echo $p['timeliness'] . "%"; ?>
																	<?php endif; ?>
																<?php endif; ?>
															<?php endforeach; ?>
														</h5>
														<span class="description-text">TIMELINESS</span>
													</div>
													<!-- /.description-block -->
												</div>
												<!-- /.col -->
											</div>
											<!-- /.row -->
										</div>
									</div>
									<!-- /.widget-user -->
								</div>
								<!-- /.col -->
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorTeam").addClass("active");

			$(document).on("click", ".employee", function() {
	      var $id = $(this).attr('data-id');
	      $("#employeeDrillDown").attr("name", "formSubmit");
	      $("#employeeDrillDown").append("<input type='hidden' name='employee_ID' value= " + $id + ">");
	      $("#employeeDrillDown").submit();

				console.log($id);
	    });

		</script>
	</body>
</html>
