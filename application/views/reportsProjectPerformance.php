<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Project Performance</title>
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
      <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Project Performance Report</h3>
    </div>
    <div class="reportBody">
      <!-- LOOP START HERE -->
        <div class="box box-danger">
          <table class="table-condensed" style="width:100%">
            <tr>
              <td><b>Title: </b><?php echo $project['PROJECTTITLE']; ?></td>
              <?php // to fix date format
                $projectStart = date_create($project['PROJECTSTARTDATE']);
                $projectEnd = date_create($project['PROJECTENDDATE']);
                if($project['PROJECTSTATUS'] == 'Complete')
                  $projectActualEnd = date_format(date_create($project['PROJECTACTUALENDDATE']), "F d, Y");
                else
                  $projectActualEnd = "Present";
              ?>
              <td align="right"><b>Duration: </b><?php echo date_format($projectStart, "F d, Y") . " - " . date_format($projectEnd, "F d, Y");?></td>
            </tr>
            <tr>
              <td><b>Description: </b><?php echo $project['PROJECTDESCRIPTION']; ?></td>
              <td align="right"><b>Owner: </b>

                <?php foreach ($users as $user): ?>
                  <?php if ($user['USERID'] == $project['users_USERID']): ?>
                    <?php echo $user['FIRSTNAME'] . " " . $user['LASTNAME']; ?>
                  <?php endif; ?>
                <?php endforeach; ?>

              </td>
            </tr>
          </table>

  				<!-- DELAYED TASKS -->
          <?php if($delayedTasks != null):?>
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
    											<th width='20%'>Task</th>
    											<th class='text-center'>End Date</th>
    											<th class='text-center'>Actual<br>End Date</th>
    											<th class='text-center'>Days<br>Delayed</th>
                          <th class="text-center">R</th>
                          <th class="text-center">A</th>
                          <th class="text-center">C</th>
                          <th class="text-center">I</th>
                          <th class='text-center'>Department</th>
    											<th width="10%">Reason</th>
    										</tr>
    									</thead>
    									<tbody id="delayedData">
                        <?php foreach ($delayedTasks as $task):?>
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
    											}
                          foreach($users as $user)
                          {

                          }
                          ?>
  											<tr>
                          <td><?php echo $task['TASKTITLE'];?></td>
                          <td class='text-center'><?php echo date_format(date_create($endDate), "M d, Y");?></td>
  												<td class='text-center'><?php echo date_format(date_create($task['TASKACTUALENDDATE']), "M d, Y");?></td>
                          <td class='text-center'><?php echo $delay?></td>
                          <td>
      											<?php foreach ($raci as $raciRow): ?>
      												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
      													<?php if ($raciRow['ROLE'] == '1'): ?>
      														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
                                  <?php foreach($users as $user):?>
                                    <?php if ($raciRow['users_USERID'] == $user['USERID']): ?>
                                      <?php $deptID = $user['departments_DEPARTMENTID'];?>
                                    <?php endif;?>
                                  <?php endforeach;?>
      													<?php endif; ?>
      												<?php endif; ?>
      											<?php endforeach; ?>
      										</td>
      										<td>
      											<?php foreach ($raci as $raciRow): ?>
      												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
      													<?php if ($raciRow['ROLE'] == '2'): ?>
      														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
      													<?php endif; ?>
      												<?php endif; ?>
      											<?php endforeach; ?>
      										</td>
      										<td>
      											<?php foreach ($raci as $raciRow): ?>
      												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
      													<?php if ($raciRow['ROLE'] == '3'): ?>
      														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
      													<?php endif; ?>
      												<?php endif; ?>
      											<?php endforeach; ?>
      										</td>
      										<td>
      											<?php foreach ($raci as $raciRow): ?>
      												<?php if ($task['TASKID'] == $raciRow['TASKID']): ?>
      													<?php if ($raciRow['ROLE'] == '4'): ?>
      														<?php echo $raciRow['FIRSTNAME'] . " " . $raciRow['LASTNAME']; ?>
      													<?php endif; ?>
      												<?php endif; ?>
      											<?php endforeach; ?>
      										</td>
                          <td class='text-center'>
                            <?php foreach ($departments as $department): ?>
      												<?php if ($deptID == $department['DEPARTMENTID']): ?>
                                <?php echo $department['DEPARTMENTNAME'];?>
      												<?php endif; ?>
      											<?php endforeach; ?>
                          </td>
                          <td><?php echo $task['TASKREMARKS'];?></td>
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
  								<h5 class="box-title">Delayed Tasks</h5>
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<h6 align="center">There were no delayed tasks</h6>
  							</div>
  						</div>
  					</div>
  					<!-- /.col -->
  				</div>
        <?php endif;?>

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
