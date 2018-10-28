<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Report - Department Performance Report</title>
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
        <h3 class="viewCenter"><img class="" id = "logo" src = "<?php echo base_url("/assets/media/tei.png")?>"> Department Performance Report</h3>
        <h4 class="viewCenter">2021</h4>
      </div>
      <div class="reportBody">

        <!-- BAR CHART -->
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Bar Chart</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
              <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <div class="chart">
              <canvas id="barChart" style="height:120px"></canvas>
            </div>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

          <div class="box box-danger">
            <!-- TEAM MEMBERS -->
    				<div class="row">
    					<div class="col-md-12 col-sm-12 col-xs-12">
    						<div class="box box-default">
    							<div class="box-header with-border">
    								<!-- <h5 class="box-title">Team Members</h5> -->
    							</div>
    							<!-- /.box-header -->
    							<div class="box-body">
    								<table class="table table-bordered table-condensed" id="">
                        <tr>
                          <td rowspan="2" align="center" style="vertical-align: middle; font-size:24px;">110%</td>
                          <th colspan="4">DEPARTMENT NAME</th>
                        </tr>
    										<tr>
    											<th>Project</th>
    											<th class='text-center'>End Date</th>
                          <th class='text-center'>Timeliness</th>
    											<th class='text-center'>Completeness</th>
    										</tr>

    									<tbody>
                        <!-- DATA HERE -->
    									</tbody>
    								</table>
    							</div>
    						</div>
    	        </div>
    	        <!-- /.col -->
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
  <!-- ChartJS -->
  <script src="<?php echo base_url()."assets/"; ?>bower_components/chart.js/Chart.js"></script>
  <script>
    $(function ()
    {
      var areaChartData =
      {
        labels  : ['Mkt', 'Fin', 'HR', 'Ops', 'FAD', 'MIS'],
        datasets: [
          {
            label               : 'Timeliness',
            fillColor           : 'rgba(210, 214, 222, 1)',
            strokeColor         : 'rgba(210, 214, 222, 1)',
            pointColor          : 'rgba(210, 214, 222, 1)',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data                : [100, 59, 80, 81, 56, 55]
          },
          {
            label               : 'Completeness',
            fillColor           : 'rgba(60,141,188,0.9)',
            strokeColor         : 'rgba(60,141,188,0.8)',
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : [100, 48, 40, 19, 86, 27]
          }
        ]
      }

      //-------------
      //- BAR CHART -
      //-------------
      var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
      var barChart                         = new Chart(barChartCanvas)
      var barChartData                     = areaChartData
      barChartData.datasets[1].fillColor   = '#00a65a'
      barChartData.datasets[1].strokeColor = '#00a65a'
      barChartData.datasets[1].pointColor  = '#00a65a'
      var barChartOptions                  = {
        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
        scaleBeginAtZero        : true,
        //Boolean - Whether grid lines are shown across the chart
        scaleShowGridLines      : true,
        //String - Colour of the grid lines
        scaleGridLineColor      : 'rgba(0,0,0,.05)',
        //Number - Width of the grid lines
        scaleGridLineWidth      : 1,
        //Boolean - Whether to show horizontal lines (except X axis)
        scaleShowHorizontalLines: true,
        //Boolean - Whether to show vertical lines (except Y axis)
        scaleShowVerticalLines  : true,
        //Boolean - If there is a stroke on each bar
        barShowStroke           : true,
        //Number - Pixel width of the bar stroke
        barStrokeWidth          : 2,
        //Number - Spacing between each of the X value sets
        barValueSpacing         : 5,
        //Number - Spacing between data sets within X values
        barDatasetSpacing       : 1,
        //String - A legend template
        legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
        //Boolean - whether to make the chart responsive
        responsive              : true,
        maintainAspectRatio     : true
      }

      barChartOptions.datasetFill = false
      barChart.Bar(barChartData, barChartOptions)
    })
  </script>
  </body>
</html>
