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
					<a href="<?php echo base_url("index.php/controller/monitorTeam"); ?>" class="btn btn-default btn" data-toggle="tooltip" data-placement="right" title="Return to My Team"><i class="fa fa-arrow-left"></i></a>
					<br><br>
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
                    data-percentage="<?php
											if($completeness['completeness'] == NULL){
												echo 0;
											} else {
												if($completeness['completeness'] == 100.00){
													echo 100;
												} elseif ($completeness['completeness'] == 0.00) {
													echo 0;
												} else {
													echo $completeness['completeness'];
												}
											}
											?>"> Completeness
                  </div>
                </div>
                <div style="display:inline-block; text-align:center; width:49%;">
                  <div class="circlechart"
                   data-percentage="<?php
										 if($timeliness['timeliness'] == NULL){
											 echo 0;
										 } else {
											 if($timeliness['timeliness'] == 100.00){
												 echo 100;
											 } elseif ($timeliness['timeliness'] == 0.00) {
												 echo 0;
											 } else {
												 echo $timeliness['timeliness'];
											 }
										 }
										 ?>"> Timeliness
                 </div>
               </div>
              </div>
          </div>
          <!-- /.col -->
				</section>
				<!-- Main content -->
				<section class="content container-fluid">
					<!-- START HERE -->
          <h3><?php echo $user['FIRSTNAME'] . " " . $user['LASTNAME']; ?></h3>
          <h4><?php echo $user['POSITION']; ?></h4>

					<div class = 'row'>

						<?php $projCount = 0;?>
						<?php foreach ($pCount as $p): ?>
						<?php  if ($p['USERID'] == $user['USERID']): ?>
							<?php $projCount = $p['projectCount']; ?>
						<?php endif; ?>
					<?php endforeach; ?>

					<?php $taskCount = 0;?>
					<?php foreach ($tCount as $t): ?>
					<?php  if ($t['USERID'] == $user['USERID']): ?>
						<?php $taskCount =  $t['taskCount']; ?>
					<?php endif; ?>
				<?php endforeach; ?>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center" id="total"> Ongoing Projects <br><br><b><?php echo $projCount;?></b></h4>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-2 pull-left">
							<div class="box box-danger">
								<!-- /.box-header -->
								<div class="box-body">
									<div class="table-responsive">
										<h4 align="center"> Ongoing Tasks <br><br><b><?php echo $taskCount;?></b></h4>
									</div>
								</div>
							</div>
						</div>
					</div>
          <!-- <h4>Number of ongoing projects:
						<?php foreach ($pCount as $p): ?>
							<?php  if ($p['USERID'] == $user['USERID']): ?>
								<?php echo $p['projectCount']; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</h4>
          <h4>Number of ongoing tasks:
						<?php foreach ($tCount as $t): ?>
							<?php  if ($t['USERID'] == $user['USERID']): ?>
								<?php echo $t['taskCount']; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</h4> -->

          <div class="box box-danger">
            <div class="box-header with-border">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- START LOOP HERE -->
              <?php foreach ($projects as $row): ?>
								<h4><?php echo $row['PROJECTTITLE']; ?></h4>
	              <table class="table table-bordered">
	                <thead>
	                  <tr>
											<th width=".5%"></th>
	                    <th width="27%">Task</th>
	                    <th width="10%">Start Date</th>
	                    <th width="10%">Target<br>End Date</th>
	                    <th class="text-center" width="17.5%">A</th>
	                    <th class="text-center" width="17.5%">C</th>
	                    <th class="text-center" width="17.5%">I</th>
	                  </tr>
	                </thead>
	                <tbody>
	                  <?php foreach ($tasks as $t): ?>
											<?php if ($row['PROJECTID'] == $t['PROJECTID']): ?>
												<tr data-toggle='modal' data-target='#taskDetails' class='clickable'>
													<?php if ($t['TASKSTATUS'] == 'Ongoing'): ?>
														<td class="bg-green"></td>
													<?php elseif ($t['TASKSTATUS'] == 'Delayed'): ?>
														<td class="bg-red"></td>
													<?php elseif ($t['TASKSTATUS'] == 'Planning'): ?>
														<td class="bg-yellow"></td>
													<?php elseif ($t['TASKSTATUS'] == 'Complete'): ?>
														<td class="bg-teal"></td>
													<?php else: ?>
														<td></td>
													<?php endif; ?>
													<!-- <td></td> -->
													<td><?php echo $t['TASKTITLE']; ?></td>

													<?php
														if($t['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
															$endDate = $t['TASKENDDATE'];
														else
															$endDate = $t['TASKADJUSTEDENDDATE'];

														if($t['TASKADJUSTEDSTARTDATE'] == "") // check if start date has been previously adjusted
															$startDate = $t['TASKSTARTDATE'];
														else
															$startDate = $t['TASKADJUSTEDSTARTDATE'];
													?>

			                    <td><?php echo date_format(date_create($startDate), "M d, Y"); ?></td>
			                    <td><?php echo date_format(date_create($endDate), "M d, Y"); ?></td>
			                    <td>
														<?php foreach ($raci as $raciRow): ?>
															<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																<?php if ($raciRow['ROLE'] == '2'): ?>
																	<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																<?php endif; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													</td>
			                    <td>
														<?php foreach ($raci as $raciRow): ?>
															<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																<?php if ($raciRow['ROLE'] == '3'): ?>
																	<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																<?php endif; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													</td>
			                    <td>
														<?php foreach ($raci as $raciRow): ?>
															<?php if ($t['TASKID'] == $raciRow['TASKID']): ?>
																<?php if ($raciRow['ROLE'] == '4'): ?>
																	<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
																<?php endif; ?>
															<?php endif; ?>
														<?php endforeach; ?>
													</td>
			                  </tr>
											<?php endif; ?>
										<?php endforeach; ?>
	                </tbody>
	              </table>
							<?php endforeach; ?>
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
                    <!-- <button type="button" class="btn btn-default pull-left" data-dismiss="modal" data-toggle="tooltip" data-placement="top" title="Close"><i class="fa fa-close"></i></button>
                    <button id = "doneConfirm" type="submit" class="btn btn-success" data-id="" data-toggle="tooltip" data-placement="top" title="Confirm"><i class="fa fa-check"></i> </button> -->
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
