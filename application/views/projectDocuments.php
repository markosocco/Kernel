<html>
	<head>
		<title>Kernel - Project Documents</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/projectDocumentsStyle.css")?>"> -->
		<!-- TRANSFER TO CSS -->
		<style>
	    .example-modal .modal
			{
	      position: relative;
	      top: auto;
	      bottom: auto;
	      right: auto;
	      left: auto;
	      display: block;
	      z-index: 1;
	    }

	    .example-modal .modal
			{
	      background: transparent !important;
	    }
	  </style>
	</head>
	<body class="hold-transition skin-red sidebar-mini">
		<?php require("frame.php"); ?>

			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<div style="margin-bottom:10px">
						<!-- IDK HOW TO MAKE THIS WORK. RETURNS TO projectGantt -->
						<a href="#" class="btn btn-default btn-xs"><i class="fa fa-arrow-left"></i> Return to Project</a>
					</div>
					<h1>
						Documents
						<small><?php echo $projectProfile['PROJECTTITLE']; ?></small>
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
		              <div class="box-tools">
		                <div class="input-group input-group-sm" style="width: 150px;">
		                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

		                  <div class="input-group-btn">
		                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
		                  </div>
		                </div>
		              </div>
		            </div>
		            <!-- /.box-header -->
		            <div class="box-body table-responsive no-padding">

									<?php if($documentsByProject != NULL):?>
		              <table class="table table-hover">
		                <tr>
		                  <th>#</th>
		                  <th>Document Name</th>
		                  <th>Uploaded By</th>
		                  <th>Department</th>
											<th>Uploaded On</th>
		                  <th>Remarks</th>
											<th align="center"></th>
		                </tr>

										<?php
											foreach ($documentsByProject as $row) {

												$dateUploaded = $row['UPLOADEDDATE'];
												$MonthNum = substr($dateUploaded, 5, 2);
												$Day = substr($dateUploaded, 8, 2);
												$Year = substr($dateUploaded, 0, 4);
												$Month = "";

												switch (intval($MonthNum))
												{
													case 1: $Month = 'Jan'; break;
													case 2: $Month = 'Feb'; break;
													case 3: $Month = 'Mar'; break;
													case 4: $Month = 'Apr'; break;
													case 5: $Month = 'May'; break;
													case 6: $Month = 'Jun'; break;
													case 7: $Month = 'Jul'; break;
													case 8: $Month = 'Aug'; break;
													case 9: $Month = 'Sep'; break;
													case 10: $Month = 'Oct'; break;
													case 11: $Month = 'Nov'; break;
													case 12: $Month = 'Dec'; break;
												}

												$formattedDate = $Month . " " . $Day . ", " . $Year;

												echo "<tr>";
													echo "<td></td>";
													echo "<td>" . $row['DOCUMENTNAME'] . "</td>";
													echo "<td>" . $row['FIRSTNAME'] . " " . $row['LASTNAME'] . "</td>";
													echo "<td>" . $row['DEPARTMENTNAME'] . "</td>";
													echo "<td>" . $formattedDate . "</td>";
													echo "<td>" . $row['REMARKS'] . "</td>";
													echo "<td align='center'><a href = '" . $row['DOCUMENTLINK']. "' download>
													<button type='button' class='btn btn-success'>
													<i class='fa fa-download'></i>Download</button></a></td>";
												echo "</tr>";
											}
										?>
		              </table>
								<?php endif;?>
		            </div>
		            <!-- /.box-body -->
		          </div>
		          <!-- /.box -->
		        </div>

						<!-- SET FLASHDATA -->
						<!-- <?php $this->session->set_flashdata('projectID', $projectProfile['PROJECTID']); ?> -->

						<!-- <?php echo "Session: " . $_SESSION['projectID']; ?> -->

						<!-- <input type="hidden" name="project_ID" value= "<?php echo $projectProfile['PROJECTID']; ?>"> -->
						<!-- <?php echo $errors; ?> -->

						<!-- <?php echo "hello ---- " . $projectProfile['PROJECTID']; ?> -->

						<?php echo form_open_multipart('controller/uploadDocument');?>

							<input type="hidden" name="project_ID" value= "<?php echo $projectProfile['PROJECTID']; ?>">

						<div class="modal fade" id="modal-upload" tabindex="-1">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h4 class="modal-title">Upload a Document</h4>
		              </div>
		              <div class="modal-body">
										<p><b>Upload this document for</b></p>
										<div class="row">
											<div class="col-lg-6">
												<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" name="sendTo">
														</span>
													<input type="text" class="form-control" placeholder="All" value="all" disabled readonly>
												</div>
												<!-- /input-group -->
											</div>
											<!-- /.col-lg-6 -->
											<div class="col-lg-6">
												<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" name="sendTo">
														</span>
													<select class="form-control select2" multiple="multiple" name = "department_0[]" data-placeholder="Select Departments" style="width:100%">
														<?php foreach ($departments as $data): ?>

															<option>
																<?php echo $data['DEPARTMENTNAME']; ?>
															</option>

														<?php endforeach; ?>
													</select>
												</div>
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
		                  <input type="text" class="form-control" placeholder="Ex. Approved, Final">
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
			$('.select2').select2()

		</script>
	</body>
</html>
