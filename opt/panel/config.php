<?php
require_once 'inc/lib.php';

session_start();
if (empty($_SESSION['user']) || !$user = user_info($_SESSION['user'])) {
	// Not logged in, redirect to login page
	header('Location: .');
	exit('Not Authorized');
}

?><!doctype html>
<html>
<head>
	<title>Configuration | MCHostPanel</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="css/smooth.css" id="smooth-css">
	<link rel="stylesheet" href="css/style.css">
	<style type="text/css">
		form {
			margin: 0;
		}
	</style>
	<script src="js/jquery-1.7.2.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
<?php require 'inc/top.php'; ?>
<div class="col-md-6">
				<div class="control-group">
					<label class="control-label" for="ram">Ngrok key <? if(empty($user['key']) || $user['key']==1234567890) { echo '- You need a ngrok key to make your server work.'; } ?></label>

					<div class="controls">
						<div class="input-append">
							<input class="span6" type="text" name="ngrok" id="ngrok" onchange="modify(this.value)" placeholder="ngrok key.." value="<?=$user['key']?>">
						</div>
						<span class="text-info"><a href="//dashboard.ngrok.com/">Ngrok Dashboard</a></span>
					</div>
				</div>
</div>
