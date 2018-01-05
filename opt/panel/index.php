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
	<title>MCHostPanel</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
	<link rel="stylesheet" href="css/smooth.css" id="smooth-css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<meta name="author" content="Alan Hardman (http://phpizza.com)">
	<style type="text/css">
		body {
			background-image: url(img/bg.jpg);
			-webkit-background-size: cover;
			-moz-background-size: cover;
			background-size: cover;
		}
.clock {
  position: relative;
  width: 200px;
  height: 200px;
  margin: 10px auto;
	margin-bottom: 30px;
}
#hours, #minutes, #seconds {
  position: absolute;
  top: 0; left: 0; bottom: 0; right: 0;
  /* background-color: hsla(20, 75%, 25%, 0.025); */
  /* border-radius: 50%; */
}
#seconds {
  transform: scale(1.0);
}
#minutes {
  transform: scale(0.85);
}
#hours {
  transform: scale(0.70);
}
.date {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%,-50%);
  font-size: 1.2rem;
  text-align: center;
  color: hsla(80, 100%, 25%, 0.85);
  width: 50%;
}
sec,min,hour {
  position: absolute;
  width: 2%;
  height: 5%;
  top: 0; left: 50%;
  background-color: hsla(80, 100%, 25%, 0.15);
  border: 1px solid hsla(0, 0%, 100%, 0.05);
  transform-origin: 0% 1000%;
  /* transition: all linear 0.5s; */
}
.tick {
  background-color: hsla(80, 100%, 25%, 0.85);
}
	</style>
</head>
<body>
<noscript>
	<p class="alert alert-warning"><strong>Enable Javascript:</strong> Javascript is required to use MCHostPanel.</p>
</noscript>
<form class="modal form-horizontal" action="dashboard.php" method="post">
<div align="center">
	<div class="clock">
		<div id="seconds"></div>
		<div id="minutes"></div>
		<div id="hours"></div>
		<div id="todayDate" class="date"></div>
	</div>	
</div>
	<div class="modal-header" align="center">
		<h1><i class="fa fa-server" aria-hidden="true"></i> MC<span style="color:green">Host</span>Panel</h1>
		<h3><?php $count=0; foreach(user_list() as $user) { $count++; } echo $count;?> server đã host</h3>
	</div>
	<div class="modal-body">
		<?php
		if (!empty($_GET['error']) && $_GET['error'] == 'badlogin')
			echo '<p class="alert alert-error">Thông tin đăng nhập không đúng.</p>';
		?>
		<div class="control-group">
			<label class="control-label" for="user">Tên đăng nhập</label>

			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-user"></i></span>
					<input class="span2" type="text" name="user" id="user">
				</div>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="pass">Mật khẩu</label>

			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-lock"></i></span>
					<input class="span2" type="password" name="pass" id="pass">
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" type="submit">Đăng nhập</button>
	</div>
</form>
<small class="muted pull-left" style="position:absolute;bottom:15px;left:15px;">&copy; <?php echo date('Y'); ?> <a href="https://phpizza.com/">Alan Hardman</a>
 - Re-edit bởi GGJohny<br>From: <?=$_SERVER['REMOTE_ADDR'] ?> - Time: <?=date('d/m/Y') ?></small>
	<script src="js/header.js"></script>
</body>
