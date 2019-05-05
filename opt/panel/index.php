<?php
require_once 'inc/lib.php';

session_start();

// Destroy session on ?logout
if (isset($_GET['logout'])) {
	$_SESSION = array();
	session_destroy();
}

// Redirect logged in users to the file manager
if (!empty($_SESSION['user']) && $user = user_info($_SESSION['user'])) {
	header('Location: dashboard.php');
}

?><!doctype html>
<html>
<head>
	<title>MCHostPanel | Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="css/smooth.css" id="smooth-css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style type="text/css">
		body {
			background-image: url(img/bg.jpg);
			-webkit-background-size: cover;
			-moz-background-size: cover;
			background-size: cover;
		}
	</style>
</head>
<body>
<noscript>
	<p class="alert alert-warning"><strong>Enable Javascript:</strong> Javascript is required to use MCGG.</p>
</noscript>
<form class="modal form-horizontal" action="dashboard.php" method="post">
	<div class="modal-header" align="center">
		<h1><i class="fa fa-server" aria-hidden="true"></i> MC<span style="color:gold">GG</span></h1>
		<h3><?php $count=0; foreach(user_list() as $user) { $count++; } echo $count;?> server(s) running...</h3>
	</div>
	<div class="modal-body">
		<?php
		if (!empty($_GET['error']) && $_GET['error'] == 'badlogin')
			echo '<p class="alert alert-error">Login information is incorrect.</p>';
		?>
		<div class="control-group">
			<label class="control-label" for="user">Username:</label>

			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-user"></i></span>
					<input class="span2" type="text" name="user" id="user">
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="pass">Password:</label>

			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-lock"></i></span>
					<input class="span2" type="password" name="pass" id="pass">
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" type="submit">Login</button>
	</div>
</form>
<small class="muted pull-left" style="position:absolute;bottom:15px;left:15px;"> <br>Guest: <?=$_SERVER['REMOTE_ADDR'] ?> <br>Time: <?=date('d/m/Y') ?></small>
	<script src="js/header.js"></script>
</body>
