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

										<!-- SAMPLE DATA. PLEASE DELETE  -->

										<!-- ARRANGE DOCUMENTS BY UPLOADED ON. MOST RECENT ON TOP -->
										<tr>
											<td>1</td>
											<td>CapEx as of March 34, 2120.ppt</td>
											<td>Manuel Cabacaba</td>
											<td>MIS</td>
											<td>March 36, 2120</td>
											<td>Any SHORT text here</td>
											<td align="center"><button type="button" class="btn btn-success"><i class="fa fa-download"></i> Download</button></td>
										</tr>

										<!-- SAMPLE DATA. PLEASE DELETE  -->
										<tr>
											<td>2</td>
											<td>CapEx as of March 37, 2120.ppt</td>
											<td>Manuel Cabacaba</td>
											<td>MIS</td>
											<td>March 39, 2120</td>
											<td>Final version</td>
											<td align="center"><button type="button" class="btn btn-success"><i class="fa fa-download"></i> Download</button></td>
										</tr>

		              </table>
		            </div>
		            <!-- /.box-body -->
		          </div>
		          <!-- /.box -->
		        </div>

						<?php echo form_open_multipart('controller/uploadDocument/?id=' . $projectProfile['PROJECTID']);?>

						<input type="hidden" name="project_ID" value= "<?php echo $projectProfile['PROJECTID']; ?>">

						<?php echo $projectProfile['PROJECTID']; ?>

						<div class="modal fade" id="modal-upload" tabindex="-1">
		          <div class="modal-dialog">
		            <div class="modal-content">
		              <div class="modal-header">
		                <h4 class="modal-title">Upload a Document</h4>
		              </div>
		              <div class="modal-body">

											<div class="form-group">
			                  <label for="uploadDoc">Select a file to upload</label>
			                  <input type="file" id="uploadDoc" name="docu">

			                  <!-- <p class="help-block"></p> -->
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
		</script>
	</body>
</html>
