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
					<small>What are the projects I have done?</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box">
					<div class="box-header">
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="archiveList" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>Project Title</th>
								<!-- IS THIS NEEDED?? -->
								<th>Project Owner</th>
								<th>Start Date</th>
								<th>Target End Date</th>
								<th>Actual End Date</th>
							</tr>
							</thead>
							<tbody>

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
