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
<body onload="window.print();">
<div class="wrapper">
  <!-- Main content -->
  <section>
    <!-- title row -->
    <div class="reportHeader viewCenter">
      <h2>Store Opening - DLSU Andrew Performance Report</h2>
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
            <h5>Timeliness: 95%</h5>
            <h5>Completeness: 63%</h5>
          </div>
          <table class="table table-bordered table-hover">
            <thead>
            <tr>
              <th>Task</th>
              <th class='text-center'>Start Date</th>
              <th class='text-center'>Target End Date</th>
              <th class='text-center'>R</th>
              <th class='text-center'>A</th>
              <th class='text-center'>C</th>
              <th class='text-center'>I</th>
              <th class='text-center'>Status</th>
              <th class='text-center'>Days Completed / Remaining</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>Receive Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
              </tr>
              <tr>
                <td>Double check Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
              </tr>
              <tr>
                <td>Display Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
              </tr>
              <tr>
                <td>Receive Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
              </tr>
              <tr>
                <td>Double check Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
              </tr>
              <tr>
                <td>Display Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
              </tr>
              <tr>
                <td>Receive Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
              </tr>
              <tr>
                <td>Double check Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
              </tr>
              <tr>
                <td>Display Inventory</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Tony Stark</td>
                <td class='text-center'>Steve Rogers</td>
                <td class='text-center'>Bruce Banner</td>
                <td class='text-center'>Nick Fury</td>
                <td class='text-center'>Completed</td>
                <td class='text-center'>1 day</td>
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
