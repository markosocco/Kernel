<html>
	<head>
		<title>Kernel - Documents</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/documentsStyle.css")?>">
	</head>
	<body>

		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Documents
					<small>What documents do I have</small>
				</h1>
			</section>

			<!-- Main content -->
			<section class="content container-fluid">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">All documents of all projects</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="documentList" class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Document Name</th>
									<th>Project</th>
									<th>Uploaded By</th>
									<th>Department</th>
									<th>Uploaded On</th>
									<th align="center"></th>
								</tr>
							</thead>
							<tbody>
								<!-- SAMPLE DATA. PLEASE DELETE  -->

								<!-- ARRANGE DOCUMENTS BY UPLOADED ON. MOST RECENT ON TOP -->
								<tr>
									<td>1</td>
									<td>CapEx as of March 34, 2120.ppt</td>
									<td>New York City</td>
									<td>Manuel Cabacaba</td>
									<td>MIS</td>
									<td>March 36, 2120</td>
									<td align="center"><button type="button" class="btn btn-success"><i class="fa fa-download"></i> Download</button></td>
								</tr>

								<!-- SAMPLE DATA. PLEASE DELETE  -->
								<tr>
									<td>2</td>
									<td>CapEx as of March 37, 2120.ppt</td>
									<td>SM Southmall</td>
									<td>Manuel Cabacaba</td>
									<td>MIS</td>
									<td>March 39, 2120</td>
									<td align="center"><button type="button" class="btn btn-success"><i class="fa fa-download"></i> Download</button></td>
								</tr>
							</tbody>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

			</section>

		</div>


		<?php require("footer.php"); ?>

		</div> <!--.wrapper closing div-->

		<script>
		$("#documents").addClass("active");
		$(function () {
	    $('#documentList').DataTable({
	      'paging'      : false,
	      'lengthChange': false,
	      'searching'   : false,
	      'ordering'    : true,
	      'info'        : false,
	      'autoWidth'   : false
	    })
	  })
		</script>

	</body>
</html>
