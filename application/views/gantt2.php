
<html>
  <head>

    <title>Gantt # 2</title>
    <h1>https://docs.anychart.com/Quick_Start/Quick_Start</h1>

  </head>

  <body>
    <div id="container"></div>

    <script src="https://cdn.anychart.com/releases/8.2.1/js/anychart-core.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.2.1/js/anychart-gantt.min.js" type="text/javascript"></script>

    <script>

      anychart.onDocumentReady(function (){

          // name: task TITLE
          // actualStart: Start date
          // actualEnd: End date
          // baselineStart: Actual Start date
          // baselineEnd: Actual End date
          // period: actual end - actual start
          // progressValue - progress (completeness)
          // 'progress':{'fill': 'red'}

          // planned and actual in data
          // {
          //   'name': "revision",
          //   'actualStart': Date.UTC(2010, 5, 1, 8),
          //   'actualEnd': Date.UTC(2010, 5, 24, 18),
          //   'actual':
          //       {
          //           'fill':
          //           {
          //               'keys': ['orange', 'red'],
          //               'angle': 0
          //           },
          //       },
          //   'baselineStart': Date.UTC(2010, 4, 29, 9),
          //   'baselineEnd': Date.UTC(2010, 5, 27, 18),
          //   'baseline':
          //       {
          //           'stroke': '3 black',
          //           'fill': {'color': 'gray'}
          //       }
          //   '"connectTo": "5",
          //   "connectorType": "finish-start"'
          //      'connector':
          //        {
          //         'stroke': {color: '#3300CC .2'},
          //          'fill': {'color': '6600CC .5'}
          //        }
          // }

          var rawData = [
            <?php

            foreach ($ganttData as $row) {

              $startDate = $row['TASKSTARTDATE'];
              $startMonthNum = substr($startDate, 5, 2);
              $startDay = substr($startDate, 8, 2);
              $startYear = substr($startDate, 0, 4);
              $startMonth = "";

              switch (intval($startMonthNum))
              {
                case 1: $startMonth = 'Jan'; break;
                case 2: $startMonth = 'Feb'; break;
                case 3: $startMonth = 'Mar'; break;
                case 4: $startMonth = 'Apr'; break;
                case 5: $startMonth = 'May'; break;
                case 6: $startMonth = 'Jun'; break;
                case 7: $startMonth = 'Jul'; break;
                case 8: $startMonth = 'Aug'; break;
                case 9: $startMonth = 'Sep'; break;
                case 10: $startMonth = 'Oct'; break;
                case 11: $startMonth = 'Nov'; break;
                case 12: $startMonth = 'Dec'; break;
              }

              $formattedStartDate = $startMonth . " " . $startDay . ", " . $startYear;

              $endDate = $row['TASKENDDATE'];
              $endMonthNum = substr($endDate, 5, 2);
              $endDay = substr($endDate, 8, 2);
              $endYear = substr($endDate, 0, 4);
              $endMonth = "";

              switch (intval($endMonthNum))
              {
                case 1: $endMonth = 'Jan'; break;
                case 2: $endMonth = 'Feb'; break;
                case 3: $endMonth = 'Mar'; break;
                case 4: $endMonth = 'Apr'; break;
                case 5: $endMonth = 'May'; break;
                case 6: $endMonth = 'Jun'; break;
                case 7: $endMonth = 'Jul'; break;
                case 8: $endMonth = 'Aug'; break;
                case 9: $endMonth = 'Sep'; break;
                case 10: $endMonth = 'Oct'; break;
                case 11: $endMonth = 'Nov'; break;
                case 12: $endMonth = 'Dec'; break;
              }

              $formattedEndDate = $endMonth . " " . $endDay . ", " . $endYear;

              $actualStartDate = $row['TASKACTUALSTARTDATE'];
              $actualStartMonthNum = substr($startDate, 5, 2);
              $actualStartDay = substr($startDate, 8, 2);
              $actualStartYear = substr($startDate, 0, 4);
              $actualStartMonth = "";

              switch (intval($actualStartMonthNum))
              {
                case 1: $actualStartMonth = 'Jan'; break;
                case 2: $actualStartMonth = 'Feb'; break;
                case 3: $actualStartMonth = 'Mar'; break;
                case 4: $actualStartMonth = 'Apr'; break;
                case 5: $actualStartMonth = 'May'; break;
                case 6: $actualStartMonth = 'Jun'; break;
                case 7: $actualStartMonth = 'Jul'; break;
                case 8: $actualStartMonth = 'Aug'; break;
                case 9: $actualStartMonth = 'Sep'; break;
                case 10: $actualStartMonth = 'Oct'; break;
                case 11: $actualStartMonth = 'Nov'; break;
                case 12: $actualStartMonth = 'Dec'; break;
              }

              $formattedActualStartDate = $actualStartMonth . " " . $actualStartDay . ", " . $actualStartYear;

              $actualEndDate = $row['TASKACTUALENDDATE'];
              $actualEndMonthNum = substr($endDate, 5, 2);
              $actualEndDay = substr($endDate, 8, 2);
              $actualEndYear = substr($endDate, 0, 4);
              $actualEndMonth = "";

              switch (intval($actualEndMonthNum))
              {
                case 1: $actualEndMonth = 'Jan'; break;
                case 2: $actualEndMonth = 'Feb'; break;
                case 3: $actualEndMonth = 'Mar'; break;
                case 4: $actualEndMonth = 'Apr'; break;
                case 5: $actualEndMonth = 'May'; break;
                case 6: $actualEndMonth = 'Jun'; break;
                case 7: $actualEndMonth = 'Jul'; break;
                case 8: $actualEndMonth = 'Aug'; break;
                case 9: $actualEndMonth = 'Sep'; break;
                case 10: $actualEndMonth = 'Oct'; break;
                case 11: $actualEndMonth = 'Nov'; break;
                case 12: $actualEndMonth = 'Dec'; break;
              }

              $formattedActualEndDate = $actualEndMonth . " " . $actualEndDay . ", " . $actualEndYear;

              echo "console.log('before' + ".$formattedActualEndDate.");";



              // if(date('Y-m-d') < $row['TASKSTARTDATE'] and $row['TASKSTATUS'] != 'Complete'){
              //   echo "
              //   {
              //       'id':" . $row['TASKID'] . ",
              //       'name': '" . $row['TASKTITLE'] . "',
              //       'actualStart': '" . $formattedStartDate . "',
              //       'actualEnd': '" . $formattedEndDate . "',
              //       'actual':
              //       {
              //         'fill':
              //         {
              //           'keys': ['grey'],
              //         },
              //       },
              //       'baselineStart': '" . $formattedActualStartDate . "',
              //       'baselineEnd': '" . $formattedActualEndDate . "',
              //       'period': '" . $row['taskDuration'] . "',
              //   },
              //   ";
              // }

              // if(date('Y-m-d') < $row['TASKSTARTDATE'] and $row['TASKSTATUS'] != 'Complete'){
              //   echo "
              //   {
              //       'id':" . $row['TASKID'] . ",
              //       'name': '" . $row['TASKTITLE'] . "',
              //       'actualStart': '" . $formattedStartDate . "',
              //       'actualEnd': '" . $formattedEndDate . "',
              //       'actual':
              //       {
              //         'fill':
              //         {
              //           'keys': ['grey'],
              //         },
              //       },
              //       'baselineStart': '" . $formattedActualStartDate . "',
              //       'baselineEnd': '" . $formattedActualEndDate . "',
              //       'period': '" . $row['taskDuration'] . "',
              //   },
              //   ";
              // }
              //
              // if(date('Y-m-d') > $row['TASKENDDATE'] and $row['TASKSTATUS'] != 'Complete'){
              //   echo "
              //   {
              //       'id':" . $row['TASKID'] . ",
              //       'name': '" . $row['TASKTITLE'] . "',
              //       'actualStart': '" . $formattedStartDate . "',
              //       'actualEnd': '" . $formattedEndDate . "',
              //       'actual':
              //       {
              //         'fill':
              //         {
              //           'keys': ['red'],
              //         },
              //       },
              //       'baselineStart': '" . $formattedActualStartDate . "',
              //       'baselineEnd': '" . $formattedActualEndDate . "',
              //       'period': '" . $row['taskDuration'] . "',
              //   },
              //   ";
              // }

              echo "
                {
                    'id':" . $row['TASKID'] . ",
                    'name': '" . $row['TASKTITLE'] . "',
                    'actualStart': '" . $formattedStartDate . "',
                    'actualEnd': '" . $formattedEndDate . "',
                    'department': '" . $formattedActualStartDate . "',
                    'responsible': '" . $formattedActualEndDate . "',
                    'period': '" . $row['taskDuration'] . "'
                },";

                echo "console.log('after' + ".$formattedActualEndDate.");";

                // if(date('Y-m-d') < $row['TASKSTARTDATE'] and $row['TASKSTATUS'] != 'Complete'){
                //   echo "
                //     {
                //       'actual': {
                //         'fill': {
                //           'keys': ['grey'],
                //         }
                //       }
                //     },";
                // }
                //
                // if(date('Y-m-d') < $row['TASKSTARTDATE'] and $row['TASKSTATUS'] = 'Complete'){
                //   echo "
                //     {
                //       'actual': {
                //         'fill': {
                //           'keys': ['green'],
                //         }
                //       }
                //     },";
                // }


            }
            ?>
          ];



        // data tree settings
        var treeData = anychart.data.tree(rawData, "as-tree");
        var chart = anychart.ganttProject();      // chart type
        chart.data(treeData);                     // chart data

        // data grid getter
        var dataGrid = chart.dataGrid();

        // create custom column
        var columnTitle = dataGrid.column(1);
        columnTitle.title("Task Title");
        columnTitle.setColumnFormat("name", "text");
        columnTitle.width(300);

        var columnStartDate = dataGrid.column(2);
        columnStartDate.title("Start Date");
        columnStartDate.setColumnFormat("actualStart", "dateCommonLog");
        columnStartDate.width(100);

        var columnEndDate = dataGrid.column(3);
        columnEndDate.title("End Date");
        columnEndDate.setColumnFormat("actualEnd", "dateCommonLog");
        columnEndDate.width(100);

        var columnPeriod = dataGrid.column(4);
        columnPeriod.title("Period");
        columnPeriod.setColumnFormat("period", "text");
        columnPeriod.width(80);

        var columnDepartment = dataGrid.column(5);
        columnDepartment.title("Department");
        columnDepartment.setColumnFormat("department", "text");
        columnDepartment.width(100);

        var columnResponsible = dataGrid.column(6);
        columnResponsible.title("Responsible");
        columnResponsible.setColumnFormat("responsible", "text");
        columnResponsible.width(100);

        var columnAccountable = dataGrid.column(7);
        columnAccountable.title("Accountable");
        columnAccountable.setColumnFormat("accountable", "text");
        columnAccountable.width(100);

        var columnConsulted = dataGrid.column(8);
        columnConsulted.title("Consulted");
        columnConsulted.setColumnFormat("consulted", "text");
        columnConsulted.width(100);

        var columnInformed = dataGrid.column(9);
        columnInformed.title("Informed");
        columnInformed.setColumnFormat("informed", "text");
        columnInformed.width(100);

        //get chart timeline link to change color

        chart.splitterPosition(1040);
        chart.container('container').draw();      // set container and initiate drawing

      });

    </script>

  </body>

</html>
