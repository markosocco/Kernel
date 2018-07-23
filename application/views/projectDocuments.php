<html>
	<head>
		<title>Kernel - Project Documents</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectDocumentsStyle.css")?>"> -->
	</head>
	<body class="hold-transition skin-red sidebar-mini sidebar-collapse">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<div style="margin-bottom:10px">
						<button id="backBtn" class="btn btn-default btn"><i class="fa fa-arrow-left"></i> Return to Project</button>
						<form id="backForm" action = 'projectGantt' method="POST" data-id="<?php echo $projectProfile['PROJECTID']; ?>">
						</form>

					</div>

					<?php
						$startdate = date_create($projectProfile['PROJECTSTARTDATE']);
						$enddate = date_create($projectProfile['PROJECTENDDATE']);
					?>

					<h1>
						Documents
						<small><?php echo $projectProfile['PROJECTTITLE']; ?> (<?php echo date_format($startdate, "F d, Y") . " - " . date_format($enddate, "F d, Y");?>)</small>
					</h1>
					<ol class="breadcrumb">
						<li class ="active"><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<!-- <li class="active">Here</li> -->
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<!-- <div id="filterButtons">
						<h5>Arrange by</h5>
					</div> -->

					<div class="row">
		        <div class="col-xs-12">
		          <div class="box">
		            <div class="box-header">
		              <h3 class="box-title">
										<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-upload"><i class="fa fa-upload"></i> Upload</button>
									</h3>
									<!-- <?php if ($documentsByProject != null):?>
			              <div class="box-tools">
			                <div class="input-group input-group-sm" style="width: 150px;">
			                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

			                  <div class="input-group-btn">
			                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
			                  </div>
			                </div>
			              </div>
									<?php endif;?> -->
		            </div>
		            <!-- /.box-header -->

		            <div class="box-body">
									<?php if($documentsByProject == NULL):?>
										<h3 class="box-title" style="text-align:center">There are no documents uploaded</h3>
									<?php else: ?>
		              <table id="documentsTable" class="table table-hover">
										<thead>
			                <tr>
			                  <th width="25%">Document Name</th>
			                  <th width="10%">Uploaded By</th>
			                  <th width="10">Department</th>
												<th width="10">Uploaded On</th>
			                  <th width="25%">Remarks</th>
												<th width="10%"><i class='fa fa-download'></i></th>
												<!-- <th width="10%"><i class='fa fa-eye'></i></th> -->
			                </tr>
										</thead>
										<tbody>

										<?php

											foreach ($documentsByProject as $row) {
												if($row['DOCUMENTSTATUS'] == 'For Acknowledgement'){
													// echo "<script>alert('".$row['DOCUMENTID']." for Acknowledgement');</script>";
													echo"
													<form action='acknowledgeDocument' method='POST' class ='acknowledgeDocument'>
														<input type='hidden' name='project_ID' value='" . $projectProfile['PROJECTID'] . "'>
													</form>";

													foreach($documentAcknowledgement as $data){
														if($row['DOCUMENTID'] == $data['documents_DOCUMENTID'] || $row['users_UPLOADEDBY'] == $_SESSION['USERID']){
															echo "<tr>";
																echo "<td>" . $row['DOCUMENTNAME'] . "</td>";
																echo "<td>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</td>";
																echo "<td>" . $row['DEPARTMENTNAME'] . "</td>";
																echo "<td>" . date('M d, Y', strtotime($row['UPLOADEDDATE'])) . "</td>";
																echo "<td>" . $row['REMARKS'] . "</td>";
																echo "<td align='center'><a href = '" . $row['DOCUMENTLINK']. "' download>
																<button type='button' class='btn btn-success'>
																<i class='fa fa-download'></i> Download</button></a></td>";

																if($row['users_UPLOADEDBY'] != $_SESSION['USERID']){
																	if($data['ACKNOWLEDGEDDATE'] != ''){
																		echo "<td align='center'>Acknowledged</td>";
																	} else {
																		echo "<td align='center'>
																		<button type='button' class='btn btn-success document' name='documentButton' id='acknowledgeButton' data-id ='" . $row['DOCUMENTID'] . "'>
																		<i class='fa fa-eye'></i> Acknowledge</button></td>";
																	}

																}
															echo "</tr>";
														}
													}
												} else {
													echo "<tr>";
														echo "<td>" . $row['DOCUMENTNAME'] . "</td>";
														echo "<td>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</td>";
														echo "<td>" . $row['DEPARTMENTNAME'] . "</td>";
														echo "<td>" . date('M d, Y', strtotime($row['UPLOADEDDATE'])) . "</td>";
														echo "<td>" . $row['REMARKS'] . "</td>";
														echo "<td align='center'><a href = '" . $row['DOCUMENTLINK']. "' download>
														<button type='button' class='btn btn-success'>
														<i class='fa fa-download'></i> Download</button></a></td>";
													echo "</tr>";
												}
											}
										?>
									</tbody>
		              </table>
								<?php endif;?>
		            </div>
		            <!-- /.box-body -->
		          </div>
		          <!-- /.box -->
		        </div>



						<?php echo form_open_multipart('controller/uploadDocument');?>

							<input type="hidden" name="project_ID" value= "<?php echo $projectProfile['PROJECTID']; ?>">

						<div class="modal fade" id="modal-upload">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h4 class="modal-title">Upload a Document</h4>
		              </div>
		              <div class="modal-body">
										<p><b>Upload this document for</b></p>
										<div class="row">
											<div class="col-lg-6">
												<p>Departments</p>
												<select id ="departments" class="form-control select2 departments" multiple="multiple" name = "departments[]" data-placeholder="Select Departments" style="width:100%">

													<option value="all">All</option>
													<?php foreach ($departments as $row): ?>

														<option value="<?php echo $row['DEPARTMENTID']?>">
															<?php echo $row['DEPARTMENTNAME']; ?>
														</option>

													<?php endforeach; ?>
												</select>
											</div>
											<!-- /.col-lg-6 -->
											<div class="col-lg-6">
												<!-- <div class="input-group"> -->
												<p>Users</p>
													<select id ="users" class="form-control select2 users" multiple="multiple" name = "users[]" data-placeholder="Select Departments" style="width:100%">

														<?php foreach ($users as $row): ?>

															<option value="<?php echo $row['USERID']?>">
																<?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?>
															</option>

														<?php endforeach; ?>


													</select>
												<!-- </div> -->
												<!-- /input-group -->
											</div>
											<!-- /.col-lg-6 -->
										</div>
										<!-- /.row -->
										<br>
										<div class="form-group">
		                  <label for="uploadDoc">Select a file to upload</label>
		                  <input type="file" id="upload" name="document">
		                </div>
										<div class="form-group">
		                  <label>Remarks</label>
		                  <input type="text" class="form-control"  name="remarks" placeholder="Ex. Approved, Final">
		                </div>
		              </div>
		              <div class="modal-footer">
		                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
		                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Upload Document</button>
		              </div>
									</form>
		            </div>
		            <!-- /.modal-content -->
		          </div>
		          <!-- /.modal-dialog -->
		        </div>
		        <!-- /.modal -->
				</section>
					</div>
			<?php require("footer.php"); ?>
		</div>
		<script>
			$("#myProjects").addClass("active");
			$('.select2').select2();

			$(document).on("click", "#backBtn", function() {
				var $project = $("#backForm").attr('data-id');
				$("#backForm").attr("name", "formSubmit");
				$("#backForm").append("<input type='hidden' name='project_ID' value= " + $project + ">");
				$("#backForm").submit();
				});

			$(document).on("click", ".document", function() {
				var $id = $(this).attr('data-id');
				$(".acknowledgeDocument").attr("name", "formSubmit");
				$(".acknowledgeDocument").append("<input type='hidden' name='documentID' value= " + $id + ">");
				$(".acknowledgeDocument").submit();
				});

			$(function () {
		    $('#documentsTable').DataTable({
		      'paging'      : false,
		      'lengthChange': false,
		      'searching'   : true,
		      'ordering'    : true,
		      'info'        : false,
		      'autoWidth'   : false
		    })
		  });

		</script>
	</body>
</html>
