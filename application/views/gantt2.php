
<html>
  <head>

    <title>Gantt # 2</title>
    <!-- <h1>https://docs.anychart.com/Quick_Start/Quick_Start</h1> -->
    <!-- <link href="<?php echo base_url()."assets/"; ?>css/jsgantt.css" rel="stylesheet" type="text/css"/> -->

    <link href="<?php echo base_url()."assets/"; ?>anyChart/css/anychart-ui.min.css" rel="stylesheet" type="text/css"/>

    <script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-bundle.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-base.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-core.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url()."assets/"; ?>anyChart/js/anychart-gantt.min.js" type="text/javascript"></script>

    <!-- <script src="https://cdn.anychart.com/releases/8.2.1/js/anychart-core.min.js" type="text/javascript"></script>
    <script src="https://cdn.anychart.com/releases/8.2.1/js/anychart-gantt.min.js" type="text/javascript"></script> -->

  </head>

  <body>
    <div id="container"></div>
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

          foreach ($ganttData as $key => $value) {

        // START: Formatting of TARGET START date
            $startDate = $value['TASKSTARTDATE'];
            $formatted_startDate = date('M d, Y', strtotime($startDate));
            // END: Formatting of TARGET START date

        // START: Formatting of TARGET END date
            $endDate = $value['TASKENDDATE'];
            $formatted_endDate = date('M d, Y', strtotime($endDate));
            // END: Formatting of TARGET END date

        // START: Formatting of ACTUAL START date
            $actualStartDate = $value['TASKACTUALSTARTDATE'];
            $formatted_actualStartDate = date('M d, Y', strtotime($actualStartDate));
            // END: Formatting of ACTUAL START date

        // START: Formatting of ACTUAL END date
            $actualEndDate = $value['TASKACTUALENDDATE'];
            $formatted_actualEndDate = date('M d, Y', strtotime($actualEndDate));
            // END: Formatting of ACTUAL END date

        // START: Completed task - ProgressValue = 100%
            if($value['TASKSTATUS'] == 'Complete'){

              // START: Planning - no baseline since task have not yet started
                  if($value['TASKACTUALSTARTDATE'] == NULL){
                    echo "
                      {
                        'id': " . $value['TASKID'] . ",
                        'name': '" . $value['TASKTITLE'] . "',
                        'actualStart': '" . $formatted_startDate . "',
                        'actualEnd': '" . $formatted_endDate . "',
                        'responsible': '',
                        'accountable': '',
                        'consulted': '',
                        'informed': '',
                        'period': '" . $value['taskDuration'] . "',
                        'progressValue': '100%'
                      },";
                  }
                  // END: Planning - no baseline since task have not yet started

              // START: Ongoing tasks - baselineEnd is the date today
                  else if($value['TASKACTUALENDDATE'] == NULL){
                  // START: Ongoing tasks - delayed // TODO:  FIX
                    if($formatted_endDate < date('M d, Y')){
                      echo "
                        {
                          'id': " . $value['TASKID'] . ",
                          'name': '" . $value['TASKTITLE'] . "',
                          'actualStart': '" . $formatted_startDate . "',
                          'actualEnd': '" . $formatted_endDate . "',
                          'responsible': 'hello',
                          'accountable': '',
                          'consulted': '',
                          'informed': '',
                          'period': '" . $value['taskDuration'] . "',
                          'baselineStart': '" . $formatted_actualStartDate . "',
                          'baselineEnd': '" . date('M d, Y') . "',
                          'progressValue': '100%'
                        },";
                    }
                    // END: Ongoing tasks - delayed

                  // START: Ongoing tasks - but not delayed // TODO:  FIX
                    else if ($formatted_endDate >= date('M d, Y')){
                      echo "
                        {
                          'id': " . $value['TASKID'] . ",
                          'name': '" . $value['TASKTITLE'] . "',
                          'actualStart': '" . $formatted_startDate . "',
                          'actualEnd': '" . $formatted_endDate . "',
                          'responsible': '" . $responsible[$key]['users_USERID'] ."',
                          'accountable': '" . "',
                          'consulted': '',
                          'informed': '',
                          'period': '" . $value['taskDuration'] . "',
                          'baselineStart': '" . $formatted_actualStartDate . "',
                          'baselineEnd': '" . date('M d, Y') . "',
                          'progressValue': '100%'
                        },";
                    }
                    // END: Ongoing tasks - but not delayed
                  }
                  // END: Ongoing tasks - baselineEnd is the date today

              // START: Completed tasks - baselineStart and baselineEnd are present
                  else{
                    echo "
                      {
                        'id': " . $value['TASKID'] . ",
                        'name': '" . $value['TASKTITLE'] . "',
                        'actualStart': '" . $formatted_startDate . "',
                        'actualEnd': '" . $formatted_endDate . "',
                        'responsible': '" . "',
                        'accountable': '" . $formatted_actualEndDate . "',
                        'consulted': '',
                        'informed': '',
                        'period': '" . $value['taskDuration'] . "',
                        'baselineStart': '" . $formatted_actualStartDate . "',
                        'baselineEnd': '" . $formatted_actualEndDate . "',
                        'progressValue': '100%'
                      },";
                    }
                    // END: Completed tasks - baselineStart and baselineEnd are present
            }
            // END: Completed task - ProgressValue = 100%

        // START: ProgressValue = 0%
            else{

              // START: Planning - no baseline since task have not yet started
                  if($value['TASKACTUALSTARTDATE'] == NULL){
                    echo "
                      {
                        'id': " . $value['TASKID'] . ",
                        'name': '" . $value['TASKTITLE'] . "',
                        'actualStart': '" . $formatted_startDate . "',
                        'actualEnd': '" . $formatted_endDate . "',
                        'responsible': '',
                        'accountable': '',
                        'consulted': '',
                        'informed': '',
                        'period': '" . $value['taskDuration'] . "',
                        'progressValue': '0%'
                      },";
                  }
                  // END: Planning - no baseline since task have not yet started

              // START: Ongoing tasks - baselineEnd is the date today
                  else if($value['TASKACTUALENDDATE'] == NULL){
                  // START: Ongoing tasks - delayed // TODO:  FIX
                    if($formatted_endDate < date('M d, Y')){
                      echo "
                        {
                          'id': " . $value['TASKID'] . ",
                          'name': '" . $value['TASKTITLE'] . "',
                          'actualStart': '" . $formatted_startDate . "',
                          'actualEnd': '" . $formatted_endDate . "',
                          'responsible': '" . "',
                          'accountable': '',
                          'consulted': '',
                          'informed': '',
                          'period': '" . $value['taskDuration'] . "',
                          'baselineStart': '" . $formatted_actualStartDate . "',
                          'baselineEnd': '" . date('M d, Y') . "',
                          'progressValue': '0%'
                        },";
                    }
                    // END: Ongoing tasks - delayed

                  // START: Ongoing tasks - but not delayed // TODO:  FIX
                    else if ($formatted_endDate >= date('M d, Y')){
                      echo "
                        {
                          'id': " . $value['TASKID'] . ",
                          'name': '" . $value['TASKTITLE'] . "',
                          'actualStart': '" . $formatted_startDate . "',
                          'actualEnd': '" . $formatted_endDate . "',
                          'responsible': '" . "',
                          'accountable': '',
                          'consulted': '',
                          'informed': '',
                          'period': '" . $value['taskDuration'] . "',
                          'baselineStart': '" . $formatted_actualStartDate . "',
                          'baselineEnd': '" . date('M d, Y') . "',
                          'progressValue': '0%'
                        },";
                    }
                    // END: Ongoing tasks - but not delayed
                  }
                  // END: Ongoing tasks - baselineEnd is the date today

              // START: Completed tasks - baselineStart and baselineEnd are present
                  else{
                    echo "
                      {
                        'id': " . $value['TASKID'] . ",
                        'name': '" . $value['TASKTITLE'] . "',
                        'actualStart': '" . $formatted_startDate . "',
                        'actualEnd': '" . $formatted_endDate . "',
                        'responsible': '" . "',
                        'accountable': '" . "',
                        'consulted': '',
                        'informed': '',
                        'period': '" . $value['taskDuration'] . "',
                        'baselineStart': '" . $formatted_actualStartDate . "',
                        'baselineEnd': '" . $formatted_actualEndDate . "',
                        'progressValue': '0%'
                      },";
                    }
                    // END: Completed tasks - baselineStart and baselineEnd are present
            }
            // END: ProgressValue = 0%

          }
          // END: Foreach
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
        columnTitle.title("Task Name");
        columnTitle.setColumnFormat("name", "text");
        columnTitle.width(300);

        var columnStartDate = dataGrid.column(2);
        columnStartDate.title("Target Start Date");
        columnStartDate.setColumnFormat("actualStart", "dateCommonLog");
        columnStartDate.width(100);

        var columnEndDate = dataGrid.column(3);

        columnEndDate.title("Target End Date");
        columnEndDate.setColumnFormat("actualEnd", "dateCommonLog");
        columnEndDate.width(100);

        var columnPeriod = dataGrid.column(4);
        columnPeriod.title("Period");
        columnPeriod.setColumnFormat("period", "text");
        columnPeriod.width(80);

        var columnResponsible = dataGrid.column(5);
        columnResponsible.title("Responsible");
        columnResponsible.setColumnFormat("responsible", "text");
        columnResponsible.width(100);

        var columnAccountable = dataGrid.column(6);
        columnAccountable.title("Accountable");
        columnAccountable.setColumnFormat("accountable", "text");
        columnAccountable.width(100);

        var columnConsulted = dataGrid.column(7);
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

