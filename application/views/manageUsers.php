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
							<span data-toggle='modal' data-target='#modal-addUser'><button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="right" title="Add User"><i class="fa fa-user-plus"></i></button></span>
						</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="usersList" class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>Last Name</th>
								<th>First Name</th>
								<th>Position</th>
								<th>Department</th>
								<th></th>
							</tr>
							</thead>
							<tbody>
								<?php foreach ($users as $u): ?>
									<tr>
										<td><?php echo $u['LASTNAME']; ?></td>
										<td><?php echo $u['FIRSTNAME']; ?></td>
										<td><?php echo $u['POSITION']; ?></td>
										<td><?php echo $u['DEPARTMENTNAME']; ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<!-- ADD USER MODAL -->
				<div class="modal fade" id="modal-addUser" tabindex="-1">
				  <div class="modal-dialog">
				    <div class="modal-content">
				      <div class="modal-header">
				        <h2 class="modal-title" id = "taskTitle">Change Password</h2>
				      </div>
				      <div class="modal-body" style="text-align:center">
				        <div style="text-align:left; display:inline-block">
				          <!-- <p style="color:red"><b>Your new password is new</b></p> -->
				          <form name="changePassword" class="form-horizontal" action="changePassword" method="POST">
				          <div class="form-group">
				            <label for="oldPass" class="col-sm-4 control-label"><span style="color:red">*</span>Last Name</label>
				            <div class="col-sm-8">
				              <input type="password" class="form-control" name="oldPass" id="oldPass" placeholder="Enter Old Password" required>
				            </div>
				          </div>
				          <div class="form-group">
				            <label for="newPass" class="col-sm-4 control-label"><span style="color:red">*</span>First Name</label>
				            <div class="col-sm-8">
				              <input type="password" class="form-control" name="newPass" id="newPass" placeholder="Enter New Password" required>
				            </div>
				          </div>
				          <div class="form-group">
				            <label for="confirmPass" class="col-sm-4 control-label"><span style="color:red">*</span>Middle Name</label>
				            <div class="col-sm-8">
				              <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Confirm New Password" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="confirmPass" class="col-sm-6 control-label"><span style="color:red">*</span>Email</label>
				            <div class="col-sm-6">
				              <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Confirm New Password" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="confirmPass" class="col-sm-6 control-label"><span style="color:red">*</span>Password</label>
				            <div class="col-sm-6">
				              <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Confirm New Password" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="confirmPass" class="col-sm-6 control-label"><span style="color:red">*</span>Department</label>
				            <div class="col-sm-6">
				              <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Confirm New Password" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="confirmPass" class="col-sm-6 control-label"><span style="color:red">*</span>Supervisor</label>
				            <div class="col-sm-6">
				              <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Confirm New Password" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="confirmPass" class="col-sm-6 control-label"><span style="color:red">*</span>User Type</label>
				            <div class="col-sm-6">
				              <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Confirm New Password" required>
				            </div>
				          </div>
									<div class="form-group">
				            <label for="confirmPass" class="col-sm-6 control-label">Active</label>
				            <div class="col-sm-6">
				              <input type="password" class="form-control" name="confirmPass" id="confirmPass" placeholder="Confirm New Password" required>
				            </div>
				          </div>
				          <p><span style="color:red">*</span><small>Required</small></p>
				        </div>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="right" title="Close"><i class="fa fa-close"></i></button>
				        <button type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="left" title="Confirm"><i class="fa fa-check"></i></button>
				      </form>
				      </div>
				    </div>
				  </div>
				</div>
				<!-- ADD USER MODAL -->

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
