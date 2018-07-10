<html>
	<head>
		<title>Kernel - Dashboard</title>
		<!-- <link rel = "stylesheet" href = "<?php echo base_url("/assets/css/dashboardStyle.css")?>"> -->
	</head>
	<body>
		<?php require("frame.php"); ?>

		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
					Welcome, <b><?php echo $_SESSION['FIRSTNAME'] . " " . $_SESSION['LASTNAME']; ?>!</b>
				</h1>
			</section>

			<section class="content container-fluid">
				<button id="success" type="button" class="btn btn-success">Test Success</button>
				<button id="warning" type="button" class="btn btn-warning">Test Warning</button>
				<button id="danger" type="button" class="btn btn-danger">Test Danger</button>
				<button id="info" type="button" class="btn btn-info">Test Info</button>

				<?php if($delayedTaskPerUser != NULL || $tasks3DaysBeforeDeadline != NULL): ?>
				<div>
				<table id="logsList" class="table table-bordered table-hover">
					<tbody>
						<th>Project Title</th>
						<th>Task</th>
						<th>Task End Date</th>
						<th>Status</th>
						<?php
							foreach ($delayedTaskPerUser as $row) {
								echo "<tr style='color:red'>";
									echo "<td>" . $row['PROJECTTITLE'] . "</td>";
									echo "<td>" . $row['TASKTITLE'] . "</td>";
									echo "<td>" . $row['TASKENDDATE'] . "</td>";
									echo "<td> DELAYED </td>";
								echo "</tr>";
							}

							foreach ($tasks3DaysBeforeDeadline as $data) {
								echo "<tr>";
									echo "<td>" . $data['PROJECTTITLE'] . "</td>";
									echo "<td>" . $data['TASKTITLE'] . "</td>";
									echo "<td>" . $data['TASKENDDATE'] . "</td>";
									echo "<td>" . $data['TASKDATEDIFF'] . " day/s before deadline</td>";
								echo "</tr>";
							}
						?>
					</tbody>
				</table>
			</div>
			<?php endif;?>

		</section>
			</div>

		<?php require("footer.php"); ?>

	</div> <!--.wrapper closing div-->

	<script>
		$("#dashboard").addClass("active");

		$(document).ready(function()
		{
			$("#success").click(function(){
				$.notify({
		      // options
		      icon: 'fa fa-check',
		      message: ' Hello Success World'
		      },{
		      // settings
		      type: 'success',
		      offset: 60,
		      delay: 5000,
		      placement: {
		        from: "top",
		        align: "center"
		      },
		      animate: {
		        enter: 'animated fadeInDownBig',
		        exit: 'animated fadeOutUpBig'
		      },
		      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
		        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
		        '<span data-notify="icon"></span>' +
		        '<span data-notify="title">{1}</span>' +
		        '<span data-notify="message">{2}</span>' +
		      '</div>'
		      });
				});

				$("#danger").click(function(){
					$.notify({
			      // options
			      icon: 'fa fa-ban',
			      message: ' Hello Danger World'
			      },{
			      // settings
			      type: 'danger',
			      offset: 60,
			      delay: 5000,
			      placement: {
			        from: "top",
			        align: "center"
			      },
			      animate: {
			        enter: 'animated fadeInDownBig',
			        exit: 'animated fadeOutUpBig'
			      },
			      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
			        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
			        '<span data-notify="icon"></span>' +
			        '<span data-notify="title">{1}</span>' +
			        '<span data-notify="message">{2}</span>' +
			      '</div>'
			      });
					});

					$("#warning").click(function(){
						$.notify({
				      // options
				      icon: 'fa fa-warning',
				      message: ' Hello Warning World'
				      },{
				      // settings
				      type: 'warning',
				      offset: 60,
				      delay: 5000,
				      placement: {
				        from: "top",
				        align: "center"
				      },
				      animate: {
				        enter: 'animated fadeInDownBig',
				        exit: 'animated fadeOutUpBig'
				      },
				      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
				        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
				        '<span data-notify="icon"></span>' +
				        '<span data-notify="title">{1}</span>' +
				        '<span data-notify="message">{2}</span>' +
				      '</div>'
				      });
						});

						$("#info").click(function(){
							$.notify({
					      // options
					      icon: 'fa fa-info',
					      message: ' Hello Info World'
					      },{
					      // settings
					      type: 'info',
					      offset: 60,
					      delay: 5000,
					      placement: {
					        from: "top",
					        align: "center"
					      },
					      animate: {
					        enter: 'animated fadeInDownBig',
					        exit: 'animated fadeOutUpBig'
					      },
					      template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
					        '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
					        '<span data-notify="icon"></span>' +
					        '<span data-notify="title">{1}</span>' +
					        '<span data-notify="message">{2}</span>' +
					      '</div>'
					      });
							});
		});
	</script>

	</body>
</html>
