<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Project Progress Report</title>
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
      <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Project Progress Report</h3>
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

          <!-- DODONUT CHARTS -->
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<!-- <h5 class="box-title">DONUT CHART HERE</h5> -->
  							</div>
  							<!-- /.box-header -->
  							<div class="box-body">
  								<table class="table table-bordered" id="">
                    <td align="center" style="width:35%"></td>
                    <td style="width:30%">
                      <table class="table table-bordered" width="100%">
                        <tr>
                          <th class='text-center'>2 Weeks Ago</th>
                          <th class='text-center'></th>
                          <th class='text-center'>Last Week</th>
                        </tr>
                        <tr>
                          <td align="center">1k%</td>
                          <td align="center">main1</td>
                          <td align="center">1M%</td>
                        </tr>
                        <tr>
                          <td align="center">1k%</td>
                          <td align="center">main1</td>
                          <td align="center">1M%</td>
                        </tr>
                        <tr>
                          <td align="center">1k%</td>
                          <td align="center">main1</td>
                          <td align="center">1M%</td>
                        </tr>
                      </table>
                    </td>
                    <td align="center" style="width:35%"></td>
  								</table>
  							</div>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>

  				<!-- ACCOMPLISHED TASKS -->
  				<div class="row">
  					<div class="col-md-12 col-sm-12 col-xs-12">
  						<div class="box box-default">
  							<div class="box-header with-border">
  								<h5 class="box-title">Accomplished Tasks</h5>
  							</div>
  							<!-- /.box-header -->
                <?php
                  $withAccomplishedTasks = 'false';
                  foreach($mainActivities as $mainActivity)
                  {
                    $rowCount[$mainActivity['TASKID']] = 0;
                    foreach($subActivities as $subActivity)
                    {
                      if($subActivity['tasks_TASKPARENT'] == $mainActivity['TASKID'])
                      {
                        foreach(${"accomplishedTasks" . $subActivity['TASKID']} as $accomplishedTask)
                        {
                          $rowCount[$mainActivity['TASKID']]++;
                          $withAccomplishedTasks = 'true';
                        }
                      }
                    }
                  }
                ;?>

                <?php if($withAccomplishedTasks == 'true'):?>
                  <div class="box-body" id="delayedBox">
                    <table class="table table-bordered table-condensed" id="">
                      <thead>
                        <tr>
                          <th width="15%">Main Activity</th>
                          <th width="30%">Task</th>
                          <th width="10%" class='text-center'>Start Date</th>
                          <th width="10%" class='text-center'>End Date</th>
                          <th width="15%" class='text-center'>Actual End Date</th>
                          <th class='text-center' width="10%">Days Completed</th>
                          <th>Responsible</th>
                          <th width="15%">Remarks</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach($mainActivities as $mainActivity):?>
                          <?php $counter = 0;?>
                          <?php foreach($subActivities as $subActivity):?>
                          <?php if($subActivity['tasks_TASKPARENT'] == $mainActivity['TASKID']):?>
                          <?php foreach(${"accomplishedTasks" . $subActivity['TASKID']} as $accomplishedTask):?>


                            <?php // to fix date format
                              $taskActualEnd = date_create($accomplishedTask['TASKACTUALENDDATE']);
                              if($accomplishedTask['TASKADJUSTEDENDDATE'] == "")
                              {
                                $taskEnd = date_create($accomplishedTask['TASKENDDATE']);
                                $daysCompleted = $accomplishedTask['completeOrig'];
                              }
                              else
                              {
                                $taskEnd = date_create($accomplishedTask['TASKADJUSTEDENDDATE']);
                                $daysCompleted = $accomplishedTask['completeAdjusted'];

                              }
                            ?>
                          <?php if($counter == 0):?>
                            <tr>
                              <td rowspan="<?php echo $rowCount[$mainActivity['TASKID']];?>" style="vertical-align: middle;"><?php echo $mainActivity['TASKTITLE'];?></td>
                              <td style="vertical-align: middle;"><?php echo $accomplishedTask['TASKTITLE'];?></td>
                              <td style="vertical-align: middle;" align="center"><?php echo date_format(date_create($accomplishedTask['TASKSTARTDATE']), "M d, Y");?></td>
                              <td style="vertical-align: middle;" align="center"><?php echo date_format($taskEnd, "M d, Y");?></td>
                              <td style="vertical-align: middle;" align="center"><?php echo date_format($taskActualEnd, "M d, Y");?></td>
                              <td style="vertical-align: middle;" align="center"><?php echo $daysCompleted;?></td>
                              <td style="vertical-align: middle;"><?php echo $accomplishedTask['FIRSTNAME'];?> <?php echo $accomplishedTask['LASTNAME'];?></td>
                              <td style="vertical-align: middle;"><?php echo $accomplishedTask['TASKREMARKS'];?></td>
                            </tr>
                          <?php else:?>
                            <tr>
                              <td style="vertical-align: middle;"><?php echo $accomplishedTask['TASKTITLE'];?></td>
                              <td style="vertical-align: middle;" align="center"><?php echo date_format(date_create($accomplishedTask['TASKSTARTDATE']), "M d, Y");?></td>
                              <td style="vertical-align: middle;" align="center"><?php echo date_format($taskEnd, "M d, Y");?></td>
                              <td style="vertical-align: middle;" align="center"><?php echo date_format($taskActualEnd, "M d, Y");?></td>
                              <td style="vertical-align: middle;" align="center"><?php echo $daysCompleted;?></td>
                              <td style="vertical-align: middle;"><?php echo $accomplishedTask['FIRSTNAME'];?> <?php echo $accomplishedTask['LASTNAME'];?></td>
                              <td style="vertical-align: middle;"><?php echo $accomplishedTask['TASKREMARKS'];?></td>
                            </tr>
                          <?php endif;?>
                          <?php $counter++;?>
                        <?php endforeach;?>
                        <?php endif;?>
                      <?php endforeach;?>
                    <?php endforeach;?>
                      </tbody>
                    </table>
    							</div>
                <?php else:?>
                  <h5 align="center">There were No Tasks Accomplished Last <?php echo $intervalWord;?></h5>
                <?php endif;?>
  						</div>
  	        </div>
  	        <!-- /.col -->
  				</div>

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
