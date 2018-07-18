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
								<th>Task Name</th>
								<th>Project</th>
								<th>Start Date</th>
								<th>Target End Date</th>
								<th>Templated On</th>
								<th>Templated By</th>
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
