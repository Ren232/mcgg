<!DOCTYPE HTML>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php if((preg_match("/iPad/i",$_SERVER['HTTP_USER_AGENT'])) || (preg_match("/iPhone/i",$_SERVER['HTTP_USER_AGENT'])) || (preg_match("/iPod/i",$_SERVER['HTTP_USER_AGENT']))) {
		echo "<title>MineAdmin</title>";
	} else {
		echo "<title>MineAdmin</title>";
	}
	?>
	<link rel="stylesheet" href="css/style_login.css" type="text/css" media="screen" />
	<link rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)" href="css/ipad.portrait.css"> 
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery-css-transform.js"></script> 
	<script type="text/javascript" src="js/system_login.js"></script>
	<link rel="apple-touch-icon" href="images/apple-icon.png"/>
	<link rel="apple-touch-startup-image" href="/startup.png" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-touch-fullscreen" content="yes" />
</head>
<body>
	<div id="login-form">
		<form action="javascript:login();" method="post">
			<div id="login_form_i">
				<h1>MineAdmin</h1>
				<label><span>Username</span><input type="text" name="user" id="user" class="autofocus" /></label><br />
				<label><span>Password</span><input type="password" name="pass" id="pass" /></label><br />
				<input type="submit" value="Login">
			</div>
			
			<div id="loading" style="display:none;">
				<div id="div4"> 
  					<div class="bar1"></div> 
  					<div class="bar2"></div> 
					<div class="bar3"></div> 
  					<div class="bar4"></div> 
  					<div class="bar5"></div> 
  					<div class="bar6"></div> 
  					<div class="bar7"></div> 
  					<div class="bar8"></div> 
				</div> 
			</div>
		</form>
	</div>
	
</body>
</html>