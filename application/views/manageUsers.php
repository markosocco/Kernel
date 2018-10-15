<html>
	<head>
		<title>Admin - Manage Users</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/archivesStyle.css")?>"> -->
	</head>
	<body>
		<?php require("frame.php"); ?>
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Manage Users
					<small>Who uses Kernel?</small>
				</h1>

				<ol class="breadcrumb">
          <?php $dateToday = date('F d, Y | l');?>
          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
        </ol>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box box-danger">
					<div class="box-header">
						<h3 class="box-title">
							<button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Add User"><i class="fa fa-user-plus"></i></button>
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="usersList" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>Last Name</th>
								<th>First Name</th>
								<th>Middle Name</th>
								<th>Department</th>
								<th></th>
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
		$("#manageUsers").addClass("active");

		$(function () {
			$('#usersList').DataTable({
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
