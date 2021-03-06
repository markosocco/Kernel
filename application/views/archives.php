<html>
	<head>
		<title>Kernel - Project Archives</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/archivesStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Project Archives
					<small>What are the projects we have done?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<?php if($archives == null):?>
					<h3 class="box-title" style="text-align:center">There are no project archives</h3>
				<?php else:?>
				<div class="box box-danger">
					<div class="box-header">
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="archiveList" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>Project Title</th>
								<th>Project Owner</th>
								<th>Start Date</th>
								<th>Target End Date</th>
								<th>Actual End Date</th>
							</tr>
							</thead>
							<tbody>

								<?php foreach ($archives as $a): ?>
									<tr class="project clickable" data-id = "<?php echo $a['PROJECTID']; ?>">

										<form action = 'projectGantt' method="POST">
												<input type ='hidden' name='archives' value='0'>
										</form>

										<?php
											$start = date_create($a['PROJECTSTARTDATE']);
											$end = date_create($a['PROJECTENDDATE']);
											$actualEnd = date_create($a['PROJECTACTUALENDDATE']);
										;?>

										<td><?php echo $a['PROJECTTITLE']; ?></td>
										<td><?php echo $a['FIRSTNAME'] . " " . $a['LASTNAME']; ?></td>
										<td><?php echo date_format($start, "M d, Y"); ?></td>
										<td><?php echo date_format($end, "M d, Y"); ?></td>
										<td><?php echo date_format($actualEnd, "M d, Y"); ?></td>
									</tr>
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

		// IF USING POST METHOD FOR PROJECT ID
		$(document).on("click", ".project", function() {
			var $id = $(this).attr('data-id');
			$("form").attr("name", "formSubmit");
			$("form").append("<input type='hidden' name='project_ID' value= " + $id + ">");
			$("form").submit();

			// console.log("hello " + $id);
			});

		$("#projectArchives").addClass("active");
		$(function () {
			$('#archiveList').DataTable({
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
