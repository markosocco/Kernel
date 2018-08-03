<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Ongoing Projects</title>
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
<body onload="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <section>
    <!-- title row -->
    <div class="reportHeader viewCenter">
      <h2>Ongoing Projects</h2>
      <h5>Prepared By: <?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']?></h5>
      <h5>Prepared On: <?php echo date('F d, Y'); ?></h5>
    </div>
    <div class="reportBody">
      <!-- LOOP START HERE -->

        <div class="box box-danger">
          <div class="box-header with-border">
            <!-- <h3 class="box-title pull-left"><b><?php echo $dept['DEPARTMENTNAME']; ?></b></h3> -->
          </div>
          <table id="rfcList" class="table table-bordered table-hover">
            <thead>
            <tr>
              <th>Project</th>
              <th class='text-center'>Start Date</th>
              <th class='text-center'>Target End Date</th>
              <th class='text-center'>Days Remaining</th>
              <th class='text-center'>Progress</th>
              <!-- <th class='text-center'>Total Tasks</th>
              <th class='text-center'>Delayed Tasks</th>
              <th class='text-center'>Departments</th>
              <th class='text-center'>Team Size</th> -->
            </tr>
            </thead>
            <tbody>
              <?php foreach ($ongoingProjects as $project): ?>

                <?php

                $startDate = date_create($project['PROJECTSTARTDATE']);
                $endDate = date_create($project['PROJECTENDDATE']);

                foreach($ongoingProjectProgress as $progress)
                {
                  if($progress['PROJECTID'] == $project['PROJECTID'])
                    $projProgress = $progress['projectProgress'];
                }
              ?>

                <tr>
                  <td><?php echo $project['PROJECTTITLE'];?></td>
                  <td align="center"><?php echo date_format($startDate, "M d, Y");?></td>
                  <td align="center"><?php echo date_format($endDate, "M d, Y");?></td>
                  <td align="center"><?php echo $project['datediff'];?></td>
                  <td align="center"><?php echo $projProgress;?>%</td>

                </tr>
              <?php endforeach;?>

            </tbody>
          </table>
        </div>

    <div class="reportFooter viewCenter">
      <p>***END OF REPORT***</p>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
