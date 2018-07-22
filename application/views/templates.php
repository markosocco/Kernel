<html>
	<head>
		<title>Kernel - Project Templates</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/templatesStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Templates
					<small>What are the projects I can replicate?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<?php if($templates == null):?>
					<h3 class="box-title" style="text-align:center">There are no project templates</h3>
				<?php else:?>
				<div class="box">
					<div class="box-header">
					</div>
					<!-- /.box-header -->

					<div class="box-body">
						<table id="templateList" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>Project Title</th>
								<th>Project Owner</th>
								<th>Period</th>
							</tr>
							</thead>
							<tbody>
								<?php foreach ($templates as $row): ?>
									<td><?php echo $row['PROJECTTITLE']; ?></td>
									<td><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></td>

									<?php
										$startdate = date_create($row['PROJECTSTARTDATE']);
										$enddate = date_create($row['PROJECTACTUALENDDATE']);
										$temp = date_diff($enddate, $startdate);
										$dFormat = $temp->format('%d');
										$diff = (int)$dFormat + 1;
									?>

									<td>
										<?php if($diff >= 1): ?>
											<?php echo $diff . " day"; ?>
										<?php else: ?>
											<?php echo $diff . " days"; ?>
									<?php endif; ?>
									</td>
							<?php endforeach; ?>
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
		$("#templates").addClass("active");

		$(function () {
			$('#templateList').DataTable({
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
