<html>
	<head>
		<title>Kernel - Monitor Department Details</title>

		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorMembersStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">

					<button id="backBtn" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to Departments"><i class="fa fa-arrow-left"></i></button>
					<form id="backForm" action = 'monitorDepartment' method="POST" data-id="<?php echo $projectProfile['PROJECTID']; ?>">
					</form>
					<h1>
						<?php echo $projectProfile['PROJECTTITLE'];?>
						<?php
						$projectStart = date_create($projectProfile['PROJECTSTARTDATE']);
						$projectEnd = date_create($projectProfile['PROJECTENDDATE']);
						?>
						<small>(<?php echo date_format($projectStart, "F d, Y");?> to <?php echo date_format($projectEnd, "F d, Y");?>)</small>
					</h1>

					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/monitorProject"); ?>"><i class="fa fa-dashboard"></i> My Team</a></li>
					</ol>

				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->

					<div class = 'row'>
						<?php
						$completed = 0;
						$planned = 0;
						$ongoing = 0;
						$delayed = 0;
						?>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="total"> Total <br><br><b><?php echo count ($tasks);?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<?php foreach($tasks as $task)
							if($task['TASKSTATUS'] == 'Complete')
								$completed++;
							elseif($task['TASKSTATUS'] == 'Planning')
								$planned++;
							elseif($task['TASKSTATUS'] == 'Ongoing')
							{
								$ongoing++;

								if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
									$endDate = $task['TASKENDDATE'];
								else
									$endDate = $task['TASKADJUSTEDENDDATE'];

								if($endDate < $task['currDate'])
									$delayed++;

							}
						?>


						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Delayed <br><br><b><span style='color:red'><?php echo $delayed ;?></span></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing <br><br><b><?php echo $ongoing ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Planned <br><br><b><?php echo $planned ;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Completed <br><br><b><?php echo $completed ;?></b></h4>
									</div>
								</div>
							</div>
						</div>
					</div>

          <div class="box box-danger">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table class="table table-bordered responsive">
                <thead>
                  <tr>
                    <th width="30%">Task</th>
                    <th width="20%">Performer</th>
                    <th width="10%">Start Date</th>
                    <th width="10%">Target<br>End Date</th>
                    <th width="5%">Status</th>
                    <th width="5%">Progress</th>
                  </tr>
                </thead>
                <tbody>
									<?php foreach($tasks as $task):?>
										<?php
										if($task['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
											$startDate = date_create($task['TASKSTARTDATE']);
										else
											$startDate = date_create($task['TASKADJUSTEDSTARTDATE']);

										if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
											$endDate = date_create($task['TASKENDDATE']);
										else
											$endDate = date_create($task['TASKADJUSTEDENDDATE']);
										?>
                  <tr class="clickable" data-toggle='modal' data-target='#taskDetails'>
                    <td><?php echo $task['TASKTITLE'];?></td>
                    <td><?php echo $task['FIRSTNAME'];?> <?php echo $task['LASTNAME'];?></td>
                    <td><?php echo date_format($startDate, "M d, Y");?></td>
                    <td><?php echo date_format($endDate, "M d, Y");?></td>
										<?php if ($endDate < $task['currDate']):?>
											<td>Delayed</td>
										<?php else:?>
	                    <td><?php echo $task['TASKSTATUS'];?></td>
										<?php endif;?>
                    <td align="center"></td>
                  </tr>
								<?php endforeach;?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>

          <!-- Task Detail Modal -->
          <div class="modal fade" id="taskDetails" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h2 class="modal-title">Task Name here</h2>
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
			$("#monitorProject").addClass("active");
      $('.circlechart').circlechart(); // Initialization

			$(document).on("click", "#backBtn", function() {
				var $project = $("#backForm").attr('data-id');
				$("#backForm").attr("name", "formSubmit");
				$("#backForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
				$("#backForm").submit();
				});
		</script>
	</body>
</html>
