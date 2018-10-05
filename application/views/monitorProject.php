<html>
	<head>
		<title>Kernel - Monitor Project</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/monitorTeamStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Monitor Project
						<small>What's happening to projects I'm spearheading?</small>
					</h1>

					<ol class="breadcrumb">
	          <?php $dateToday = date('F d, Y | l');?>
	          <p><i class="fa fa-calendar"></i> <b><?php echo $dateToday;?></b></p>
	        </ol>
				</section>
				<!-- Main content -->
				<section class="content container-fluid" style="padding-top:20px">
					<!-- TOGGLE MY PROJECT -->
	        <!-- <div id = "divGridListMyProjects" class="pull-right">
	          <a href="#" id = "buttonListProjects" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="List View"><i class="fa fa-th-list"></i>
	          <a href="#" id = "buttonGridProjects" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="Grid View"><i class="fa fa-th-large"></i></a>
	        </div> -->

	        <!-- <div id="divShowMyTeam" class="pull-right">
	          <a href="#" id = "showMyTeam" class="btn btn-default btn"><i class="fa fa-users" data-toggle="tooltip" data-placement="top" title="My Team"></i></a>
	        </div> -->

	        <!-- TOGGLE MY TEAM -->
	        <!-- <div id = "divGridListMyTeam" class="pull-right">
	          <a href="#" id = "buttonListTeam" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="List View"><i class="fa fa-th-list"></i>
	          <a href="#" id = "buttonGridTeam" class="btn btn-default btn" data-toggle="tooltip" data-placement="top" title="Grid View"><i class="fa fa-th-large"></i></a>
	        </div> -->

	        <!-- <div id="divShowMyProjects" class="pull-right">
	          <a href="#" id = "showMyProjects" class="btn btn-default btn"><i class="fa fa-briefcase" data-toggle="tooltip" data-placement="top" title="My Projects"></i></a>
	        </div> -->

					<!-- SORT/LEGEND -->
					<div>
	          <button type="button" id = "filterAll" class="btn btn-default filter">All</button>
	          <button type="button" id = "filterCompleted" class="btn bg-teal filter">Completed</button>
	          <button type="button" id = "filterOngoing" class="btn btn-success filter">Ongoing</button>
	          <button type="button" id = "filterDelayed" class="btn btn-danger filter">Delayed</button>
	          <button type="button" id = "filterPlanned" class="btn btn-warning filter">Planned</button>
	          <!-- <button type="button" id = "filterParked" class="btn btn-info filter">Parked</button> -->
	          <!-- <button type="button" id = "filterDrafted" class="btn bg-maroon filter">Draft</button> -->
	        </div>

	        <br><br>

	        <!-- PROJECT VIEW -->
	        <div id="projectView">

	          <div id="myProjectsGridView">

	            <div class="row">

	              <?php if($completedProjects == null && $delayedProjects == null &&
	                      $ongoingProjects == null && $plannedProjects == null):?>
	                <h3 class = "projects" align="center">You do not own any project</h3>
	              <?php endif;?>

	              <div class = "projectsGrid" id = "completedProjGrid">
	                <?php foreach ($completedProjects as $key=> $value):?>

	                  <div class="col-lg-3 col-xs-6">
	                    <!-- small box -->
	                    <a class = "project clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
	                    <div class="small-box bg-teal">
	                      <div class="inner">
	                        <h2>100%</h2>

	                        <form action = 'monitorDepartment'  method="POST">
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

	              <div class = "projectsGrid" id = "delayedProjGrid">

	                <?php foreach ($delayedProjects as $key=> $value):?>

	                  <div class="col-lg-3 col-xs-6">
	                    <!-- small box -->
	                    <a class = "project clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
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

	                        <form class="dept" action = 'monitorDepartment'  method="POST">
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

	              <div class = "projectsGrid" id = "ongoingProjGrid">
	                <?php foreach ($ongoingProjects as $key=> $value):?>

	                  <div class="col-lg-3 col-xs-6">
	                    <!-- small box -->
	                    <a class = "project clickable" data-id = "<?php echo $value['PROJECTID']; ?>">
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

                          <form class="dept" action = 'monitorDepartment'  method="POST">
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

	              <div class = "projectsGrid" id = "plannedProjGrid">
	                <?php foreach ($plannedProjects as $row):?>
	                  <div class="col-lg-3 col-xs-6">
	                    <!-- small box -->
	                    <a class = "project clickable" data-id = "<?php echo $row['PROJECTID']; ?>">
	                    <div class="small-box bg-yellow">
	                      <div class="inner">
	                        <h2 class="title"><?php echo $row['PROJECTTITLE']; ?></h2>

	                        <form class="dept" action = 'monitorDepartment'  method="POST">
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

	            </div>
	          </div>
	          <!-- ./myProjectsGridView -->
				</section>
				<!-- /.content -->
			</div>
			<?php require("footer.php"); ?>
		</div>
		<!-- ./wrapper -->
		<script>
			$("#monitor").addClass("active");
			$("#monitorProject").addClass("active");

			$(document).on("click", ".project", function() {
	      var $id = $(this).attr('data-id');
	      $(".dept").attr("name", "formSubmit");
	      $(".dept").append("<input type='hidden' name='project_ID' value= " + $id + ">");
	      $(".dept").submit();
	    });
		</script>
	</body>
</html>
