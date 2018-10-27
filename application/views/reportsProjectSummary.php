<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Project Summary Report</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>dist/css/AdminLTE.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- Own Style -->
  <!-- <link rel="stylesheet" href="<?php echo base_url("/assets/css/reportsProjectPerDeptStyle.css")?>"> -->
  <!-- Report Style -->
  <link rel="stylesheet" href="<?php echo base_url("/assets/css/reportStyle.css")?>">
</head>
<body onload="window.print();" style="font-size: 11px">
<div class="wrapper">
  <!-- Main content -->
  <section>
    <!-- title row -->
    <div class="reportHeader viewCenter">
      <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Project Summary Report</h3>
    </div>
    <div class="reportBody">
      <!-- LOOP START HERE -->
        <div class="box box-danger">
          <table class="table-condensed" style="width:100%">
            <tr>
              <td><b>Title: </b>Project Title Here</td>
              <td align="right"><b>Initial Duration: </b>March 21, 1996 to March 21, 2019</td>
            </tr>
            <tr>
              <td><b>Owner: </b>Marko Socco</td>
              <td align="right"><b>Actual Duration: </b>March 21, 1996 to March 21, 2019</td>
            </tr>
            <tr>
              <td><b>Description: </b>This project is consuming every drop of energy that we have</td>
              <td></td>
            </tr>
          </table>
          <!-- TEAM MEMBERS -->
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Team Members</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<table class="table table-bordered table-condensed" id="">
  									<thead>
  										<tr>
  											<th>Name</th>
                        <th>Position</th>
  											<th>Department</th>
  											<th class='text-center'>Total Tasks</th>
                        <th class='text-center'>Delayed Tasks</th>
  											<th class='text-center'>Timeliness</th>
  										</tr>
  									</thead>
  									<tbody>
  											<tr>
  												<td><?php echo $member['FIRSTNAME'];?> <?php echo $member['LASTNAME'];?></td>
                          <td></td>
  												<td><?php echo $member['DEPARTMENTNAME'];?></td>
  												<td class='text-center'><?php echo $numTasks;?></td>
                          <td class='text-center'></td>
  												<td class='text-center'><?php echo $timeliness;?>%</td>
  											</tr>
  									</tbody>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>

  				<!-- DELAYED TASKS -->
  				<?php if($tasks != null):?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Delayed Tasks</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body" id="delayedBox">
  								<table class="table table-bordered table-condensed" id="delayedTable">
  									<thead>
  										<tr>
  											<th width="20%">Task</th>
  											<th width="10%" class='text-center'>Target<br>End Date</th>
  											<th width="10%" class='text-center'>Actual<br>End Date</th>
  											<th width="5%" class='text-center'>Days Delayed</th>
                        <th width="15%">Responsible</th>
                        <th width="15%" class='text-center'>Department</th>
  											<th width="25">Reason</th>
  										</tr>
  									</thead>
  									<tbody id="delayedData">
  										<?php foreach ($tasks as $task):?>
  											<?php
  											if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
  											{
  												$endDate = $task['TASKENDDATE'];
  												$delay = $task['actualInitial'];
  											}
  											else
  											{
  												$endDate = $task['TASKADJUSTEDENDDATE'];
  												$delay = $task['actualAdjusted'];
  											}?>

  											<?php if($task['TASKACTUALENDDATE'] > $endDate && $task['TASKSTATUS'] == "Complete"):?>
  											<tr>
  												<td><?php echo $task['TASKTITLE'];?></td>
  												<td class='text-center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
  												<td class='text-center'><?php echo date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");?></td>
  												<td align="center"><?php echo $delay;?></td>
                          <td><?php echo $task['FIRSTNAME'];?> <?php echo $task['LASTNAME'];?></td>
                          <td class='text-center'><?php echo $task['DEPARTMENTNAME'];?></td>
  												<td><?php echo $task['TASKREMARKS'];?></td>
  											</tr>
  										<?php endif;?>
  										<?php endforeach;?>
  									</tbody>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>
  			<?php else:?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Delayed Tasks</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<h6 align="center">There were no delayed task</h6>
  							</div>
  						</div>
  					</div>
  					<!-- /.col -->
  				</div>
  			<?php endif;?>

  				<!-- EARLY TASKS -->
  				<?php if($tasks != null):?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Early Tasks</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<table class="table table-bordered table-condensed" id="">
  									<thead>
  										<tr>
  											<th width="20%">Task</th>
  											<th width="10%" class='text-center'>Target<br>End Date</th>
  											<th width="10%" class='text-center'>Actual<br>End Date</th>
  											<th width="5%" class='text-center'>Days Early</th>
                        <th width="15%">Responsible</th>
                        <th width="15%" class='text-center'>Department</th>
  											<th width="25">Reason</th>
  										</tr>
  									</thead>
  									<tbody>
  										<?php foreach ($tasks as $task):?>
  											<?php
  											if($task['TASKADJUSTEDENDDATE'] == "") // check if end date has been previously adjusted
  											{
  												$endDate = $task['TASKENDDATE'];
  												$early = $task['actualInitial'];
  											}
  											else
  											{
  												$endDate = $task['TASKADJUSTEDENDDATE'];
  												$early = $task['actualAdjusted'];
  											}
  											?>

  											<?php if($task['TASKACTUALENDDATE'] < $endDate && $task['TASKSTATUS'] == "Complete"):?>
  												<tr>
  													<td><?php echo $task['TASKTITLE'];?></td>
  													<td class='text-center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
  													<td class='text-center'><?php echo date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");?></td>
  													<td align="center"><?php echo $early;?></td>
                            <td><?php echo $task['FIRSTNAME'];?> <?php echo $task['LASTNAME'];?></td>
                            <td class='text-center'><?php echo $task['DEPARTMENTNAME'];?></td>
  													<td><?php echo $task['TASKREMARKS'];?></td>
  												</tr>
  											<?php endif;?>
  										<?php endforeach;?>
  									</tbody>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>
  				<?php else:?>
  					<div class="row">
  						<div class="col-md-12 col-sm-12 col-xs-12">
  							<div class="box box-default">
  								<div class="box-header with-border">
  									<h5 class="box-title">Early Tasks</h5>
  								</div>
  								<!-- /.box-header -->
  								<div class="box-body">
  									<h6 align="center">There were no early tasks</h6>
  								</div>
  							</div>
  						</div>
  						<!-- /.col -->
  					</div>
  				<?php endif;?>

  				<!-- Requests -->
  				<?php if($changeRequests != null):?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Change Requests</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<table class="table table-bordered table-condensed" id="">
  									<thead>
  										<tr>
  											<th width="0%">Task</th>
                        <th width="0%" class='text-center'>Type</th>
                        <th width="0%" class='text-center'>Date Requested</th>
                        <th width="0">Reason</th>
  											<th width="0%">Requested By</th>
  											<th width="0%" class='text-center'>Department</th>
  											<th width="0%" class='text-center'>Status</th>
  											<th width="0%">Reviewed By</th>
  											<th width="0%" class='text-center'>Date Approved</th>
  											<th width="0">Remarks</th>
  										</tr>
  									</thead>
  									<tbody>
  										<?php foreach($changeRequests as $request):?>
  											<tr>
  												<?php if($request['REQUESTTYPE'] == '1')
  														$type = "<i class='fa fa-user-times'></i>";
  													else
  														$type = "<i class='fa fa-calendar'></i>";
  													?>

  												<?php foreach($users as $user)
  													if($user['USERID'] == $request['users_REQUESTEDBY'])
  													{
  														$requester = $user['FIRSTNAME'] . " " . $user['LASTNAME'];
  														foreach($allDepartments as $dept)
  														{
  															if($user['departments_DEPARTMENTID'] == $dept['DEPARTMENTID'])
  															$deptName = $dept['DEPARTMENTNAME'];
  														}
  													}
  													else if($user['USERID'] == $request['users_APPROVEDBY'])
  													{
  														$approver = $user['FIRSTNAME'] . " " . $user['LASTNAME'];
  													}
  												?>

  												<?php
  												$requestdate = date_create($request['REQUESTEDDATE']);
  												$approveddate = date_create($request['APPROVEDDATE']);
  												?>

  												<td class='text-center'><?php echo $type;?></td>
  												<td><?php echo $request['TASKTITLE'];?></td>
  												<td><?php echo $requester;?></td>
  												<td class='text-center'><?php echo $deptName;?></td>
  												<td class='text-center'><?php echo date_format($requestdate, "M d, Y");?></td>
  												<td><?php echo $request['REASON'];?></td>
  												<td class='text-center'><?php echo $request['REQUESTSTATUS'];?></td>
  												<?php if($request['REQUESTSTATUS'] == 'Pending'):?>
  													<td align='center'>-</td>
  												<?php else:?>
  													<td><?php echo $approver;?></td>
  												<?php endif;?>
  												<td class='text-center'><?php echo date_format($approveddate, "M d, Y");?></td>
  												<?php if($request['REMARKS'] == ""):?>
  												<td align="center">-</td>
  												<?php else:?>
  												<td><?php echo $request['REMARKS'];?></td>
  												<?php endif;?>
  											</tr>
  										<?php endforeach;?>
  									</tbody>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>
  			<?php else:?>
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Change Requests</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<h6 align="center">There were no change requests</h6>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>
  			<?php endif;?>
        </div>

    <div class="endReport viewCenter">
      <p>***END OF REPORT***</p>
    </div>

    <footer class="reportFooter">
      <!-- To the right -->
      <div class="pull-right hidden-xs">
        <!-- <medium>Page 1 of 1M</medium> -->
      </div>
      <!-- Default to the left -->
      <medium>Prepared By: <?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']?></medium>
      <br>
      <medium>Prepared On: <?php echo date('F d, Y'); ?></medium>
    </footer>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>