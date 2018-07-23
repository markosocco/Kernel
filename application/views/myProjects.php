<html>

  <head>
    <title>Kernel - My Projects</title>

    <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/myProjectsStyle.css")?>">
  </head>

  <body class="hold-transition skin-red sidebar-mini">
    <?php require("frame.php"); ?>

    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1 id="myProjectsHeader">
          My Projects
          <small>What are my projects?</small>
        </h1>

        <h1 id="myTeamHeader">
          My Team Projects
          <small>What is my team working on?</small>
        </h1>

        <ol class="breadcrumb">
          <li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content container-fluid" style="padding-top:20px">

        <!-- TOGGLE MY PROJECT -->
        <div id = "divGridListMyProjects" class="pull-right">
          <a href="#" id = "buttonListProjects" class="btn btn-default btn"><i class="fa fa-th-list"></i>
          <a href="#" id = "buttonGridProjects" class="btn btn-default btn"><i class="fa fa-th-large"></i></a>

          <!-- <a href="#" id = "buttonListProjects" class="btn btn-default btn"><i class="fa fa-bars"></i>
          <a href="#" id = "buttonGridProjects" class="btn btn-default btn"><i class="fa fa-clone"></i></a> -->
        </div>

        <div id="divShowMyTeam" class="pull-right">
          <a href="#" id = "showMyTeam" class="btn btn-default btn"><i class="fa fa-users"></i></a>
        </div>

        <!-- TOGGLE MY TEAM -->
        <div id = "divGridListMyTeam" class="pull-right">
          <a href="#" id = "buttonListTeam" class="btn btn-default btn"><i class="fa fa-th-list"></i>
          <a href="#" id = "buttonGridTeam" class="btn btn-default btn"><i class="fa fa-th-large"></i></a>
        </div>

        <div id="divShowMyProjects" class="pull-right">
          <a href="#" id = "showMyProjects" class="btn btn-default btn"><i class="fa fa-briefcase"></i></a>
        </div>

        <div class="btn-group"> <!-- SORT/LEGEND -->
          <button type="button" id = "filterAll" class="btn btn-default">All</button>
          <button type="button" id = "filterDrafted" class="btn bg-maroon">Draft</button>
          <button type="button" id = "filterParked" class="btn btn-info">Parked</button>
          <button type="button" id = "filterPlanned" class="btn btn-warning">Planned</button>
          <button type="button" id = "filterOngoing" class="btn btn-success">Ongoing</button>
          <button type="button" id = "filterDelayed" class="btn btn-danger">Delayed</button>
        </div>

        <br><br>

        <div class="row" id="createProject">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <a href="<?php echo base_url("index.php/controller/newProject"); ?>">
            <div class="small-box bg-blue">
              <div class="inner">
                <h2>Create</h2>
                <p>New<br>Project</p>
              </div>
              <div class="icon" style="margin-top:25px;">
                <i class="ion ion-plus"></i>
              </div>

              <!-- <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div> -->
            </div>
            </a>
          </div>
          <!-- ./col -->

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <a class = "project" data-id = "">
            <div class="small-box bg-purple">
              <div class="inner">
                <h2>Project Title</h2>

                <p>Completed on<b></b><br><i>March 21, 1996</i></p>
              </div>
              <div class="icon" style="margin-top:25px;">
                <i class="ion ion-folder"></i>
              </div>
            </div>
            </a>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <a class = "project" data-id = "">
            <div class="small-box bg-purple">
              <div class="inner">
                <h2>Project Title</h2>

                <p>Completed on<b></b><br><i>March 21, 1996</i></p>
              </div>
              <div class="icon" style="margin-top:25px;">
                <i class="ion ion-folder"></i>
              </div>
            </div>
            </a>
          </div>

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <a class = "project" data-id = "">
            <div class="small-box bg-purple">
              <div class="inner">
                <h2>Project Title</h2>

                <p>Completed on<b></b><br><i>March 21, 1996</i></p>
              </div>
              <div class="icon" style="margin-top:25px;">
                <i class="ion ion-folder"></i>
              </div>
            </div>
            </a>
          </div>
        </div>

        <hr id="hrCreateProject" style="height:1px; background-color:black">

        <!-- PROJECT VIEW -->
        <div id="projectView">

          <div id="myProjectsGridView">

            <div class="row">
              <div class = "projects" id = "completed">
                <?php foreach ($completedProjects as $key=> $value):?>

                  <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
                    <div class="small-box bg-teal">
                      <div class="inner">
                        <h2>100%</h2>

                        <form action = 'projectGantt'  method="POST">
                        </form>

                        <p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Archiving in
                          <?php echo $value['datediff'] +1;?>
                          <?php if(($value['datediff'] +1) > 1):?>
                            days
                          <?php else:?>
                            day
                          <?php endif;?>
                        </i></p>
                      </div>
                      <div class="icon" style="margin-top:25px;">
                        <i class="ion ion-checkmark"></i>
                      </div>
                    </div>
                    </a>
                  </div>
                <!-- ./col -->
                <?php endforeach;?>
              </div>

              <div class = "projects" id = "delayed">

                <?php foreach ($delayedProjects as $key=> $value):?>

                  <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
                    <div class="small-box bg-red">
                      <div class="inner">

                        <h2>
                          <?php
                            foreach ($delayedProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</h2>

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>
                          <?php echo $value['datediff'];?>
                          <?php if(($value['datediff'] +1) > 1):?>
                            days delayed
                          <?php else:?>
                            day delayed
                          <?php endif;?>
                        </i></p>
                      </div>

                      <div class="icon" style="margin-top:25px;">
                        <i class="ion ion-alert-circled"></i>
                      </div>
                    </div>
                    </a>
                  </div>
                  <!-- ./col -->
                <?php endforeach;?>
              </div>

              <div class = "projects" id = "ongoing">
                <?php foreach ($ongoingProjects as $key=> $value):?>

                  <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
                    <div class="small-box bg-green">
                      <div class="inner">

                        <h2>
                          <?php
                            foreach ($ongoingProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</h2>

                            <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>
                          <?php echo $value['datediff'] +1;?>
                          <?php if(($value['datediff'] +1) > 1):?>
                            days remaining
                          <?php else:?>
                            day remaining
                          <?php endif;?>
                        </i></p>
                      </div>
                      <div class="icon" style="margin-top:25px;">
                        <i class="ion ion-clipboard"></i>
                      </div>
                    </div>
                    </a>
                  </div>
                  <!-- ./col -->
                <?php endforeach;?>
              </div>

              <div class = "projects" id = "planned">
                <?php foreach ($plannedProjects as $row):?>
                  <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <a class = "project" data-id = "<?php echo $row['PROJECTID']; ?>">
                    <div class="small-box bg-yellow">
                      <div class="inner">
                        <h2 class="title"><?php echo $row['PROJECTTITLE']; ?></h2>

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <?php //Compute for days remaining
          							if($row['PROJECTADJUSTEDSTATDATE'] == "") // check if start date has been previously adjusted
          								$startdate = date_create($row['PROJECTSTARTDATE']);
          							else
          								$startdate = date_create($row['PROJECTADJUSTEDSTATDATE']);
                        // $startdate = date_create($row['PROJECTSTARTDATE']);
                        ?>
                        <p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Launch in
                          <?php echo $row['datediff'] +1;?>
                          <?php if(($value['datediff'] +1) > 1):?>
                            days
                          <?php else:?>
                            day
                          <?php endif;?>
                        </i></p>
                      </div>
                      <div class="icon" style="margin-top:25px;">
                        <i class="ion ion-lightbulb"></i>
                      </div>
                    </div>
                    </a>
                  </div>
                  <!-- ./col -->
                <?php endforeach;?>
              </div>

              <div class = "projects" id = "parked">
                <?php foreach ($parkedProjects as $key=> $value):?>

                  <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <a class = "project" data-id = "<?php echo $value['PROJECTID']; ?>">
                    <div class="small-box btn-info">
                      <div class="inner">

                        <h2>
                          <?php
                            foreach ($parkedProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</h2>

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Parked</i></p>
                      </div>
                      <div class="icon" style="margin-top:25px;">
                        <i class="ion ion-clock"></i>
                      </div>
                    </div>
                    </a>
                  </div>
                  <!-- ./col -->
                <?php endforeach;?>
              </div>

              <div class = "projects" id = "drafted">
                <?php foreach ($draftedProjects as $row):?>
                  <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <a class = "project" data-id = "<?php echo $row['PROJECTID']; ?>">
                      <div id="draftBox" class="small-box bg-maroon">
                        <div class="inner">
                          <h2 class="title"><?php echo $row['PROJECTTITLE']; ?></h2>

                          <form class="gantt" action = 'projectGantt'  method="POST">
                          </form>

                          <?php //Compute for days remaining
                          $startdate = date_create($row['PROJECTSTARTDATE']);
                          ?>
                          <p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Draft</i></p>
                        </div>
                        <div class="icon" style="margin-top:25px;">
                          <i class="ion ion-clock"></i>
                        </div>
                      </div>
                    </a>
                  </div>
                  <!-- ./col -->
                <?php endforeach;?>
              </div>

            </div>
          </div>
          <!-- ./myProjectsGridView -->

          <div id="myProjectsListView">
            <div class="box">
              <div class="box-header" style="display:inline-block">
                <h3 class="box-title">
                  <a href="<?php echo base_url("index.php/controller/newProject"); ?>">
                    <button type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Create Project</button>
                  </a>
                </h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="projectList" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th width="1%"></th>
                      <th>Project Title</th>
                      <th>Start Date</th>
                      <th>Target End Date</th>
                      <th>Progress</th>
                      <th>Status</th>
                    </tr>
                  </thead>

                  <tbody>

                    <?php foreach ($completedProjects as $key=> $value):?>

                      <?php // to fix date format
                        $completedStart = date_create($value['PROJECTSTARTDATE']);
                        $completedEnd = date_create($value['PROJECTENDDATE']);
                      ?>

                      <tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <td class="bg-blue"></td>
                        <td><?php echo $value['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($completedStart, "M d, Y");?></td>
                        <td><?php echo date_format($completedEnd, "M d, Y");?></td>
                        <td>100%</td>
                        <td>Complete</td>
                      </tr>
                    <?php endforeach;?>

                    <?php foreach ($delayedProjects as $key=> $value):?>

                      <?php // to fix date format
                        $delayedStart = date_create($value['PROJECTSTARTDATE']);
                        $delayedEnd = date_create($value['PROJECTENDDATE']);
                      ?>

                        <tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

                          <form class="gantt" action = 'projectGantt'  method="POST">
                          </form>

                          <td class="bg-red"></td>
                          <td><?php echo $value['PROJECTTITLE']; ?></td>
                          <td><?php echo date_format($delayedStart, "M d, Y");?></td>
                          <td><?php echo date_format($delayedEnd, "M d, Y");?></td>
                          <td>
                          <?php
                            foreach ($delayedProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</td>
                          <td><?php echo "Delayed"; ?></td>
                      </tr>
                    <?php endforeach;?>

                    <?php foreach ($ongoingProjects as $key=> $value):?>

                      <?php // to fix date format
                        $ongoingStart = date_create($value['PROJECTSTARTDATE']);
                        $ongoingEnd = date_create($value['PROJECTENDDATE']);
                      ?>

                      <tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <td class="bg-green"></td>
                        <td><?php echo $value['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($ongoingStart, "M d, Y");?></td>
                        <td><?php echo date_format($ongoingEnd, "M d, Y");?></td>
                        <td>
                        <?php
                          foreach ($ongoingProjectProgress as $row)
                          {
                            if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                            {
                              echo $row['projectProgress'];
                            }
                          } ?>%</td>
                        <td><?php echo $value['PROJECTSTATUS']; ?></td>
                      </tr>
                    <?php endforeach;?>

                    <?php foreach ($plannedProjects as $row):?>

                      <?php // to fix date format
                        $plannedStart = date_create($row['PROJECTSTARTDATE']);
                        $plannedEnd = date_create($row['PROJECTENDDATE']);
                      ?>

                      <tr class="project" data-id = "<?php echo $row['PROJECTID']; ?>">

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <td class="bg-yellow"></td>
                        <td><?php echo $row['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($plannedStart, "M d, Y");?></td>
                        <td><?php echo date_format($plannedEnd, "M d, Y");?></td>
                        <td>0.00%</td>
                        <td><?php echo $row['PROJECTSTATUS']; ?></td>
                      </tr>
                    <?php endforeach;?>

                    <?php foreach ($parkedProjects as $key=> $value):?>

                      <?php // to fix date format
                        $parkedStart = date_create($value['PROJECTSTARTDATE']);
                        $parkedEnd = date_create($value['PROJECTENDDATE']);
                      ?>

                      <tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <td class="btn-info"></td>
                        <td><?php echo $value['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($parkedStart, "M d, Y");?></td>
                        <td><?php echo date_format($parkedEnd, "M d, Y");?></td>
                        <td>
                          <?php
                            foreach ($parkedProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</td>
                        <td><?php echo "Parked"; ?></td>
                      </tr>
                    <?php endforeach;?>

                    <?php foreach ($draftedProjects as $key=> $value):?>

                      <?php // to fix date format
                        $draftedStart = date_create($value['PROJECTSTARTDATE']);
                        $draftedEnd = date_create($value['PROJECTENDDATE']);
                      ?>

                      <tr class="project" data-id = "<?php echo $value['PROJECTID']; ?>">

                        <form class="gantt" action = 'projectGantt'  method="POST">
                        </form>

                        <td class="bg-maroon"></td>
                        <td><?php echo $value['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($draftedStart, "M d, Y");?></td>
                        <td><?php echo date_format($draftedEnd, "M d, Y");?></td>
                        <td>0.00%</td>
                        <td><?php echo "Draft"; ?></td>
                      </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
            <!-- /.box -->
          </div>
          <!-- /.myProjectListView -->
        </div>
        <!-- END OF PROJECT VIEW -->


        <!-- TEAM VIEW -->
        <div id="teamView">

          <div id="myTeamGridView">
            <div class="row">

              <?php foreach ($completedProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box bg-teal">
										<div class="inner">

											<h2>100%</h2>

											<form class="teamgantt" action = 'teamGantt'  method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Archiving in <?php echo $value['datediff'] +1;?> day/s</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-checkmark"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

							<?php foreach ($delayedProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box bg-red">
										<div class="inner">

											<h2>
												<?php
													foreach ($delayedTeamProjectProgress as $row)
													{
														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
														{
															echo $row['projectProgress'];
														}
													}
												?>%</h2>

                      <form class="teamgantt" action = 'teamGantt'  method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i><?php echo $value['datediff'];?> day/s delayed</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-alert-circled"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

							<?php foreach ($ongoingProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box bg-green">
										<div class="inner">

											<h2>
												<?php
													foreach ($ongoingTeamProjectProgress as $row)
													{
														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
														{
															echo $row['projectProgress'];
														}
													}
												?>%</h2>

                      <form class="teamgantt" action = 'teamGantt'  method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i><?php echo $value['datediff'] +1;?> day/s remaining</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-clipboard"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

							<?php foreach ($plannedProjects as $row):?>
								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "myTeam" data-id = "<?php echo $row['PROJECTID']; ?>">
									<div class="small-box bg-yellow">
										<div class="inner">
											<h2><?php echo $row['PROJECTTITLE']; ?></h2>

                      <form class="teamgantt" action = 'teamGantt'  method="POST">
											</form>

											<?php //Compute for days remaining
											$startdate = date_create($row['PROJECTSTARTDATE']);
											?>
											<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Launch in <?php echo $row['datediff'] +1;?> day/s</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-lightbulb"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

							<?php foreach ($parkedProjects as $key=> $value):?>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">
									<div class="small-box btn-info">
										<div class="inner">

											<h2>
												<?php
													foreach ($parkedTeamProjectProgress as $row)
													{
														if ($value['PROJECTID'] == $row['projects_PROJECTID'])
														{
															echo $row['projectProgress'];
														}
													}
												?>%</h2>

                      <form class="teamgantt" action = 'teamGantt'  method="POST">
											</form>

											<p><b><?php echo $value['PROJECTTITLE']; ?></b><br><i>Parked</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-clock"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

							<?php foreach ($draftedProjects as $row):?>
								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<a class = "myTeam" data-id = "<?php echo $row['PROJECTID']; ?>">
									<div id="draftBox" class="small-box bg-maroon">
										<div class="inner">
											<h2><?php echo $row['PROJECTTITLE']; ?></h2>

                      <form class="teamgantt" action = 'teamGantt'  method="POST">
											</form>

											<?php //Compute for days remaining
											$startdate = date_create($row['PROJECTSTARTDATE']);
											?>
											<p><?php echo date_format($startdate, "F d, Y"); ?><br><i>Draft</i></p>
										</div>
										<div class="icon" style="margin-top:25px;">
											<i class="ion ion-clock"></i>
										</div>
									</div>
								</a>
								</div>
								<!-- ./col -->
							<?php endforeach;?>

						</div>
          </div>

          <div id="myTeamListView">
            <div class="box">
              <div class="box-header" style="display:inline-block">
                <h3 class="box-title">
                  <a href="<?php echo base_url("index.php/controller/newProject"); ?>">
                    <button type="button" class="btn btn-primary"><i class="fa fa-upload"></i> Create Project</button>
                  </a>
                </h3>
              </div>
              <!-- /.box-header -->
              <div class="box-body">
                <table id="projectList" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="1%"></th>
                    <th>Project Title</th>
                    <th>Start Date</th>
                    <th>Target End Date</th>
                    <th>Progress</th>
                    <th>Status</th>
                  </tr>
                  </thead>

                  <tbody>

                        <?php foreach ($completedProjects as $key=> $value):?>

                            <?php // to fix date format
                              $completedStart = date_create($value['PROJECTSTARTDATE']);
                              $completedEnd = date_create($value['PROJECTENDDATE']);
                            ?>

                          <tr class="myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">

                            <form class="teamgantt" action = 'teamGantt'  method="POST">
                            </form>

                            <td class="bg-blue"></td>
                            <td><?php echo $value['PROJECTTITLE']; ?></td>
                            <td><?php echo date_format($completedStart, "M d, Y");?></td>
                            <td><?php echo date_format($completedEnd, "M d, Y");?></td>
                            <td>100%</td>
                            <td>Complete</td>
                          </tr>
                        <?php endforeach;?>

                        <?php foreach ($delayedProjects as $key=> $value):?>

                          <?php // to fix date format
                            $delayedStart = date_create($value['PROJECTSTARTDATE']);
                            $delayedEnd = date_create($value['PROJECTENDDATE']);
                          ?>

                        <tr class="myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">

                          <form class="teamgantt" action = 'teamGantt'  method="POST">
                          </form>

                          <td class="bg-red"></td>
                          <td><?php echo $value['PROJECTTITLE']; ?></td>
                          <td><?php echo date_format($delayedStart, "M d, Y");?></td>
                          <td><?php echo date_format($delayedEnd, "M d, Y");?></td>
                          <td>
                            <?php
                              foreach ($delayedTeamProjectProgress as $row)
                              {
                                if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                                {
                                  echo $row['projectProgress'];
                                }
                              } ?>%</td>
                          <td><?php echo "Delayed"; ?></td>
                        </tr>
                      <?php endforeach;?>

                      <?php foreach ($ongoingProjects as $key=> $value):?>

                        <?php // to fix date format
                          $ongoingStart = date_create($value['PROJECTSTARTDATE']);
                          $ongoingEnd = date_create($value['PROJECTENDDATE']);
                        ?>

                      <tr class="myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
                        </form>

                        <td class="bg-green"></td>
                        <td><?php echo $value['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($ongoingStart, "M d, Y");?></td>
                        <td><?php echo date_format($ongoingEnd, "M d, Y");?></td>
                        <td>
                          <?php
                            foreach ($ongoingTeamProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</td>
                          <td><?php echo $value['PROJECTSTATUS']; ?></td>
                      </tr>
                    <?php endforeach;?>

                    <?php foreach ($plannedProjects as $row):?>

                      <?php // to fix date format
                        $plannedStart = date_create($row['PROJECTSTARTDATE']);
                        $plannedEnd = date_create($row['PROJECTENDDATE']);
                      ?>

                      <tr class="myTeam" data-id = "<?php echo $row['PROJECTID']; ?>">

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
                        </form>

                        <td class="bg-yellow"></td>
                        <td><?php echo $row['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($plannedStart, "M d, Y");?></td>
                        <td><?php echo date_format($plannedEnd, "M d, Y");?></td>
                        <td>0.00%</td>
                        <td><?php echo $row['PROJECTSTATUS']; ?></td>
                      </tr>
                    <?php endforeach;?>

                    <?php foreach ($parkedProjects as $key=> $value):?>

                      <?php // to fix date format
                        $parkedStart = date_create($value['PROJECTSTARTDATE']);
                        $parkedEnd = date_create($value['PROJECTENDDATE']);
                      ?>

                      <tr class="myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
                        </form>

                        <td class="btn-info"></td>
                        <td><?php echo $value['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($parkedStart, "M d, Y");?></td>
                        <td><?php echo date_format($parkedEnd, "M d, Y");?></td>
                        <td>
                          <?php
                            foreach ($parkedTeamProjectProgress as $row)
                            {
                              if ($value['PROJECTID'] == $row['projects_PROJECTID'])
                              {
                                echo $row['projectProgress'];
                              }
                            } ?>%</td>
                        <td><?php echo "Parked"; ?></td>
                      </tr>
                    <?php endforeach;?>

                    <?php foreach ($draftedProjects as $key=> $value):?>

                      <?php // to fix date format
                        $draftedStart = date_create($value['PROJECTSTARTDATE']);
                        $draftedEnd = date_create($value['PROJECTENDDATE']);
                      ?>

                      <tr class="myTeam" data-id = "<?php echo $value['PROJECTID']; ?>">

                        <form class="teamgantt" action = 'teamGantt'  method="POST">
                        </form>

                        <td class="bg-maroon"></td>
                        <td><?php echo $value['PROJECTTITLE']; ?></td>
                        <td><?php echo date_format($draftedStart, "M d, Y");?></td>
                        <td><?php echo date_format($draftedEnd, "M d, Y");?></td>
                        <td>0.00%</td>
                        <td><?php echo "Draft"; ?></td>
                      </tr>
                    <?php endforeach;?>

                  </tbody>
                </table>
              </div>
              <!-- /.box-body -->
            </div>
          </div>
        </div>
        <!-- END OF TEAM VIEW -->
      </section>
    </div>

      <?php require("footer.php"); ?>

    </div> <!--.wrapper closing div-->
    <!-- ./wrapper -->

    <script>

    $("#myProjects").addClass("active");
    $("#buttonGridProjects").hide();
    $("#teamView").hide();
    $("#myProjectsListView").hide();
    $("#divGridListMyTeam").hide();
    $("#divShowMyProjects").hide();
    $("#myTeamHeader").hide();

    // show my projects (default: grid view)
    $("#showMyProjects").on("click", function(){
      $("#projectView").show();
      $("#teamView").hide();

      $("#myProjectsGridView").show();
      $("#myProjectsListView").hide();

      $("#divGridListMyProjects").show();
      $("#divGridListMyTeam").hide();

      $("#buttonListProjects").show();
      $("#buttonGridProjects").hide();

      $("#divShowMyTeam").show();
      $("#divShowMyProjects").hide();

      $("#createProject").show();
      $("#hrCreateProject").show();

      $("#myProjectsHeader").show();
      $("#myTeamHeader").hide();
    });

    // show my team (default: grid view)
    $("#showMyTeam").on("click", function(){
      $("#teamView").show();
      $("#projectView").hide();

      $("#myTeamGridView").show();
      $("#myTeamListView").hide();

      $("#divGridListMyTeam").show();
      $("#divGridListMyProjects").hide();

      $("#buttonListTeam").show();
      $("#buttonGridTeam").hide();

      $("#divShowMyProjects").show();
      $("#divShowMyTeam").hide();

      $("#createProject").show();
      $("#hrCreateProject").show();

      $("#myTeamHeader").show();
      $("#myProjectsHeader").hide();
    });

    // show my projects in list view
    $("#buttonListProjects").on("click", function(){

      $("#projectView").show();
      $("#teamView").hide();

      $("#myProjectsListView").show();
      $("#myProjectsGridView").hide();

      $("#divGridListMyProjects").show();
      $("#divGridListMyTeam").hide();

      $("#buttonGridProjects").show();
      $("#buttonListProjects").hide();

      $("#divShowMyTeam").show();
      $("#divShowMyProjects").hide();

      $("#createProject").hide();
      $("#hrCreateProject").hide();

      $("#myProjectsHeader").show();
      $("#myTeamHeader").hide();
    });

    // show my team in list view
    $("#buttonListTeam").on("click", function(){
      $("#teamView").show();
      $("#projectView").hide();

      $("#myTeamListView").show();
      $("#myTeamGridView").hide();

      $("#divGridListMyTeam").show();
      $("#divGridListMyProjects").hide();

      $("#buttonGridTeam").show();
      $("#buttonListTeam").hide();

      $("#divShowMyProjects").show();
      $("#divShowMyTeam").hide();

      $("#createProject").hide();
      $("#hrCreateProject").hide();

      $("#myTeamHeader").show();
      $("#myProjectsHeader").hide();
    });

    // show my projects in grid view
    $("#buttonGridProjects").on("click", function(){
      $("#projectView").show();
      $("#teamView").hide();

      $("#myProjectsGridView").show();
      $("#myProjectsListView").hide();

      $("#divGridListMyProjects").show();
      $("#divGridListMyTeam").hide();

      $("#buttonListProjects").show();
      $("#buttonGridProjects").hide();

      $("#divShowMyTeam").show();
      $("#divShowMyProjects").hide();

      $("#createProject").show();
      $("#hrCreateProject").show();

      $("#myProjectsHeader").show();
      $("#myTeamHeader").hide();
    });

    // show my team in grid view
    $("#buttonGridTeam").on("click", function(){
      $("#teamView").show();
      $("#projectView").hide();

      $("#myTeamGridView").show();
      $("#myTeamListView").hide();

      $("#divGridListMyTeam").show();
      $("#divGridListMyProjects").hide();

      $("#buttonListTeam").show();
      $("#buttonGridTeam").hide();

      $("#divShowMyProjects").show();
      $("#divShowMyTeam").hide();

      $("#createProject").show();
      $("#hrCreateProject").show();

      $("#myTeamHeader").show();
      $("#myProjectsHeader").hide();
    });

    // FILTER 
    $(document).on("click", "#filterAll", function() {
      if($("#projectView").css("display") == 'none' && $("#myTeamListView").css("display") == 'none')
        alert("All Team Projects in Grid View");
      else if($("#projectView").css("display") == 'none' && $("#myTeamGridView").css("display") == 'none')
        alert("All Team Projects in List View");
      else if($("#teamView").css("display") == 'none' && $("#myProjectsListView").css("display") == 'none')
        alert("All My Projects in Grid View");
      else if($("#teamView").css("display") == 'none' && $("#myProjectsGridView").css("display") == 'none')
        alert("All My Projects in List View");

    });

    // IF USING POST METHOD FOR PROJECT ID
    $(document).on("click", ".project", function() {
      var $id = $(this).attr('data-id');
      $(".gantt").attr("name", "formSubmit");
      $(".gantt").append("<input type='hidden' name='project_ID' value= " + $id + ">");
      $(".gantt").submit();
    });

    $(document).on("click", ".myTeam", function() {
      var $id = $(this).attr('data-id');
      $(".teamgantt").attr("name", "formSubmit");
      $(".teamgantt").append("<input type='hidden' name='project_ID' value= " + $id + ">");
      $(".teamgantt").submit();
    });

    </script>
  </body>
</html>
