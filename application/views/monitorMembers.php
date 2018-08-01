<html>
	<head>
		<title>Kernel - Monitor Members</title>

		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorMembersStyle.css")?>">
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Monitor Members
						<small>What's happening to the members of my team?</small>
					</h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/monitorMembers"); ?>"><i class="fa fa-dashboard"></i> My Team</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>

          <div class="col-md-4 col-sm-6 col-xs-12 pull-right">
              <div class="box-header with-border" style="text-align:center;">
                <h3 class="box-title">Performance</h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <div style="display:inline-block; text-align:center; width:49%;">
                  <div class="circlechart"
                    data-percentage=""> Completeness
                  </div>
                </div>
                <div style="display:inline-block; text-align:center; width:49%;">
                  <div class="circlechart"
                   data-percentage=""> Timeliness
                 </div>
               </div>
              </div>
          </div>
          <!-- /.col -->
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
          <h3>firstName lastName</h3>
          <h4>Position Here</h4>
          <h4>Number of Projects:</h4>
          <h4>Number of tasks:</h4>

          <br><br>
          <div class="box box-danger">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- START LOOP HERE -->
              <h4>Project Name Here</h4>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th width="25%">Task</th>
                    <th width="10%">Start Date</th>
                    <th width="10%">Target<br>End Date</th>
                    <th width="10%">Status</th>
                    <th class="text-center" width="15%">A</th>
                    <th class="text-center" width="15%">C</th>
                    <th class="text-center" width="15%">I</th>
                  </tr>
                </thead>
                <tbody>
                  <tr data-toggle='modal' data-target='#taskDetails'>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                  </tr>
                </tbody>
              </table>
              <!-- END LOOP HERE -->
            </div>
            <!-- /.box-body -->
          </div>

          <!-- Task Detail Modal -->
          <div class="modal fade" id="taskDetails" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h2 class="modal-title">Task Name here</h2>
                  <h3 class="modal-title">Project Name here</h3>
                </div>
                <div class="modal-body">
                  <h4>Delegate History</h4>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="25%">Delagated By</th>
                        <th width="25%">Accountable</th>
                        <th width="25%">Consulted</th>
                        <th width="25%">Informed</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <!-- IF NULL -->
                      <tr>
                        <td colspan="4" align="center">No history</td>
                      </tr>
                    </tbody>
                  </table>
                  <h4>RFC History</h4>
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="20%">Type</th>
                        <th width="20%">Requested By</th>
                        <th width="20%">Approved By</th>
                        <th width="20%">Request Date</th>
                        <th width="20%">Approved Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <!-- IF NULL -->
                      <tr>
                        <td colspan="5" align="center">No History</td>
                      </tr>
                    </tbody>
                  </table>

                  <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
                    <button id = "doneConfirm" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="top" title="Confirm"><i class="fa fa-check"></i> </button>
                  </div>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorTeam").addClass("active");
      $('.circlechart').circlechart(); // Initialization
		</script>
	</body>
</html>