// OTHER CONDITIONS
      // if(date('Y-m-d') < $value['TASKSTARTDATE'] and $value['TASKSTATUS'] != 'Complete'){
      //   echo "
      //   {
      //       'id':" . $value['TASKID'] . ",
      //       'name': '" . $value['TASKTITLE'] . "',
      //       'actualStart': '" . $formattedStartDate . "',
      //       'actualEnd': '" . $formattedEndDate . "',
      //       'actual':
      //       {
      //         'fill':
      //         {
      //           'keys': ['grey'],
      //         },
      //       },
      //       'baselineStart': '" . $value['TASKACTUALSTARTDATE'] . "',
      //       'baselineEnd': '" . $value['TASKACTUALENDDATE'] . "',
      //       'period': '" . $value['taskDuration'] . "',
      //   },
      //   ";
      // }

      // if(date('Y-m-d') < $value['TASKSTARTDATE'] and $value['TASKSTATUS'] != 'Complete'){
      //   echo "
      //   {
      //       'id':" . $value['TASKID'] . ",
      //       'name': '" . $value['TASKTITLE'] . "',
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
      //       'period': '" . $value['taskDuration'] . "',
      //   },
      //   ";
      // }
      //
      // if(date('Y-m-d') > $value['TASKENDDATE'] and $value['TASKSTATUS'] != 'Complete'){
      //   echo "
      //   {
      //       'id':" . $value['TASKID'] . ",
      //       'name': '" . $value['TASKTITLE'] . "',
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
      //       'period': '" . $value['taskDuration'] . "',
      //   },
      //   ";
      // }

      // echo "
      //   {
      //       'name': '" . $value['TASKTITLE'] . "',
      //       'actualStart': '" . $formatted_startDate . "',
      //       'actualEnd': '" . $formatted_endDate . "',
      //       'department': '" . $formatted_actualStartDate  . "',
      //       'responsible': '" . $formatted_actualEndDate . "',
      //       'period': '" . $value['taskDuration'] . "'
      //   },";

        // if(date('Y-m-d') < $value['TASKSTARTDATE'] and $value['TASKSTATUS'] != 'Complete'){
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
        // if(date('Y-m-d') < $value['TASKSTARTDATE'] and $value['TASKSTATUS'] = 'Complete'){
        //   echo "
        //     {
        //       'actual': {
        //         'fill': {
        //           'keys': ['green'],
        //         }
        //       }
        //     },";
        // }

    </script>
  </body>
</html>
