<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Department Performance on Store Opening - DLSU Andrew</title>
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
      <h2>Department Performance on Store Opening - DLSU Andrew</h2>
      <h5>Prepared By: <?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']?></h5>
      <h5>Prepared On: <?php echo date('F d, Y'); ?></h5>
    </div>
    <div class="reportBody">
      <!-- LOOP START HERE -->

        <div class="box box-danger">
          <div class="box-header with-border">
            <h5>Owner: Mickey Mouse</h5>
            <h5>Details: 1st branch in DLSU</h5>
            <h5>Start Date: Apr 1, 2018</h5>
            <h5>Target End Date: Aug 28, 2018</h5>
            <h5>Status: Ongoing</h5>
            <h5>Remaining: 6 days</h5>
          </div>
          <table id="rfcList" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Department</th>
                <th>Department Head</th>
                <th class='text-center'>Planned</th>
                <th class='text-center'>Delayed</th>
                <th class='text-center'>Ongoing</th>
                <th class='text-center'>Completed</th>
                <th class='text-center'>Total Tasks</th>
                <th class='text-center'>Timeliness</th>
                <th class='text-center'>Completeness</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Marketing</td>
                <td>Mickey Mouse</td>
                <td align="center">23</td>
                <td align="center">0</td>
                <td align="center">2</td>
                <td align="center">17</td>
                <td align="center">42</td>
                <td align="center">100%</td>
                <td align="center">40%</td>
              </tr>
              <tr>
                <td>Procurement</td>
                <td>Donald Duck</td>
                <td align="center">23</td>
                <td align="center">0</td>
                <td align="center">2</td>
                <td align="center">17</td>
                <td align="center">42</td>
                <td align="center">100%</td>
                <td align="center">40%</td>
              </tr>
              <tr>
                <td>HR</td>
                <td>Tiger the Tiger</td>
                <td align="center">23</td>
                <td align="center">0</td>
                <td align="center">2</td>
                <td align="center">17</td>
                <td align="center">42</td>
                <td align="center">100%</td>
                <td align="center">40%</td>
              </tr>
              <tr>
                <td>Marketing</td>
                <td>Mickey Mouse</td>
                <td align="center">23</td>
                <td align="center">0</td>
                <td align="center">2</td>
                <td align="center">17</td>
                <td align="center">42</td>
                <td align="center">100%</td>
                <td align="center">40%</td>
              </tr>
              <tr>
                <td>Procurement</td>
                <td>Donald Duck</td>
                <td align="center">23</td>
                <td align="center">0</td>
                <td align="center">2</td>
                <td align="center">17</td>
                <td align="center">42</td>
                <td align="center">100%</td>
                <td align="center">40%</td>
              </tr>
              <tr>
                <td>HR</td>
                <td>Tiger the Tiger</td>
                <td align="center">23</td>
                <td align="center">0</td>
                <td align="center">2</td>
                <td align="center">17</td>
                <td align="center">42</td>
                <td align="center">100%</td>
                <td align="center">40%</td>
              </tr>
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
