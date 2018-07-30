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
						<button id="backBtn" class="btn btn-default btn"><i class="fa fa-arrow-left"></i></button>
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
						<li><a href="<?php echo base_url("index.php/controller/myProjects"); ?>"><i class="fa fa-dashboard"></i> My Projects</a></li>
						<!-- <li class="active">Here</li> -->
						<li class='active'>Documents</li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content container-fluid">
					<!-- <div id="filterButtons">
						<h5>Arrange by</h5>
					</div> -->

					<div class="row">
		        <div class="col-xs-12">
		          <div class="box box-danger">
		            <div class="box-header">
		              <h3 class="box-title">
										<?php if($projectProfile['PROJECTSTATUS'] != 'Completed' &&  $projectProfile['PROJECTSTATUS'] != 'Archived'):?>
											<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-upload"><i class="fa fa-upload"></i> Upload</button>
										<?php endif;?>
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
			                  <th width="25%" class="text-center">Document Name</th>
			                  <th width="15%" class="text-center">Uploaded By</th>
			                  <th width="15" class="text-center">Department</th>
												<th width="10" class="text-center">Uploaded On</th>
			                  <th width="25%" class="text-center">Remarks</th>
												<th width="10%" class="text-center">Action</th>
			                </tr>
										</thead>
										<tbody>

											<form action='acknowledgeDocument' method='POST' id ='acknowledgeForm'> </form>

										<?php

											foreach($documentsByProject as $document){
												if($document['DOCUMENTSTATUS'] == 'For Acknowledgement'){

													if($document['users_UPLOADEDBY'] == $_SESSION['USERID']){

														$buttonAction = "<a href = '" . $document['DOCUMENTLINK']. "' download>
														<button type='button' class='btn btn-success'>
														<i class='fa fa-download'></i></button></a>

														<button disabled type='button' class='btn btn-warning document' name='documentButton'>
															<i class='fa fa-eye'></i></button>";

															echo "<tr>";
																echo "<td>" . $document['DOCUMENTNAME'] . "</td>";
																echo "<td>" . $document['FIRSTNAME'] . " " . $document['LASTNAME'] . "</td>";
																echo "<td>" . $document['DEPARTMENTNAME'] . "</td>";
																echo "<td>" . date('M d, Y', strtotime($document['UPLOADEDDATE'])) . "</td>";
																echo "<td>" . $document['REMARKS'] . "</td>";
																echo "<td align='center'> " . $buttonAction . " </td>";
															echo "</tr>";

													} else {

														foreach ($documentAcknowledgement as $docuToAcknowledge){
															if($docuToAcknowledge['documents_DOCUMENTID'] == $document['DOCUMENTID']){
																if($docuToAcknowledge['ACKNOWLEDGEDDATE'] == NULL){

																	$buttonAction = "<a href = '" . $document['DOCUMENTLINK']. "' download>
																	<button type='button' class='btn btn-success'>
																	<i class='fa fa-download'></i></button></a>

																	<button type='button' class='btn btn-warning document' name='documentButton'
																		id='acknowledgeButton' data-toggle='modal' data-target='#confirmAcknowledge'
																		data-docuID ='" . $document['DOCUMENTID'] . "'
																		data-projectID = '" . $projectProfile['PROJECTID'] . "'
																		data-docuName = '" . $document['DOCUMENTNAME'] ."'>
																		<i class='fa fa-eye'></i></button>";
																}

																else {

																	$buttonAction = "<a href = '" . $document['DOCUMENTLINK']. "' download>
																	<button type='button' class='btn btn-success'>
																	<i class='fa fa-download'></i></button></a>

																	<button disabled type='button' class='btn btn-warning document' name='documentButton'>
																		<i class='fa fa-eye'></i></button>";

																}
															}
														}
														echo "<tr>";
															echo "<td>" . $document['DOCUMENTNAME'] . "</td>";
															echo "<td>" . $document['FIRSTNAME'] . " " . $document['LASTNAME'] . "</td>";
															echo "<td>" . $document['DEPARTMENTNAME'] . "</td>";
															echo "<td>" . date('M d, Y', strtotime($document['UPLOADEDDATE'])) . "</td>";
															echo "<td>" . $document['REMARKS'] . "</td>";
															echo "<td align='center'> " . $buttonAction . " </td>";
														echo "</tr>";
													}

												}

												else {

													$buttonAction = "<a href = '" . $document['DOCUMENTLINK']. "' download>
													<button type='button' class='btn btn-success'>
													<i class='fa fa-download'></i></button></a>

													<button disabled type='button' class='btn btn-warning document' name='documentButton'>
														<i class='fa fa-eye'></i></button>";

														echo "<tr>";
															echo "<td>" . $document['DOCUMENTNAME'] . "</td>";
															echo "<td>" . $document['FIRSTNAME'] . " " . $document['LASTNAME'] . "</td>";
															echo "<td>" . $document['DEPARTMENTNAME'] . "</td>";
															echo "<td>" . date('M d, Y', strtotime($document['UPLOADEDDATE'])) . "</td>";
															echo "<td>" . $document['REMARKS'] . "</td>";
															echo "<td align='center'> " . $buttonAction . " </td>";
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

						<!-- CONFIRM ACKNOWLEDGEMENT -->
						<div class="modal fade" id="confirmAcknowledge" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h2 class="modal-title">Confirm Document Acknowledgement</h2>
									</div>
									<div class="modal-body">
										<h4>Are you sure you want to acknowledge this document?</h4>
										<div class="modal-footer">
											<button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-close"></i> Close</button>
											<button id = "doneConfirm" type="submit" class="btn btn-success" data-id=""><i class="fa fa-check"></i> Confirm</button>
										</div>
									</div>
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

			$(document).on("click", "#acknowledgeButton", function() {

				var $documentID = $(this).attr('data-docuID');
				var $projectID = $(this).attr('data-projectID');
				var $documentName = $(this).attr('data-docuName');

				$("#doneConfirm").attr('data-docuID', $documentID);
				$("#doneConfirm").attr('data-projectID', $projectID);
				$("#doneConfirm").attr('data-docuName', $documentName);

			});

			$(document).on("click", "#doneConfirm", function() {

				var $documentID = $(this).attr('data-docuID');
				var $projectID = $(this).attr('data-projectID');
				var $documentName = $(this).attr('data-docuName');

				$("#acknowledgeForm").attr("name", "formSubmit");
				$("#acknowledgeForm").append("<input type='hidden' name='documentID' value= " + $documentID + ">");
				$("#acknowledgeForm").append("<input type='hidden' name='projectID' value= " + $projectID + ">");
				$("#acknowledgeForm").append("<input type='hidden' name='fileName' value= " + $documentName + ">");
				$("#acknowledgeForm").submit();
			});

		</script>
	</body>
</html>
