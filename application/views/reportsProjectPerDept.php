<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Report - Project Per Department</title>
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
      <h2>Projects Per Department</h2>
      <h4>Prepared By: <?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']?></h4>
      <h4>Prepared On: <?php echo ?></h4>
    </div>
    <div class="reportBody viewCenter">
      <!-- LOOP START HERE -->
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title pull-left"><b>DeptName Department</b></h3>
          <h3 class="box-title pull-right">10 Projects</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th width="45%">Project</th>
                <th width="15%">Target Start Date</th>
                <th width="15%">Target End Date</th>
                <th width="15%">Status</th>
                <th width="10%">Completeness</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>10.78%</td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- LOOP END HERE -->
    <div class="reportFooter viewCenter">
      <p>***END OF REPORT***</p>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>
