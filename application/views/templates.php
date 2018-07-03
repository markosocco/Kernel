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
<<<<<<< HEAD
					<small>What are my past projects?</small>
=======
					<small>What are the sample project models?</small>
>>>>>>> ef6172f8af9f6e0716cf0a8c73eec0e00ac36d33
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box">
					<div class="box-header">
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="templateList" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>Project Title</th>
								<!-- IS THIS NEEDED?? -->
								<th>Project Owner</th>
								<th>Start Date</th>
								<th>Target End Date</th>
								<th>Actual End Date</th>
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
