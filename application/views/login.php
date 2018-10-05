<html>
	<head>
		<title>Kernel - Log In</title>
		<link rel = "stylesheet" href = "<?php echo base_url("/assets/css/loginStyle.css")?>">
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
		<!-- Bootstrap Notify -->
		<link rel="stylesheet" href="<?php echo base_url()."assets/"; ?>animate.css/animate.min.css"/>
	</head>
	<body>

		<div id = mainContainer>

			<img class="" id = "logo"  src = "<?php echo base_url("/assets/media/tei.png")?>">
			<h3 id="formHeader">KERNEL:<br>PROJECT MANAGEMENT</h3>

			<div id = "login" class = "loginElements">
				<form name = "loginForm" action = "validateLogin" method = "POST">
					<input type = "text" placeholder = "USERNAME" name = "email" value = "<?php if (isset($_SESSION['stickyemail'])) echo $_SESSION['stickyemail']; ?>" required>
					<input type = "password" placeholder = "PASSWORD" name = "password" required>
					<input type = "submit" class="btn btn-success" name = "submitLogin" value = "LOG IN">
				</form>
			</div>

			<p>Unable to Log In? <a href = "<?php echo base_url("index.php/controller/contact"); ?>" id="resetPass">Reset Password</a></p>
		</div>

		<footer>
			<p>&copy; 2018 <a href="http://www.ilovetaters.com">Taters Enterprises Inc</a>. All rights reserved.</p>
			<!-- <p>© 2018 Team Lowkey, Inc. All Rights Reserved</p> -->
		</footer>

		<!-- jQuery 3 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/jquery/dist/jquery.min.js"></script>
		<!-- Bootstrap 3.3.7 -->
		<script src="<?php echo base_url()."assets/"; ?>bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
		<!-- Bootstrap Notify -->
		<script src="<?php echo base_url()."assets/"; ?>bootstrap-notify-3.1.3/dist/bootstrap-notify.min.js"></script>

		<script>

		// ALERTS
		<?php if (isset($_SESSION['alertMessage'])): ?>
				$(document).ready(function()
				{
					dangerAlert();
				});
		<?php endif; ?>

	    function successAlert ()
	    {
	      $.notify({
	        // options
	        icon: 'fa fa-check',
	        message: '<?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
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
	    };

	    function dangerAlert ()
	    {
	      $.notify({
	        // options
	        icon: 'fa fa-ban',
	        message: '<?php if (isset($_SESSION['alertMessage'])) echo $_SESSION['alertMessage']; ?>'
	        },{
	        // settings
	        type: 'danger',
	        offset: 20,
	        delay: 0,
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
	    };

	    function warningAlert ()
	    {
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
	    };

	    function infoAlert ()
	    {
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
	      };
	  </script>

	</body>
</html>
