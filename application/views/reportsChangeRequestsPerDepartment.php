<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Change Requests of Marketing Department</title>
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
      <h2>Change Requests of Marketing Department</h2>
      <h5>Prepared By: <?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']?></h5>
      <h5>Prepared On: <?php echo date('F d, Y'); ?></h5>
    </div>
    <div class="reportBody">
      <!-- LOOP START HERE -->

        <div class="box box-danger">
          <div class="box-header with-border">
            <h5>Mickey Mouse</h5>
            <h5>Total Change Requests: 9</h5>
          </div>
          <table id="rfcList" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Request Date</th>
                <th>Type</th>
                <th>Requester</th>
                <th>Reason</th>
                <th>Task</th>
                <th class='text-center'>Start Date</th>
                <th class='text-center'>Target End Date</th>
                <th>Project</th>
                <th>Status</th>
                <th>Reviewed By</th>
                <th class='text-center'>Reviewed On</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>March 21, 2018</td>
                <td>Change Performer</td>
                <td>Winnie the Pooh</td>
                <td>Not my function</td>
                <td>Task 2.3.4.5.6</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 5, 2018</td>
                <td>Store Opening - DLSU Andrew</td>
                <td>Denied</td>
                <td>Mickey Mouse</td>
                <td class='text-center'>March 22, 2018</td>
              </tr>
              <tr>
                <td>March 29, 2018</td>
                <td>Change Date</td>
                <td>Piglet the Pig</td>
                <td>Leave of Absence</td>
                <td>Task 2.3.4.5.6</td>
                <td class='text-center'>Apr 1, 2018</td>
                <td class='text-center'>Apr 5, 2018</td>
                <td>Store Opening - DLSU Bloemen</td>
                <td>Denied</td>
                <td>Mickey Mouse</td>
                <td class='text-center'>March 29, 2018</td>
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
